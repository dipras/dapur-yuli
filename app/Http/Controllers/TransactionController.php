<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        $query = Transaction::with('user')->orderBy('created_at', 'desc');
        
        // Filter by date if provided
        if ($request->has('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        
        if ($request->has('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }
        
        // Filter by payment method if provided
        if ($request->has('payment_method') && $request->payment_method != '') {
            $query->where('payment_method', $request->payment_method);
        }
        
        $transactions = $query->paginate(15);
        
        // Calculate total sum
        $totalSum = Transaction::when($request->has('date_from'), function($q) use ($request) {
                return $q->whereDate('created_at', '>=', $request->date_from);
            })
            ->when($request->has('date_to'), function($q) use ($request) {
                return $q->whereDate('created_at', '<=', $request->date_to);
            })
            ->when($request->has('payment_method') && $request->payment_method != '', function($q) use ($request) {
                return $q->where('payment_method', $request->payment_method);
            })
            ->sum('total');
        
        return view('transactions.index', compact('transactions', 'totalSum'));
    }
    
    public function show(Transaction $transaction)
    {
        return view('transactions.show', compact('transaction'));
    }
    
    public function report(Request $request)
    {
        $period = $request->input('period', 'weekly'); // daily, weekly, monthly, yearly
        $startDate = null;
        $endDate = now();
        
        // Determine date range based on period
        switch ($period) {
            case 'daily':
                $startDate = now()->startOfDay();
                break;
            case 'weekly':
                $startDate = now()->subDays(6)->startOfDay();
                break;
            case 'monthly':
                $startDate = now()->startOfMonth();
                break;
            case 'yearly':
                $startDate = now()->startOfYear();
                break;
        }
        
        // Get transactions for the period
        $transactions = Transaction::whereBetween('created_at', [$startDate, $endDate])
            ->orderBy('created_at', 'desc')
            ->get();
        
        // Calculate summary statistics
        $totalRevenue = $transactions->sum('total');
        $totalTransactions = $transactions->count();
        $averageTransaction = $totalTransactions > 0 ? $totalRevenue / $totalTransactions : 0;
        
        // Cash vs QRIS breakdown
        $cashTotal = $transactions->where('payment_method', 'cash')->sum('total');
        $qrisTotal = $transactions->where('payment_method', 'qris')->sum('total');
        
        // Prepare chart data based on period
        $chartData = $this->prepareChartData($transactions, $period, $startDate, $endDate);
        
        // Get today's transactions
        $todayTransactions = Transaction::whereDate('created_at', now()->toDateString())
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();
        
        return view('transactions.report', compact(
            'totalRevenue',
            'totalTransactions',
            'averageTransaction',
            'cashTotal',
            'qrisTotal',
            'chartData',
            'todayTransactions',
            'period',
            'startDate',
            'endDate'
        ));
    }
    
    private function prepareChartData($transactions, $period, $startDate, $endDate)
    {
        $labels = [];
        $data = [];
        
        if ($period === 'daily') {
            // Hourly data for today
            for ($hour = 0; $hour < 24; $hour++) {
                $labels[] = sprintf('%02d:00', $hour);
                $data[] = $transactions->filter(function($t) use ($hour) {
                    return $t->created_at->hour == $hour;
                })->sum('total');
            }
        } elseif ($period === 'weekly') {
            // Daily data for past 7 days
            $days = ['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'];
            for ($i = 6; $i >= 0; $i--) {
                $date = now()->subDays($i);
                $dayIndex = $date->dayOfWeek;
                $labels[] = $days[$dayIndex];
                $data[] = $transactions->filter(function($t) use ($date) {
                    return $t->created_at->toDateString() === $date->toDateString();
                })->sum('total');
            }
        } elseif ($period === 'monthly') {
            // Daily data for current month
            $daysInMonth = $endDate->daysInMonth;
            for ($day = 1; $day <= $daysInMonth; $day++) {
                $labels[] = (string)$day;
                $data[] = $transactions->filter(function($t) use ($day) {
                    return $t->created_at->day == $day;
                })->sum('total');
            }
        } elseif ($period === 'yearly') {
            // Monthly data for current year
            $months = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
            for ($month = 1; $month <= 12; $month++) {
                $labels[] = $months[$month - 1];
                $data[] = $transactions->filter(function($t) use ($month) {
                    return $t->created_at->month == $month;
                })->sum('total');
            }
        }
        
        return [
            'labels' => $labels,
            'data' => $data
        ];
    }
}

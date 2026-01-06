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
        
        return view('reports.index', compact(
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
    
    public function exportForm()
    {
        return view('reports.export');
    }
    
    public function exportDownload(Request $request)
    {
        $request->validate([
            'period' => 'required|in:daily,weekly,monthly,yearly,custom',
            'format' => 'required|in:csv,pdf,json',
            'year' => 'nullable|integer|min:2020|max:2100'
        ]);
        
        // Determine date range
        $period = $request->period;
        $year = $request->year ?? now()->year;
        
        if ($period === 'custom') {
            $startDate = $request->start_date ? date('Y-m-d', strtotime($request->start_date)) : now()->startOfYear();
            $endDate = $request->end_date ? date('Y-m-d', strtotime($request->end_date)) : now()->endOfYear();
        } else {
            switch ($period) {
                case 'daily':
                    $startDate = now()->startOfDay();
                    $endDate = now()->endOfDay();
                    break;
                case 'weekly':
                    $startDate = now()->startOfWeek();
                    $endDate = now()->endOfWeek();
                    break;
                case 'monthly':
                    $startDate = now()->startOfMonth();
                    $endDate = now()->endOfMonth();
                    break;
                case 'yearly':
                    $startDate = now()->setYear($year)->startOfYear();
                    $endDate = now()->setYear($year)->endOfYear();
                    break;
            }
        }
        
        // Get transactions with filters
        $query = Transaction::with('user')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->orderBy('created_at', 'desc');
        
        if ($request->payment_method) {
            $query->where('payment_method', $request->payment_method);
        }
        
        $transactions = $query->get();
        
        // Generate file based on format
        switch ($request->format) {
            case 'csv':
                return $this->exportCSV($transactions, $startDate, $endDate);
            case 'pdf':
                return $this->exportPDF($transactions, $startDate, $endDate);
            case 'json':
                return $this->exportJSON($transactions, $startDate, $endDate);
        }
    }
    
    private function exportCSV($transactions, $startDate, $endDate)
    {
        $filename = 'laporan-keuangan-' . now()->format('Y-m-d-His') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];
        
        $callback = function() use ($transactions, $startDate, $endDate) {
            $file = fopen('php://output', 'w');
            
            // UTF-8 BOM for Excel compatibility
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            
            // Header
            fputcsv($file, ['LAPORAN KEUANGAN']);
            fputcsv($file, ['Periode: ' . date('d/m/Y', strtotime($startDate)) . ' - ' . date('d/m/Y', strtotime($endDate))]);
            fputcsv($file, ['Total Transaksi: ' . $transactions->count()]);
            fputcsv($file, ['Total Pendapatan: Rp ' . number_format($transactions->sum('total'), 0, ',', '.')]);
            fputcsv($file, []);
            
            // Column headers
            fputcsv($file, [
                'No',
                'Nomor Transaksi',
                'Tanggal',
                'Waktu',
                'Metode Pembayaran',
                'Jumlah Item',
                'Total',
                'Kasir'
            ]);
            
            // Data rows
            foreach ($transactions as $index => $transaction) {
                fputcsv($file, [
                    $index + 1,
                    $transaction->transaction_number,
                    $transaction->created_at->format('d/m/Y'),
                    $transaction->created_at->format('H:i'),
                    $transaction->payment_method == 'cash' ? 'Cash' : 'QRIS',
                    count($transaction->items),
                    $transaction->total,
                    $transaction->user ? $transaction->user->name : '-'
                ]);
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }
    
    private function exportPDF($transactions, $startDate, $endDate)
    {
        // For now, return a simple text response
        // You can integrate libraries like DomPDF or TCPDF later
        $content = "LAPORAN KEUANGAN\n\n";
        $content .= "Periode: " . date('d/m/Y', strtotime($startDate)) . " - " . date('d/m/Y', strtotime($endDate)) . "\n";
        $content .= "Total Transaksi: " . $transactions->count() . "\n";
        $content .= "Total Pendapatan: Rp " . number_format($transactions->sum('total'), 0, ',', '.') . "\n\n";
        
        foreach ($transactions as $index => $transaction) {
            $content .= ($index + 1) . ". " . $transaction->transaction_number . "\n";
            $content .= "   Tanggal: " . $transaction->created_at->format('d/m/Y H:i') . "\n";
            $content .= "   Pembayaran: " . ($transaction->payment_method == 'cash' ? 'Cash' : 'QRIS') . "\n";
            $content .= "   Total: Rp " . number_format($transaction->total, 0, ',', '.') . "\n\n";
        }
        
        return response($content)
            ->header('Content-Type', 'text/plain')
            ->header('Content-Disposition', 'attachment; filename="laporan-keuangan-' . now()->format('Y-m-d-His') . '.txt"');
    }
    
    private function exportJSON($transactions, $startDate, $endDate)
    {
        $data = [
            'report' => [
                'period' => [
                    'start' => date('Y-m-d', strtotime($startDate)),
                    'end' => date('Y-m-d', strtotime($endDate))
                ],
                'summary' => [
                    'total_transactions' => $transactions->count(),
                    'total_revenue' => $transactions->sum('total'),
                    'cash_total' => $transactions->where('payment_method', 'cash')->sum('total'),
                    'qris_total' => $transactions->where('payment_method', 'qris')->sum('total')
                ],
                'transactions' => $transactions->map(function($transaction) {
                    return [
                        'transaction_number' => $transaction->transaction_number,
                        'date' => $transaction->created_at->format('Y-m-d'),
                        'time' => $transaction->created_at->format('H:i:s'),
                        'payment_method' => $transaction->payment_method,
                        'total' => $transaction->total,
                        'items' => $transaction->items,
                        'cashier' => $transaction->user ? $transaction->user->name : null
                    ];
                })
            ]
        ];
        
        return response()->json($data)
            ->header('Content-Disposition', 'attachment; filename="laporan-keuangan-' . now()->format('Y-m-d-His') . '.json"');
    }
}


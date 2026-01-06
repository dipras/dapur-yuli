<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
}

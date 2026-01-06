<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckoutController extends Controller
{
    public function index(Request $request) {
        $foodTotal = Product::where("category", "food")->count();
        $drinkTotal = Product::where("category", "drink")->count();
        
        $type = $request->query('type');
        
        if ($type && in_array($type, ['food', 'drink'])) {
            $products = Product::where('category', $type)->get();
        } else {
            $products = Product::all();
            $type = "all";
        }
        
        return view("checkout/index", compact("foodTotal", "drinkTotal", "products", "type"));
    }
    
    public function summary(Request $request) {
        $products = $request->input('products', []);
        
        // Filter products with quantity > 0
        $items = [];
        $totalPrice = 0;
        $totalItems = 0;
        
        foreach ($products as $productId => $quantity) {
            if ($quantity > 0) {
                $product = Product::find($productId);
                if ($product) {
                    $subtotal = $product->price * $quantity;
                    $items[] = [
                        'product_id' => $productId,
                        'name' => $product->name,
                        'quantity' => $quantity,
                        'price' => $product->price,
                        'subtotal' => $subtotal
                    ];
                    $totalPrice += $subtotal;
                    $totalItems += $quantity;
                }
            }
        }
        
        // Redirect back if no items
        if (empty($items)) {
            return redirect('/')->with('error', 'Silakan pilih produk terlebih dahulu');
        }
        
        // Generate transaction number
        $transactionNumber = 'TRX' . date('YmdHis');
        $date = date('d/m/Y');
        $time = date('H.i') . ' WIB';
        
        return view('checkout/summary', compact('items', 'totalPrice', 'totalItems', 'transactionNumber', 'date', 'time'));
    }
    
    public function processPayment(Request $request) {
        $paymentMethod = $request->input('payment_method');
        $items = $request->input('items', []);
        $total = $request->input('total');
        $transactionNumber = $request->input('transaction_number');
        
        // If payment method is QRIS, show QR code page
        if ($paymentMethod === 'qris') {
            return view('checkout/qris', compact('transactionNumber', 'total', 'items'));
        }
        
        // If payment method is cash, save transaction and go to success page
        if ($paymentMethod === 'cash') {
            // Save transaction
            Transaction::create([
                'transaction_number' => $transactionNumber,
                'payment_method' => $paymentMethod,
                'total' => $total,
                'items' => $items,
                'user_id' => Auth::id()
            ]);
            
            $date = date('d/m/Y');
            $time = date('H.i') . ' WIB';
            
            return view('checkout/success', [
                'transactionNumber' => $transactionNumber,
                'paymentMethod' => 'cash',
                'total' => $total,
                'date' => $date,
                'time' => $time
            ]);
        }
        
        return redirect('/');
    }
    
    public function success(Request $request) {
        $transactionNumber = $request->input('transaction_number');
        $paymentMethod = $request->input('payment_method');
        $total = $request->input('total');
        $items = $request->input('items', []);
        
        // Save transaction if from QRIS payment
        if ($paymentMethod === 'qris') {
            Transaction::create([
                'transaction_number' => $transactionNumber,
                'payment_method' => $paymentMethod,
                'total' => $total,
                'items' => $items,
                'user_id' => Auth::id()
            ]);
        }
        
        $date = date('d/m/Y');
        $time = date('H.i') . ' WIB';
        
        return view('checkout/success', compact('transactionNumber', 'paymentMethod', 'total', 'date', 'time'));
    }
}

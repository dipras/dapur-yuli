<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request) {
        $type = $request->query('type');
        $search = $request->query('search');
        
        $query = Product::query();
        
        // Filter by type
        if ($type && in_array($type, ['food', 'drink'])) {
            $query->where('category', $type);
        }
        
        // Filter by search
        if ($search) {
            $query->where('product_name', 'like', '%' . $search . '%');
        }
        
        $products = $query->get();
        
        $allTotal = Product::count();
        $foodTotal = Product::where('category', 'food')->count();
        $drinkTotal = Product::where('category', 'drink')->count();
        
        // Alternative flow: Data tidak ditemukan saat search
        $notFound = false;
        if ($search && $products->isEmpty()) {
            $notFound = true;
        }
        
        return view('product.index', compact('products', 'allTotal', 'foodTotal', 'drinkTotal', 'type', 'search', 'notFound'));
    }
    
    public function create() {
        return view('product.create');
    }
    
    public function store(Request $request) {
        try {
            $validated = $request->validate([
                'product_name' => 'required|string|max:255',
                'price' => 'required|integer|min:0',
                'category' => 'required|in:food,drink',
                'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
                'stock' => 'required|integer|min:0'
            ]);
            
            // Handle image upload
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                
                if (!$image->isValid()) {
                    return back()->withErrors(['image' => 'File gambar tidak valid'])->withInput();
                }
                
                $imageName = time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '', $image->getClientOriginalName());
                
                // Create directory if not exists
                $directory = public_path('images/products');
                if (!file_exists($directory)) {
                    mkdir($directory, 0755, true);
                }
                
                $image->move($directory, $imageName);
                $validated['image'] = '/images/products/' . $imageName;
            }
            
            Product::create($validated);
            
            return redirect()->route('product.index')->with('success', 'Produk berhasil ditambahkan');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()])->withInput();
        }
    }
    
    public function edit(Product $product) {
        return view('product.edit', compact('product'));
    }
    
    public function update(Request $request, Product $product) {
        try {
            $validated = $request->validate([
                'product_name' => 'required|string|max:255',
                'price' => 'required|integer|min:0',
                'category' => 'required|in:food,drink',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'stock' => 'required|integer|min:0'
            ]);
            
            // Handle image upload
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                
                if (!$image->isValid()) {
                    return back()->withErrors(['image' => 'File gambar tidak valid'])->withInput();
                }
                
                // Delete old image if exists
                if ($product->image && file_exists(public_path($product->image))) {
                    try {
                        unlink(public_path($product->image));
                    } catch (\Exception $e) {
                        // Continue even if old image deletion fails
                    }
                }
                
                $imageName = time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '', $image->getClientOriginalName());
                
                // Create directory if not exists
                $directory = public_path('images/products');
                if (!file_exists($directory)) {
                    mkdir($directory, 0755, true);
                }
                
                $image->move($directory, $imageName);
                $validated['image'] = '/images/products/' . $imageName;
            }
            
            $product->update($validated);
            
            return redirect()->route('product.index')->with('success', 'Produk berhasil diupdate');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()])->withInput();
        }
    }
    
    public function destroy(Product $product) {
        try {
            // Delete image file if exists
            if ($product->image && file_exists(public_path($product->image))) {
                try {
                    unlink(public_path($product->image));
                } catch (\Exception $e) {
                    // Continue even if image deletion fails
                }
            }
            
            $product->delete();
            
            return redirect()->route('product.index')->with('success', 'Produk berhasil dihapus');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }
}

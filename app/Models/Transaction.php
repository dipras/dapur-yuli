<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transaction extends Model
{
    protected $fillable = [
        'transaction_number',
        'payment_method',
        'total',
        'items',
        'user_id'
    ];

    protected $casts = [
        'items' => 'array',
        'total' => 'decimal:2'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get enriched items with full product details
     */
    public function getEnrichedItemsAttribute()
    {
        $enrichedItems = [];
        
        foreach ($this->items as $item) {
            // If item already has name and price, use it
            if (isset($item['name']) && isset($item['price'])) {
                $enrichedItems[] = $item;
            } else {
                // Otherwise, fetch from product table
                $product = Product::find($item['product_id']);
                if ($product) {
                    $enrichedItems[] = [
                        'product_id' => $item['product_id'],
                        'name' => $product->product_name,
                        'quantity' => $item['quantity'],
                        'price' => $product->price,
                        'subtotal' => $product->price * $item['quantity'],
                        'category' => $product->category,
                    ];
                } else {
                    // Product deleted, show minimal info
                    $enrichedItems[] = [
                        'product_id' => $item['product_id'],
                        'name' => 'Produk #' . $item['product_id'] . ' (Dihapus)',
                        'quantity' => $item['quantity'],
                        'price' => 0,
                        'subtotal' => 0,
                    ];
                }
            }
        }
        
        return $enrichedItems;
    }
}

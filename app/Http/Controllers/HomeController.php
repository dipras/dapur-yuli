<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class HomeController extends Controller
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
        
        return view("home", compact("foodTotal", "drinkTotal", "products", "type"));
    }
}

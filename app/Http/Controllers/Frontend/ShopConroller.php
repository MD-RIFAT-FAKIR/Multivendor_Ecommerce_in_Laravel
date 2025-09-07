<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Subcategor;
use App\Models\Brand;
use App\Models\Product;
use App\Models\User;

class ShopConroller extends Controller
{
    public function ShopPage() {

        $products = Product::query();

        if(!empty($_GET['category'])) {
            $slugs = explode(',',$_GET['category']);
            $catIds = Category::select('id')->whereIn('category_slug', $slugs)->pluck('id')->toArray();

            $products = Product::whereIn('category_id', $catIds)->get();
        }else{
            $products = Product::where('status',1)->orderBy('id','DESC')->get();
        }


        $categories = Category::orderBy('category_name','ASC')->get();
        $newProduct = Product::orderBy('id','DESC')->limit(3)->get();

        return view('frontend.product.shop_page', compact('products', 'categories',  'newProduct'));

    }


    
    public function ShopFilter(Request $request) {
    $catUrl = '';

    if (!empty($request->category)) {
        // Remove duplicates & trim spaces
        $categories = array_unique(array_map('trim', $request->category));

        // Build a clean comma-separated string
        $catUrl = implode(',', $categories);
    }

    return redirect()->route('shop.page', ['category' => $catUrl]);
}

    
}

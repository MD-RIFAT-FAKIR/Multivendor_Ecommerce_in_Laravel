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

        }elseif(!empty($_GET['brand'])) {
            $slugs = explode(',',$_GET['brand']);
            $brandIds = Brand::select('id')->whereIn('brand_slug', $slugs)->pluck('id')->toArray();

            $products = Product::whereIn('brand_id', $brandIds)->get();
        }
        else{
            $products = Product::where('status',1)->orderBy('id','DESC')->get();
        }

        //price range
         if(!empty($request->price)) {
            $price = explode('-', $request->price);
            $products = $products->whereBetween('selling_price',$price);
        }


        $categories = Category::orderBy('category_name','ASC')->get();
        $brands = Brand::orderBy('brand_name','ASC')->get();
        $newProduct = Product::orderBy('id','DESC')->limit(3)->get();

        return view('frontend.product.shop_page', compact('products', 'categories',  'newProduct', 'brands'));

    }





    public function ShopFilter(Request $request) {
    $catUrl = '';
    $brandUrl = '';

    if (!empty($request->category)) {
        // Remove duplicates & trim spaces
        $categories = array_unique(array_map('trim', $request->category));

        // Build a clean comma-separated string
        $catUrl = implode(',', $categories);
    }

    if (!empty($request->brand)) {
        // Remove duplicates & trim spaces
        $brand = array_unique(array_map('trim', $request->brand));

        // Build a clean comma-separated string
        $brandUrl = implode(',', $brand);
    }

    // filter for price range
    $priceUrl = '';
    if (!empty($request->price_range)) {
        $priceUrl = $request->price_range;
    }

    return redirect()->route('shop.page', [
        'category' => $catUrl, 
        'brand' => $brandUrl, 
        'price'    => $priceUrl
    ]);
}

    
}

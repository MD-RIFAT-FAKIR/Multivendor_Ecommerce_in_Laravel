<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Subcategor;
use App\Models\Brand;
use App\Models\MultiImg;
use App\Models\Product;
use App\Models\User;

class IndexController extends Controller
{
    public function Index () {
        $skip_category_0 = Category::skip(0)->first();
        $skip_product_0 = Product::where('status',1)->where('category_id',$skip_category_0->id)->orderBy('id','DESC')->limit(5)->get();

        $skip_category_2 = Category::skip(2)->first();
        $skip_product_2 = Product::where('status',1)->where('category_id',$skip_category_2->id)->orderBy('id','DESC')->limit(5)->get();

        $skip_category_5 = Category::skip(5)->first();
        $skip_product_5 = Product::where('status',1)->where('category_id',$skip_category_5->id)->orderBy('id','DESC')->limit(5)->get();

        $hot_deals = Product::where('hot_deals',1)->where('discount_price','!=',NULL)->orderBy('id','DESC')->limit(3)->get();
        $special_offer = Product::where('special_offer',1)->orderBy('id','DESC')->limit(3)->get();

        $recently_added = Product::where('status',1)->orderBy('id','DESC')->limit(3)->get();
        $special_deals = Product::where('special_deals',1)->orderBy('id','DESC')->limit(3)->get();


        return view('frontend.index', compact('skip_category_0','skip_product_0','skip_category_2','skip_product_2','skip_category_5','skip_product_5','hot_deals','special_offer','recently_added','special_deals'));
    }



    //product details page
    public function ProductDetails ($id,$slug) {
        $product = Product::findOrFail($id);

        $size = $product->product_size;
        $product_size = explode(',',$size);

        $color = $product->product_color;
        $product_color = explode(',',$color);

        $multiImg = MultiImg::where('product_id',$id)->get();

        $cat_id = $product->category_id;
        $related_product = Product::where('category_id',$cat_id)->where('id','!=',$id)->orderBy('id','DESC')->limit(4)->get();

        return view('frontend.product.product_details', compact('product','product_size','product_color','multiImg','related_product'));
    }//end 

    //frontend vendor details
    public function VendorDetails($id) {
        $vendor = User::findOrFail($id);
        $vendor_product = Product::where('vendor_id', $id)->get();

        return view('frontend.vendor.vendor_details', compact('vendor','vendor_product'));
    }//end

    //all vendor list
    public function VendorAll() {
        $vendors = User::where('role','vendor')->where('status','active')->orderBy('id','DESC')->get();

        return view('frontend.vendor.vendor_all', compact('vendors'));
    }//end

    //frontend category wise porduct display
    public function CatwiseProduct (Request $request,$id,$slug) {
        $products = Product::where('status',1)->where('category_id',$id)->orderBy('id','DESC')->get();
        $categories = Category::orderBy('category_name','ASC')->get();

        $breadcat = Category::where('id',$id)->first();
        $newProduct = Product::orderBy('id','DESC')->limit(3)->get();

        return view('frontend.product.category_view', compact('products', 'categories', 'breadcat', 'newProduct'));

    }//end

    //frontend subcategory wise porduct display
    public function SubCatwiseProduct (Request $request,$id,$slug) {
        $products = Product::where('status',1)->where('subcategory_id',$id)->orderBy('id','DESC')->get();
        $categories = Category::orderBy('category_name','ASC')->get();

        $breadsubcat = Subcategor::where('id',$id)->first();
        $newProduct = Product::orderBy('id','DESC')->limit(3)->get();

        return view('frontend.product.subcategory_view', compact('products', 'categories', 'breadsubcat', 'newProduct'));

    }//end

    //product quick view modal
    public function productViewAjax ($id) {
        $product = Product::with('category','brand')->findOrFail($id);

        $size = $product->product_size;
        $product_size = explode(',',$size);

        $color = $product->product_color;
        $product_color = explode(',',$color);

        return response()->json(array(
            'product' => $product,
            'size' => $product_size,
            'color' => $product_color
        ));
    }//end product quick view modal



    //////////////Frontend Product search///////////////////
    public function SearchProduct(Request $request) {
        $search_item = $request->search;

        $request->validate([
            'search' => 'required',
        ]);

        $products = Product::where('product_name', 'like', '%'.$search_item.'%')->get();
        $categories = Category::orderBy('category_name','ASC')->get();
        $newProduct = Product::orderBy('id','DESC')->limit(3)->get();

        return view('frontend.product.search_product', compact('products', 'search_item', 'categories', 'newProduct'));
    }//end

    //search recommendation using ajax
    public function SearchRecomnend(Request $request) {

        $request->validate([
            'search' => 'required',
        ]);

        $search_item = $request->search;

        $products = Product::where('product_name', 'like', '%'.$search_item.'%')->select('id', 'product_name', 'product_slug', 'product_thambnail', 'selling_price')->limit(6)->get();

        return view('frontend.product.search_recommendation', compact('products'));
    }//end search recommendation using ajax


    //////////////end Frontend Product search///////////////
}


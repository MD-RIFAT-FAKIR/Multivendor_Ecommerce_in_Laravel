<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Wishlist;
use Carbon\Carbon;

class WishlistConrtoller extends Controller
{
    //product add to wishlist
    public function addToWishlist(Request $request, $product_id) {
        if(Auth::check()) {
            $exists = Wishlist::where('user_id',Auth::id())->where('product_id',$product_id)->first();
            if(!$exists) {
                Wishlist::insert([
                    'user_id' => Auth::id(),
                    'product_id' => $product_id,
                    'created_at' => Carbon::now()
                ]);
                return response()->json(['success' => 'Successfully Added On Your Wishlist']);
            }else{
                return response()->json(['error' => 'Product Already On Your Wishlist']);
            }
        }else{
            return response()->json(['error' => 'At First Login Your Acount']);
        }
    }//end product add to wishlist

    //wishlist page
    public function AllWishlist() {
        return view('frontend.wishlist.view_wishlist');
    }//end

    //load wishlist product data
    public function GetWishlistProduct() {
        $wishlist = Wishlist::with('product')->where('user_id', Auth::id())->latest()->get();
        return response()->json([
            'wishlist' => $wishlist,
            'wishlistQty' => $wishlist->count(),
        ]);
    }//end

    //remove wishlist product 
    public function WishlistProductRemove($id) {
        Wishlist::where('user_id', Auth::id())->where('id',$id)->delete();
        return response()->json(['success' => 'Successfully Product Remove']);
    }
}

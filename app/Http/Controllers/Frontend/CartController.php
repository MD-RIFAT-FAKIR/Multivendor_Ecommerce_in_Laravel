<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Coupon;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;

class CartController extends Controller
{
    //product add to cart 
    public function addToCart(Request $request, $id) {
        $product = Product::findOrFail($id);

        if($product->discount_price == Null) {
            Cart::add([
                'id' => $id,
                'name' => $request->product_name,
                'qty' => $request->quantity,
                'price' => $product->selling_price,
                'weight' => 1,
                'options' => [
                    'image' => $product->product_thambnail,
                    'color' => $request->color,
                    'size' => $request->size,
                ],
            ]);
            return response()->json(['success' => 'Successfully Added On Your Cart']);
        }else{
            Cart::add([
                'id' => $id,
                'name' => $request->product_name,
                'qty' => $request->quantity,
                'price' => $product->discount_price,
                'weight' => 1,
                'options' => [
                    'image' => $product->product_thambnail,
                    'color' => $request->color,
                    'size' => $request->size,
                ],
            ]);
            return response()->json(['success' => 'Successfully Added On Your Cart']);
        }
    }//end add to cart

    //product add to cart from details page
    public function AddToCartDetails(Request $request, $id) {
        $product = Product::findOrFail($id);

        if($product->discount_price == Null) {
            Cart::add([
                'id' => $id,
                'name' => $request->product_name,
                'qty' => $request->quantity,
                'price' => $product->selling_price,
                'weight' => 1,
                'options' => [
                    'image' => $product->product_thambnail,
                    'color' => $request->color,
                    'size' => $request->size,
                ],
            ]);
            return response()->json(['success' => 'Successfully Added On Your Cart']);
        }else{
            Cart::add([
                'id' => $id,
                'name' => $request->product_name,
                'qty' => $request->quantity,
                'price' => $product->discount_price,
                'weight' => 1,
                'options' => [
                    'image' => $product->product_thambnail,
                    'color' => $request->color,
                    'size' => $request->size,
                ],
            ]);
            return response()->json(['success' => 'Successfully Added On Your Cart']);
        }
    }//end add to cart from details page

    //get data from added cart
    public function AddMiniCart() {
        $carts = Cart::content();
        $cartQty = Cart::count();
        $cartTotal = Cart::total();

        return response()->json(array(
            'carts' => $carts,
            'cartQty' => $cartQty,
            'cartTotal' => $cartTotal,
        ));

    }//end

    //remove product mini cart
    public function RemoveMiniCart($rowId) {
        Cart::remove($rowId);

        return response()->json(['success' => 'Product Remove From Cart']);
    }//end

    //mycart 
    public function MyCart() {
        return view('frontend.mycart.view_mycart');
    }

    //load my cart data 
    public function GetMyCart() {
        $carts = Cart::content();
        $cartQty = Cart::count();
        $cartTotal = Cart::total();

        return response()->json(array(
            'carts' => $carts,
            'cartQty' => $cartQty,
            'cartTotal' => $cartTotal,
        ));
    }//end

    //Remove cart
    public function CartRemove($rowId) {
        Cart::remove($rowId);

        return response()->json(['success' => 'Product Remove From Cart']);
    }//end

    // decrement cart quantity
    public function DecrementCart($rowId) {
        $row = Cart::get($rowId);
        Cart::update($rowId, $row->qty - 1);

        return response()->json('Decrement');
    }//end

    // decrement cart quantity
    public function IncrementCart($rowId) {
        $row = Cart::get($rowId);
        Cart::update($rowId, $row->qty + 1);

        return response()->json('Increment');
    }//end


    //frontend coupon apply
       public function CouponApply(Request $request){

        $coupon = Coupon::where('coupon_name',$request->coupon_name)->where('coupon_validity','>=',Carbon::now()->format('Y-m-d'))->first();

        if ($coupon) {
            Session::put('coupon',[
                'coupon_name' => $coupon->coupon_name, 
                'coupon_discount' => $coupon->coupon_discount, 
                'discount_amount' => round(Cart::total() * $coupon->coupon_discount/100), 
                'total_amount' => round(Cart::total() - Cart::total() * $coupon->coupon_discount/100 )
            ]);

            return response()->json(array(
                'validity' => true,                
                'success' => 'Coupon Applied Successfully'

            ));

        } else{
            return response()->json(['error' => 'Invalid Coupon']);
        }

    }//

    

}

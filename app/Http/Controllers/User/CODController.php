<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;
use Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\OrderMail;

class CODController extends Controller
{
    //
    public function CashOrder(Request $request) {
        
        if(Session::has('coupon')){
          $total_amount = Session::get('coupon')['total_amount'];
        }else{
          $total_amount = round(Cart::total());
        }


        //store order bills related all data to database
        $order_id = Order::insertGetId([
          'user_id' => Auth::id(),
          'division_id' => $request->division_id,
          'district_id' => $request->district_id,
          'state_id' => $request->state_id,
          'name' => $request->name,
          'email' => $request->email,
          'phone' => $request->phone,
          'adress' => $request->address,
          'post_code' => $request->post_code,
          'notes' => $request->notes,

          'payment_type' => 'Cash on Delivary',
          'payment_method' => 'Cash on Delivary',
          'currency' => 'USD',
          'amount' => $total_amount,

          'invoice_no' => 'EOS'.mt_rand(10000000,99999999),
          'order_date' => Carbon::now()->format('d F Y'),
          'order_month' => Carbon::now()->format('F'),
          'order_year' => Carbon::now()->format('Y'),
          'status' => 'Pending',
          'created_at' => Carbon::now(),
        ]);

        //order confirmation email
        $invoice = Order::findOrFail($order_id);

        Mail::to($request->email)->send(new OrderMail([
            'invoice_no' => $invoice->invoice_no,
            'amount' => $total_amount,
            'name' => $invoice->name,
            'email' => $invoice->email,
        ]));
        //end order confirmation email

        //store ordered product related all data to database
        $carts = Cart::content();
        foreach ($carts as $cart) {
          OrderItem::insert([
          'order_id' => $order_id,
          'product_id' => $cart->id,
          'vendor_id' => $cart->options->vendor_id,
          'color' => $cart->options->color,
          'size' => $cart->options->size,
          'qty' => $cart->qty,
          'price' => $cart->price,
          'created_at' => Carbon::now(),
          ]);
        }

        //after palace order coupon will destroy
        if(Session::has('coupon')) {
          Session::forget('coupon');
        }

        //after palace order all cart will remove
        Cart::destroy();

        $notification = array(
            'message' => 'Your order place succesfully',
            'alert-type' => 'success'
        );

        return redirect()->route('dashboard')->with($notification);
    }
}

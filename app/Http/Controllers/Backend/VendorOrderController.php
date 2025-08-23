<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use Carbon\Carbon;
use Auth;

class VendorOrderController extends Controller
{
    //
    public function VendorOrder() {
        $id = Auth::user()->id;
        $OrderItem = OrderItem::with('order')->where('vendor_id', $id)->orderBy('id','desc')->get();

        return view('vendor.backend.orders.orders_pending', compact('OrderItem'));

    }
    //vendor return order
    public function VendorReturnOrder() {
        $id = Auth::user()->id;
        $OrderItem = OrderItem::with('order')->where('vendor_id', $id)->orderBy('id','desc')->get();

        return view('vendor.backend.orders.return_orders', compact('OrderItem'));

    }


    //vendor complete return order
    public function VendorCompleteReturnOrder() {
        $id = Auth::user()->id;
        $OrderItem = OrderItem::with('order')->where('vendor_id', $id)->orderBy('id','desc')->get();

        return view('vendor.backend.orders.complete_return_orders', compact('OrderItem'));

    }

    public function VendorOrderDetails($order_id) {
        $order =  Order::with('division','district','state','user')->where('id', '=', $order_id)->first();

        $orderItem = OrderItem::with('product')->where('order_id', '=', $order_id)->orderBy('id','desc')->get();

        return view('vendor.backend.orders.orders_details', compact('order','orderItem'));
    }
}

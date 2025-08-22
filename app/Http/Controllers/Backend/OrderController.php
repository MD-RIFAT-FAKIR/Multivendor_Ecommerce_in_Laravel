<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;
use Auth;

class OrderController extends Controller
{
    //
    public function PendingOrder() {
        $orders = Order::where('status', 'pending')->orderBy('id','desc')->get();
        return view('backend.orders.pending_orders', compact('orders'));
    }

    public function OrderDetails($order_id) {
        $order =  Order::with('division','district','state','user')->where('id', '=', $order_id)->first();

        $orderItem = OrderItem::with('product')->where('order_id', '=', $order_id)->orderBy('id','desc')->get();

        return view('backend.orders.order_details', compact('order','orderItem'));
    }

    //admin confirmed order status
    public function ConfirmedOrder() {
        $orders = Order::where('status', 'confirm')->orderBy('id','desc')->get();
        return view('backend.orders.confirmed_orders', compact('orders'));
    }
}

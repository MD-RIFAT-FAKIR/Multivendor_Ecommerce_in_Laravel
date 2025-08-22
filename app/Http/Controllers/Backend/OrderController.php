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
    //admin confirmed order status
    public function ProcessingOrder() {
        $orders = Order::where('status', 'processing')->orderBy('id','desc')->get();
        return view('backend.orders.processing_orders', compact('orders'));
    }
    //admin Delivered Order  status
    public function DeliveredOrder() {
        $orders = Order::where('status', 'delivered')->orderBy('id','desc')->get();
        return view('backend.orders.delivered_orders', compact('orders'));
    }

    //order status pending to confirm
    public function PendingToConfirm($order_id) {
        Order::findOrFail($order_id)->update([
            'status' => 'confirm',
        ]);

        $notification = array(
            'message' => 'Order Confirmed Successfully',
            'alert-type' => 'success',
        );

        return redirect()->route('confirmed.orders')->with($notification);
    }

    //order status confirm to Processing
    public function ConfirmToProcessing($order_id) {
        Order::findOrFail($order_id)->update([
            'status' => 'processing',
        ]);

        $notification = array(
            'message' => 'Order Processing Successfully',
            'alert-type' => 'success',
        );

        return redirect()->route('processing.orders')->with($notification);
    }
}

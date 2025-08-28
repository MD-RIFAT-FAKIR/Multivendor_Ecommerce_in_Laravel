<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;
use Auth;
use Barryvdh\DomPDF\Facade\Pdf;
use DB;

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
    }//end order status confirm to Processing


    //order status processing to delivered
    public function ProcessingToDelivered($order_id) {
        
        //update products total quantity after product delivary
        $productItem = OrderItem::where('order_id', $order_id)->get();

        foreach($productItem as $item) {
            Product::where('id', $item->product_id)
            ->update([
                'product_qty' => DB::raw('product_qty-'.$item->qty),
            ]);
        }
        //update products total quantity after product delivary

        Order::findOrFail($order_id)->update([
            'status' => 'delivered',
        ]);

        $notification = array(
            'message' => 'Order Delivered Successfully',
            'alert-type' => 'success',
        );

        return redirect()->route('delivered.orders')->with($notification);
    }

    //admin invoice download
     public function AdminInvoiceDownload($order_id) {
        $order =  Order::with('division','district','state','user')->where('id', '=', $order_id)->first();

        $orderItem = OrderItem::with('product')->where('order_id', '=', $order_id)->orderBy('id','desc')->get();

        $pdf = Pdf::loadView('backend.orders.admin_order_invoice', compact('order','orderItem'))->setPaper('a4')
        ->setOption([
            'tempDir' => public_path(), 
            'chroot' => public_path(),
        ]);

        return $pdf->download('invoice.pdf');

    }//end admin invoice download
}

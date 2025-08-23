<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;

class ReturnController extends Controller
{
    //
    public function ReturnRequest() {
        $orders = Order::where('return_order', '=', 1)->orderBy('id', 'desc')->get();

        return view('backend.return_orders.return_request', compact('orders'));
    }

    //approved order return request
    public function ReturnRequestApproved($order_id) {
        Order::findOrFail($order_id)->update(['return_order' => 2 ]);

        $notification = array(
            'message' => 'Roder Approved Successfully',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);
    }
}

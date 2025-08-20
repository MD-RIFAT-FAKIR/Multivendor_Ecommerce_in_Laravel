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
        $OrderItem = OrderItem::where('vendor_id', $id)->orderBy('id','desc')->get();

        return view('vendor.backen.orders.pending_orders', compact('OrderItem'));

    }
}

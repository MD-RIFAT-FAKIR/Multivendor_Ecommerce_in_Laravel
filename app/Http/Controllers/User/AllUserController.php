<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use App\Models\User;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\Hash;
use Barryvdh\DomPDF\Facade\Pdf;


class AllUserController extends Controller
{
    //
    public function UserAcount() {
        $id = Auth::user()->id;
        $UserData = User::find($id);

        return view('frontend.dashboard.acount_details', compact('UserData'));
    }

    //change password page
    public function UserChangePassword() {
        return view('frontend.dashboard.user_change_password');
    }
    //order page
    public function UserOrderPage() {
        $id = Auth::user()->id;
        $orders = Order::where('user_id','=', $id)->orderBy('id', 'desc')->get();
        return view('frontend.dashboard.user_order_page', compact('orders'));
    }

    //view user order details
    public function UserOrderDetails($order_id) {
        $order =  Order::with('division','district','state','user')->where('id', '=', $order_id)->where('user_id', '=', Auth::id())->first();

        $orderItem = OrderItem::with('product')->where('order_id', '=', $order_id)->orderBy('id','desc')->get();

        return view('frontend.order.order_details', compact('order','orderItem'));
    }

    public function UserOrderInvoice($order_id) {
        $order =  Order::with('division','district','state','user')->where('id', '=', $order_id)->where('user_id', '=', Auth::id())->first();

        $orderItem = OrderItem::with('product')->where('order_id', '=', $order_id)->orderBy('id','desc')->get();

        $pdf = Pdf::loadView('frontend.order.order_invoice', compact('order','orderItem'))   ->setPaper('a4')
        ->setOption([
            'tempDir' => public_path(), 
            'chroot' => public_path(),
        ]);
        return $pdf->download('invoice.pdf');


    }
}

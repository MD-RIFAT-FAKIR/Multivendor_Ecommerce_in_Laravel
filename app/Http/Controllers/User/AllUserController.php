<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use App\Models\User;
use Illuminate\Support\Facades\Hash;


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
        return view('frontend.dashboard.user_order_page');
    }
}

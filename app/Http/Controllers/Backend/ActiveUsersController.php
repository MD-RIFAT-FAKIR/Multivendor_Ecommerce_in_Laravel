<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class ActiveUsersController extends Controller
{
    //all users data
    public function AllUsers() {
        $users = User::where('role', '=', 'user')->latest()->get();

        return view('backend.users.user_all_data', compact('users'));
    }

    //all vendors data
    public function AllVendors() {
        $vendors = User::where('role', '=', 'vendor')->latest()->get();

        return view('backend.users.vendor_all_data', compact('vendors'));
    }

}

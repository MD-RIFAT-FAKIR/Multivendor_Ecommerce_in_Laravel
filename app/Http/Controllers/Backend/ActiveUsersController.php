<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class ActiveUsersController extends Controller
{
    //
    public function AllUsers() {
        $users = User::where('role', '=', 'user')->latest()->get();

        return view('backend.users.user_all_data', compact('users'));
    }

}

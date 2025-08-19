<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    //User dashboard
    public function UserDashboard() {
        $id = Auth::user()->id;
        $UserData = User::find($id);

        return view('index', compact('UserData'));
    }//end

    //user profile store
    public function UserProfileStore(Request $request) {
        $id = Auth::user()->id;
        $data = User::find($id);

        $data->name = $request->name;
        $data->username = $request->username;
        $data->email = $request->email;
        $data->phone = $request->phone;
        $data->address = $request->address;
        
        if($request->file('photo')) {
            $file = $request->file('photo');
            @unlink(public_path('upload/user_images'.$data->photo));
            $fileName = date('YmdHi').$file->getClientOriginalName();
            $file->move(public_path('upload/user_images'),$fileName);
            $data['photo'] = $fileName;
        }

        $data->save();

        $notification = array (
            'message' => 'Profile updated successfully',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);
    }//end

    //user profile logout
    public function UserProfileLogout (Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        $notification = array (
            'message' => 'User Logout successfully',
            'alert-type' => 'success'
        );

        return redirect('/login')->with($notification);
    }//end

    //user update password
    public function UserUpdatePassword (Request $request) {
        $request->validate([
            'old_password' => 'required',
            'new_password' => 'required|confirmed'
        ]);

        //match the current password
        if(!Hash::check($request->old_password, auth::user()->password)){
            return back()->with('error', 'Old Password Does Not Matched');
        }

        //update password
        User::where('id', auth::user()->id)->update([
            'password' => Hash::make($request->new_password)
        ]);

        return back()->with('status', 'Password Changed Successfully');
    }
}

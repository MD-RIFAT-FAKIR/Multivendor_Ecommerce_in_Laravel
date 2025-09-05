<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Notification;
use App\Notifications\VendorRegisterComplete;


class VendorController extends Controller
{
    //
    public function VendorDashboard() {
        return view('vendor.index');
    }
    //vendor login
    public function VendorLogin() {
        return view('vendor.vendor_login');
    }
    //vendor logout
    public function VendorDestroy(Request $request): RedirectResponse
        {
            Auth::guard('web')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect('/vendor/login');
        }//end

    //vendor profile
    public function VendorProfie() {
        $id = Auth::user()->id;
        $vendorData = User::find($id);

        return view('vendor.vendor_profile_view', compact('vendorData'));
    }//end

    //vendor profile store
    public function VendorProfileStore(Request $request) {
        $id = Auth::user()->id;
        $data = User::find($id);

        $data->username = $request->username;
        $data->email = $request->email;
        $data->phone = $request->phone;
        $data->address = $request->address;
        $data->vendor_join = $request->vendor_join;
        $data->vendor_short_info = $request->vendor_short_info;
        

        if($request->file('photo')) {
            $file = $request->file('photo');
            @unlink(public_path('/upload/vendor_images/'.$data->photo));
            $fileName = date('YmdHi').$file->getClientOriginalName();
            $file->move(public_path('upload/vendor_images'), $fileName);
            $data['photo'] = $fileName;
        }
        $data->save();
        $notification = array(
            'message' => 'Profile Updated Successfully',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);
        
    }//end

    //vendor change password
    public function VendorChangePassword() {
        return view('vendor.vendor_change_password');
    }//end

    //vendor update password
    public function VendorUpdatePassword(Request $request) {
        $request->validate([
            'old_password' => 'required',
            'new_password' => 'required | confirmed'
        ]);

        //match the old password 
        if(!Hash::check($request->old_password, auth::user()->password)){
            return back()->with("error", " Old Password Does not Matched");
        }

        //Update new password
        User::where('id', Auth::user()->id)->update([
            'password' => Hash::make($request->new_password)
        ]);

        return back()->with("status", "Password Changed Successfully");

    }//end

    //become vendor
    public function BecomeVendor() {
        return view('auth.become_vendor');
    }//end

    //vendor register
    public function VendorRegister(Request $request): RedirectResponse
    {

        $adminUser = User::where('role', 'admin')->get();

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed'],
        ]);

        $user = User::insert([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'phone' => $request->phone,
            'vendor_join' => $request->vendor_join,
            'password' => Hash::make($request->password),
            'role'=> 'vendor',
            'status'=> 'inactive',
        ]);


        //after vendor register admin will notify notifaication message
        Notification::send($adminUser, new VendorRegisterComplete($request->name));


        $notification = array(
            'message' => 'Vendor Registered Successfully',
            'alert-type' => 'success'
        );

        return redirect()->route('vendor.login')->with($notification);

        
    }
}

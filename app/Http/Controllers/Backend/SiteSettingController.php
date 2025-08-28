<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SiteSetting;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class SiteSettingController extends Controller
{
    //site settings
    public function SiteSettings() {
        $setting = SiteSetting::find(1);

        return view('backend.setting.setting_update', compact('setting'));
    }

    //update site settings
    public function UpdateSiteSettings(Request $request) {

        $id = $request->id;

        if($request->file('logo')){

            $manager = new ImageManager(new Driver());
            $name_gen = hexdec(uniqid()).'.'.$request->file('logo')->getClientOriginalExtension();

            $img = $manager->read($request->file('logo'));
            $img = $img->resize(180,56);
            
            $img->toJpeg()->save(base_path('public/upload/logo/'.$name_gen));

            $save_url = 'upload/logo/'.$name_gen;


            SiteSetting::findOrFail($id)->update([
                'support_phone' => $request->support_phone,
                'phone_one' => $request->phone_one,
                'email' => $request->email,
                'company_address' => $request->company_address,
                'facebook' => $request->facebook,
                'twitter' => $request->twitter,
                'youtube' => $request->youtube,
                'copyright' => $request->copyright, 
                'logo' => $save_url,
            ]);

             $notification = array(
                'message' => 'Site Setting Updated with image Successfully',
                'alert-type' => 'success'
            );

            return redirect()->back()->with($notification);

        }else{

            SiteSetting::findOrFail($id)->update([
                'support_phone' => $request->support_phone,
                'phone_one' => $request->phone_one,
                'email' => $request->email,
                'company_address' => $request->company_address,
                'facebook' => $request->facebook,
                'twitter' => $request->twitter,
                'youtube' => $request->youtube,
                'copyright' => $request->copyright, 
            ]);

            $notification = array(
                'message' => 'Site Setting Updated without image Successfully',
                'alert-type' => 'success'
            );

            return redirect()->back()->with($notification);

        }
        
    }
}

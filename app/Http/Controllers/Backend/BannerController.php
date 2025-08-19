<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use App\Models\Banner;

class BannerController extends Controller
{
     //all banner
     public function AllBanner() {
        $banner = Banner::latest()->get();
        return view('backend.banner.banner_all', compact('banner'));
    }//end

    //add banner
    public function AddBanner() {
        return view('backend.banner.banner_add');
    }

     //store banner
     public function StoreBanner(Request $request) {

        if($request->file('banner_image')) {
            $manager = new ImageManager(new Driver());
            $name_gen = hexdec(uniqid()).'.'.$request->file('banner_image')->getClientOriginalExtension();
            $img = $manager->read($request->file('banner_image'));
            $img = $img->resize(768,450);

            $img->toJpeg(80)->save(base_path('public/upload/banner/'.$name_gen));
            $save_url = 'upload/banner/'.$name_gen;

            Banner::insert([
                'banner_title' => $request->banner_title,
                'banner_url' => $request->banner_url,
                'banner_image' => $save_url
            ]);
        }//end if

        $notification = array (
                'message' => 'Banner Inserted Successfully',
                'alert-type' => 'success'
        );
        
        return redirect()->route('all.banner')->with($notification);

    }//end

    // edit banner
    public function EditBanner($id) {
        $banner = Banner::findorFail($id);

        return view('backend.banner.banner_edit', compact('banner'));
    }//end

     //update banner
     public function UpdateBanner(Request $request) {
        $id = $request->id;
        $old_img = $request->old_img;

        if($request->file('banner_image')) {
            $manager = new ImageManager(new Driver());
            $name_gen = hexdec(uniqid()).'.'.$request->file('banner_image')->getClientOriginalExtension();
            $img = $manager->read($request->file('banner_image'));
            $img = $img->resize(768,450);

            //unlink old_image
            if(file_exists($old_img)) {
                unlink($old_img);
            }

            $img = $img->toJpeg(80)->save(base_path('public/upload/banner/'.$name_gen));
            $save_url = 'upload/banner/'.$name_gen;

            Banner::findorFail($id)->update([
                'banner_title' => $request->banner_title,
                'banner_url' => $request->banner_url,
                'banner_image' => $save_url
            ]);
            
            $notification = array(
                'message' => 'Banner Edited With Image Successfully',
                'alert-type' => 'success'
            );

            return redirect()->route('all.banner')->with($notification);

        //end if
        } else {
            Banner::findorFail($id)->update([
                'banner_title' => $request->banner_title,
                'banner_url' => $request->banner_url
            ]);
            
            $notification = array(
                'message' => 'Banner Edited Without Image Successfully',
                'alert-type' => 'success'
            );

            return redirect()->route('all.banner')->with($notification);
        }//end else
    }//end banner update

     //delete banner
     public function DeleteBanner($id) {
        $banner = Banner::findOrFail($id);
        $img = $banner->banner_image;
        unlink($img);

        Banner::findOrFail($id)->delete();

        $notification = array(
            'message' => 'Banner Deleted Successfully',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);
    }//end delete banner
}

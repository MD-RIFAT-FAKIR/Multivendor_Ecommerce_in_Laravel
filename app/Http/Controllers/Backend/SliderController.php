<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use App\Models\Slider;

class SliderController extends Controller
{
     //all slider
     public function AllSlider() {
        $sliders = Slider::latest()->get();
        return view('backend.slider.slider_all', compact('sliders'));
    }//end

    //add slider
    public function AddSlider() {
        return view('backend.slider.slider_add');
    }

     //store slider
     public function StoreSlider(Request $request) {

        if($request->file('slider_image')) {
            $manager = new ImageManager(new Driver());
            $name_gen = hexdec(uniqid()).'.'.$request->file('slider_image')->getClientOriginalExtension();
            $img = $manager->read($request->file('slider_image'));
            $img = $img->resize(2376,807);

            $img->toJpeg(80)->save(base_path('public/upload/slider/'.$name_gen));
            $save_url = 'upload/slider/'.$name_gen;

            Slider::insert([
                'slider_title' => $request->slider_title,
                'short_title' => $request->short_title,
                'slider_image' => $save_url
            ]);
        }//end if

        $notification = array (
                'message' => 'Slider Inserted Successfully',
                'alert-type' => 'success'
        );
        
        return redirect()->route('all.slider')->with($notification);

    }//end

    // edit slider
    public function EditSlider($id) {
        $sliders = Slider::findorFail($id);

        return view('backend.slider.slider_edit', compact('sliders'));
    }//end

    //update slider
    public function UpdateSlider(Request $request) {
        $id = $request->id;
        $old_img = $request->old_img;

        if($request->file('slider_image')) {
            $manager = new ImageManager(new Driver());
            $name_gen = hexdec(uniqid()).'.'.$request->file('slider_image')->getClientOriginalExtension();
            $img = $manager->read($request->file('slider_image'));
            $img = $img->resize(2376,807);

            //unlink old_image
            if(file_exists($old_img)) {
                unlink($old_img);
            }

            $img = $img->toJpeg(80)->save(base_path('public/upload/slider/'.$name_gen));
            $save_url = 'upload/slider/'.$name_gen;

            Slider::findorFail($id)->update([
                'slider_title' => $request->slider_title,
                'short_title' => $request->short_title,
                'slider_image' => $save_url
            ]);
            
            $notification = array(
                'message' => 'Slider Edited With Image Successfully',
                'alert-type' => 'success'
            );

            return redirect()->route('all.slider')->with($notification);

        //end if
        } else {
            Slider::findorFail($id)->update([
                'slider_title' => $request->slider_title,
                'short_title' => $request->short_title
            ]);
            
            $notification = array(
                'message' => 'Slider Edited Without Image Successfully',
                'alert-type' => 'success'
            );

            return redirect()->route('all.slider')->with($notification);
        }//end else
    }//end slider update

     //delete slider
     public function DeleteSlider($id) {
        $slider = Slider::findOrFail($id);
        $img = $slider->slider_image;
        unlink($img);

        Slider::findOrFail($id)->delete();

        $notification = array(
            'message' => 'Slider Deleted Successfully',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);
    }//end delete slider

}

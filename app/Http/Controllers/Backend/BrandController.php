<?php

namespace App\Http\Controllers\Backend;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Brand;

class BrandController extends Controller
{
    //Backend all brand
    public function AllBrand() {
        $brands = Brand::latest()->get();
        return view('backend.brand.brand_all', compact('brands'));
    }//end

    //Backend add brand
    public function AddBrand() {
        return view('backend.brand.brand_add');
    }//end

    //store brand
    public function StoreBrand(Request $request) {

        if($request->file('brand_image')) {
            $manager = new ImageManager(new Driver());
            $name_gen = hexdec(uniqid()).'.'.$request->file('brand_image')->getClientOriginalExtension();
            $img = $manager->read($request->file('brand_image'));
            $img = $img->resize(300,246);

            $img->toJpeg(80)->save(base_path('public/upload/brand/'.$name_gen));
            $save_url = 'upload/brand/'.$name_gen;

            Brand::insert([
                'brand_name' => $request->brand_name,
                'brand_slug' => strtolower(str_replace('','-',$request->brand_name)),
                'brand_image' => $save_url
            ]);
        }//end if

        $notification = array (
                'message' => 'Brand Inserted Successfully',
                'alert-type' => 'success'
        );
        
        return redirect()->route('all.brand')->with($notification);

    }//end

    // edit brand
    public function EditBrand($id) {
        $brands = Brand::findorFail($id);

        return view('backend.brand.brand_edit', compact('brands'));
    }//end

    //update brand
    public function UpdateBrand(Request $request) {
        $id = $request->id;
        $old_image = $request->old_image;

        if($request->file('brand_image')) {
            $manager = new ImageManager(new Driver());
            $name_gen = hexdec(uniqid()).'.'.$request->file('brand_image')->getClientOriginalExtension();
            $img = $manager->read($request->file('brand_image'));
            $img = $img->resize(300,246);

            //unlink old_image
            if(file_exists($old_image)) {
                unlink($old_image);
            }

            $img = $img->toJpeg(80)->save(base_path('public/upload/brand/'.$name_gen));
            $save_url = 'upload/brand/'.$name_gen;

            Brand::findorFail($id)->update([
                'brand_name' => $request->brand_name,
                'brand_slug' => strtolower(str_ireplace('','-',$request->brand_name)),
                'brand_image' => $save_url
            ]);
            
            $notification = array(
                'message' => 'Brand Edited With Image Successfully',
                'alert-type' => 'success'
            );

            return redirect()->route('all.brand')->with($notification);

        //end if
        } else {
            Brand::findorFail($id)->update([
                'brand_name' => $request->brand_name,
                'brand_slug' => strtolower(str_ireplace('','-',$request->brand_name))
            ]);
            
            $notification = array(
                'message' => 'Brand Edited Without Image Successfully',
                'alert-type' => 'success'
            );

            return redirect()->route('all.brand')->with($notification);
        }//end else
    }//end Brand update

    //delete brand
    public function DeleteBrand($id) {
        $brand = Brand::findOrFail($id);
        $img = $brand->brand_image;
        unlink($img);

        Brand::findOrFail($id)->delete();

        $notification = array(
            'message' => 'Brand Deleted Successfully',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);
    }

}

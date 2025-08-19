<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use App\Models\Category;
use App\Models\Subcategor;
use App\Models\Brand;
use App\Models\MultiImg;
use App\Models\Product;
use App\Models\User;
use Carbon\Carbon;
use Auth;

class VendorProductController extends Controller
{
    //vendor all product
    public function VendorAllProduct() {
        $id = Auth::user()->id;
        $products = Product::where('vendor_id',$id)->latest()->get();
        return view('vendor.backend.product.vendor_product_all', compact('products'));
    }//end

    //vendor add product
    public function VendorAddProduct() {
        $Brands = Brand::latest()->get();
        $Category = Category::latest()->get();
        
        return view('vendor.backend.product.vendor_product_add', compact('Brands','Category'));
    }//end

      //automatically get subcategory data on vendor dashboard when category is selected
      public function VendorGetSubcategory($category_id) {
        $subcat = Subcategor::where('category_id',$category_id)->orderBy('subcategory_name','ASC')->get();

        return json_encode($subcat);
    }//end

    //vendor store product in database
    public function VendorStoreProduct(Request $request) {
        $img = $request->file('product_thambnail');

        $manager = new ImageManager(new Driver());
        $name_gen = hexdec(uniqid()).'.'.$img->getClientOriginalExtension();
        $img = $manager->read($img);
        $img = $img->resize(800,800);

        $img->toJpeg(80)->save(base_path('public/upload/products/thambnails/'.$name_gen));
        $save_url = 'upload/products/thambnails/'.$name_gen;

        $Product_id = Product::insertGetId([
            'brand_id' => $request->brand_id,
            'category_id' => $request->category_id,
            'subcategory_id' => $request->subcategory_id,
            'product_name' => $request->product_name,

            'product_slug' => strtolower(str_replace(' ','-',$request->product_name)),
            'product_code' => $request->product_code,
            'product_qty' => $request->product_qty,
            'product_tags' => $request->product_tags,

            'product_size' => $request->product_size,
            'product_color' => $request->product_color,
            'selling_price' => $request->selling_price,
            'discount_price' => $request->discount_price,
            'short_descp' => $request->short_descp,

            'long_descp' => $request->long_descp,
            'hot_deals' => $request->hot_deals,
            'featured' => $request->featured,
            'special_offer' => $request->special_offer,
            'special_deals' => $request->special_deals,

            'product_thambnail' => $save_url,
            'vendor_id' => Auth::user()->id,
            'status' => 1,
            'created_at' => Carbon::now()
        ]);

        //upload multiple images
        $images = $request->file('multi_img');

        foreach($images as $img) {

            $manager = new ImageManager(new Driver());
            $name_gen = hexdec(uniqid()).'.'.$img->getClientOriginalExtension();
            $img = $manager->read($img);
            $img = $img->resize(800,800);
    
            $img->toJpeg(80)->save(base_path('public/upload/products/multi-img/'.$name_gen));
            $upload_url = 'upload/products/multi-img/'.$name_gen;

            MultiImg::insert([
                'product_id' => $Product_id,
                'photo_name' => $upload_url,
                'created_at' => Carbon::now()
            ]);

        }//end if

        $notification = array (
            'message' => 'Vendor Product Inserted Successfully',
            'alert-type' => 'success'
        );
    
        return redirect()->route('vendor.all.product')->with($notification);
    }//end 

    //vendor edit product
    public function VendorEditProduct($id) {
        $mulImgs = MultiImg::where('product_id',$id)->get();
        $Brands = Brand::latest()->get();
        $Category = Category::latest()->get();
        $Subcategory = Subcategor::latest()->get();
        $Product = Product::findOrFail($id);
        
        return view('vendor.backend.product.vendor_product_edit', compact('Brands','Category','Subcategory','Product','mulImgs'));

    }//end

    //update vendor product
    public function VendorUpdateProduct(Request $request) {
        $product_id = $request->id;

        Product::findOrFail($product_id)->update([
            'brand_id' => $request->brand_id,
            'category_id' => $request->category_id,
            'subcategory_id' => $request->subcategory_id,
            'product_name' => $request->product_name,

            'product_slug' => strtolower(str_replace(' ','_',$request->product_name)),
            'product_code' => $request->product_code,
            'product_qty' => $request->product_qty,
            'product_tags' => $request->product_tags,

            'product_size' => $request->product_size,
            'product_color' => $request->product_color,
            'selling_price' => $request->selling_price,
            'discount_price' => $request->discount_price,
            'short_descp' => $request->short_descp,

            'long_descp' => $request->long_descp,
            'hot_deals' => $request->hot_deals,
            'featured' => $request->featured,
            'special_offer' => $request->special_offer,
            'special_deals' => $request->special_deals,

            'status' => 1,
            'created_at' => Carbon::now()
        ]);

        $notification = array (
            'message' => 'Vendor Product Updated Without Image Successfully',
            'alert-type' => 'success'
        );
    
        return redirect()->route('vendor.all.product')->with($notification);

    }//end

     //vendor product thambnail img update
     public function VendorUpdateProductThambnail(Request $request) {

        $pro_id = $request->id;
        $old_img = $request->old_img;

        $img = $request->file('product_thambnail');
        $manager = new ImageManager(new Driver());
        $name_gen = hexdec(uniqid()).'.'.$img->getClientOriginalExtension();
        $img = $manager->read($img);
        $img = $img->resize(800,800);

        $img->toJpeg(80)->save(base_path('public/upload/products/thambnails/'.$name_gen));
        $save_url = 'upload/products/thambnails/'.$name_gen;

        if(file_exists($old_img)) {
            unlink($old_img);
        }

        Product::findOrFail($pro_id)->update([
            'product_thambnail' => $save_url,
            'created_at' => Carbon::now(),
        ]); 

        $notification = array (
            'message' => 'Vendor Thambnail Image Updated Successfully',
            'alert-type' => 'success'
        );
    
        return redirect()->back()->with($notification);
    }//end


    // update vendor products multi images
    public function VendorUpdateProductMultiImage(Request $request) {
        $imgs = $request->mul_img;

        foreach($imgs as $id => $img) {
            $delImg = MultiImg::findOrFail($id);
            unlink($delImg->photo_name);

            $manager = new ImageManager(new Driver());
            $name_gen = hexdec(uniqid()).'.'.$img->getClientOriginalExtension();
            $img = $manager->read($img);
            $img = $img->resize(800,800);
    
            $img->toJpeg(80)->save(base_path('public/upload/products/multi-img/'.$name_gen));
            $upload_url = 'upload/products/multi-img/'.$name_gen;

            MultiImg::where('id',$id)->update([
                'photo_name' => $upload_url,
                'created_at' => Carbon::now(),
            ]);
        }//end foreach

        $notification = array (
            'message' => 'Vendor Product Multi Image Updated Successfully',
            'alert-type' => 'success'
        );
    
        return redirect()->back()->with($notification);
    }//end


      //delete vendor product multi img
      public function VendorDeleteProductMultiImage($id) {
        $old_img = MultiImg::findOrFail($id);
        unlink($old_img->photo_name);

        MultiImg::findOrFail($id)->delete();

        $notification = array (
            'message' => 'Vendor Product Multi Image Deleted Successfully',
            'alert-type' => 'success'
        );
    
        return redirect()->back()->with($notification);
    }//end

    //product status  active to inactive
    public function VendorProductInactive($id) {
        Product::findOrFail($id)->update(['status' => 0 ]);

        $notification = array (
            'message' => 'Vendor Product Inactive',
            'alert-type' => 'success'
        );
    
        return redirect()->back()->with($notification);
    }//end

    //product status inactive to active 
    public function VendorProductActive($id) {
        Product::findOrFail($id)->update(['status' => 1 ]);

        $notification = array (
            'message' => 'Vendor Product Active',
            'alert-type' => 'success'
        );
    
        return redirect()->back()->with($notification);
    }//end

    //vendor poduct delete
    public function VendorDeleteProduct($id) {
        $product = Product::findOrFail($id);
        unlink($product->product_thambnail);
        Product::findOrFail($id)->delete();

        //delete multi images
        $images = MultiImg::where('product_id',$id)->get();
        foreach($images as $img) {
            unlink($img->photo_name);
            MultiImg::where('product_id',$id)->delete();
        }//end foreach

        $notification = array (
            'message' => 'Vendor Product Deleted Successfully',
            'alert-type' => 'success'
        );
    
        return redirect()->back()->with($notification);
    
    }//end

}
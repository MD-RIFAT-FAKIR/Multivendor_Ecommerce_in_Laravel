<?php

namespace App\Http\Controllers\Backend;

use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;

class CategoryController extends Controller
{
    //all category
    public function AllCategory() {
        $categories = Category::latest()->get();
        return view('backend.category.category_all', compact('categories'));
    }

    //add category
    public function AddCategory() {
        return view('backend.category.category_add');
    }

    //store brand
    public function StoreCategory(Request $request) {

        if($request->file('category_image')) {
            $manager = new ImageManager(new Driver());
            $name_gen = hexdec(uniqid()).'.'.$request->file('category_image')->getClientOriginalExtension();
            $img = $manager->read($request->file('category_image'));
            $img = $img->resize(120,120);

            $img->toJpeg(80)->save(base_path('public/upload/category/'.$name_gen));
            $save_url = 'upload/category/'.$name_gen;

            Category::insert([
                'category_name' => $request->category_name,
                'category_slug' => strtolower(str_replace('','-',$request->category_name)),
                'category_image' => $save_url
            ]);
        }//end if

        $notification = array (
                'message' => 'Category Inserted Successfully',
                'alert-type' => 'success'
        );
        
        return redirect()->route('all.category')->with($notification);

    }//end

    
    // edit brand
    public function EditCategory($id) {
        $category = Category::findorFail($id);

        return view('backend.category.category_edit', compact('category'));
    }//end

    //update brand
    public function UpdateCategory(Request $request) {
        $id = $request->id;
        $old_image = $request->old_image;

        if($request->file('category_image')) {
            $manager = new ImageManager(new Driver());
            $name_gen = hexdec(uniqid()).'.'.$request->file('category_image')->getClientOriginalExtension();
            $img = $manager->read($request->file('category_image'));
            $img = $img->resize(120,120);

            //unlink old_image
            if(file_exists($old_image)) {
                unlink($old_image);
            }

            $img = $img->toJpeg(80)->save(base_path('public/upload/category/'.$name_gen));
            $save_url = 'upload/category/'.$name_gen;

            Category::findorFail($id)->update([
                'category_name' => $request->category_name,
                'category_slug' => strtolower(str_ireplace('','-',$request->category_name)),
                'category_image' => $save_url
            ]);
            
            $notification = array(
                'message' => 'Category Edited With Image Successfully',
                'alert-type' => 'success'
            );

            return redirect()->route('all.category')->with($notification);

        //end if
        } else {
            Category::findorFail($id)->update([
                'category_name' => $request->category_name,
                'category_slug' => strtolower(str_ireplace('','-',$request->category_name)),
            ]);
            
            $notification = array(
                'message' => 'Category Edited Without Image Successfully',
                'alert-type' => 'success'
            );

            return redirect()->route('all.category')->with($notification);
        }//end else
    }//end Brand update

    //delete brand
    public function DeleteCategory($id) {
        $category = Category::findOrFail($id);
        $img = $category->category_image;
        unlink($img);

        Category::findOrFail($id)->delete();

        $notification = array(
            'message' => 'Category Deleted Successfully',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);
    }
}

<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Subcategor;

class SubcategoryController extends Controller
{
    //all subcategory
    public function AllSubcategory() {
        $subcategory = Subcategor::latest()->get();
        return view('backend.subcategory.subcategory_all', compact('subcategory'));
    }//end

    //add subcategory
    public function AddSubcategory() {
        $category = Category::orderBy('category_name', 'ASC')->get();
        return view('backend.subcategory.subcategory_add', compact('category'));
    }//end

    //store subcategory
    public function StoreSubcategory(Request $request) {
        Subcategor::insert([
            'category_id' => $request->category_id,
            'subcategory_name' => $request->subcategory_name,
            'subcategory_slug' => strtolower(str_replace('','-',$request->subcategory_name))
        ]);

        $notification = array (
            'message' => 'SubCategory Inserted Successfully',
            'alert-type' => 'success'
        );
    
        return redirect()->route('all.subcategory')->with($notification);
 
    }//end

    //edit subcategory
    public function EditSubcategory($id) {
        $category = Category::orderBy('category_name', 'ASC')->get();
        $subcategory = Subcategor::findOrFail($id);

        return view('backend.subcategory.subcategory_edit', compact('category', 'subcategory'));
    }//end

    //update subcategory
    public function UpdateSubcategory(Request $request) {
        $subcat_id = $request->id;

        Subcategor::findOrFail($subcat_id)->update([
            'category_id' => $request->category_id,
            'subcategory_name' => $request->subcategory_name,
            'subcategory_slug' => strtolower(str_replace('','-',$request->subcategory_name))
        ]);

        $notification = array (
            'message' => 'SubCategory Updated Successfully',
            'alert-type' => 'success'
        );
    
        return redirect()->route('all.subcategory')->with($notification);

    }//end

    //delete subcategory
    public function DeleteSubcategory($id) {
        Subcategor::findOrFail($id)->delete();

        $notification = array (
            'message' => 'SubCategory Deleted Successfully',
            'alert-type' => 'success'
        );
    
        return redirect()->back()->with($notification);
        
    }//end

    //automatically get subcategory data on admin dashboard when category is selected
    public function GetSubcategory($category_id) {
        $subcat = Subcategor::where('category_id',$category_id)->orderBy('subcategory_name','ASC')->get();

        return json_encode($subcat);
    }
}

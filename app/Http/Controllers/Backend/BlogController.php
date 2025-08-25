<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BlogCategory;
use App\Models\BlogPost;
use Image;
use Carbon\Carbon;

class BlogController extends Controller
{
    //all blog category
    public function BlogCategory() {

        $blogCategories = BlogCategory::latest()->get();
        return view('backend.blog.category.blog_category_all', compact('blogCategories'));

    }//end all blog category

    //add blog category 
    public function AddBlogCategory() {
        return view('backend.blog.category.blog_category_add');
    }
    //end add blog category 

    //store blog category to the db
    public function StoreBlogCategory(Request $request) {
        BlogCategory::insert([
            'blog_category_name' =>  $request->blog_category_name,
            'blog_category_slug' => strtolower(str_replace(' ', '_', $request->blog_category_name)),
            'created_at' => Carbon::now(),
        ]);

        $notification = array(
            'message' => 'Blog Category Name Added succrssfully',
            'alert-type' => 'success'
        );

        return redirect()->route('all.blog.category')->with($notification);

    }//end store blog category to the db

    //blog category edit page setup
    public function EditBlogCategory($id) {
        $blogCategory = BlogCategory::findOrFail($id);

        return view('backend.blog.category.blog_category_edit', compact('blogCategory'));

    }// end blog category edit page setup

    //update blog category
    public function UpdateBlogCategory(Request $request, $id) {
        BlogCategory::findOrFail($id)->update([
            'blog_category_name' =>  $request->blog_category_name,
            'blog_category_slug' => strtolower(str_replace(' ', '_', $request->blog_category_name)),
            'created_at' => Carbon::now(),
        ]);

        $notification = array(
            'message' => 'Blog Category Name Edited succrssfully',
            'alert-type' => 'success'
        );

        return redirect()->route('all.blog.category')->with($notification);

    }//end update blog category

    //delete blog category name
    public function DeleteBlogCategory($id) {
        BlogCategory::findOrFail($id)->delete();

        $notification = array(
            'message' => 'Blog Category Name Deleted succrssfully',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);
    }//end delete blog category name
    
}

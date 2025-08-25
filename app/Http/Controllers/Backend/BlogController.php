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
        return view('backend.blog.blog_category_all', compact('blogCategories'));

    }//end all blog category

    //add blog category 
    public function AddBlogCategory() {
        return view('backend.blog.blog_category_add');
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
            'message' => 'Blog Category Name Add succrssfully',
            'alert-type' => 'success'
        );

        return redirect()->route('all.blog.category')->with($notification);
    }
    //end store blog category to the db
}

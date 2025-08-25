<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BlogCategory;
use App\Models\BlogPost;
use Image;

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
}

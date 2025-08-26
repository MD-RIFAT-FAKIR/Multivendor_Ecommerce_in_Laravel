<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BlogCategory;
use App\Models\BlogPost;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
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
    

    /////////// vlog post ////////////

    //all blog post
    public function BlogPost() {

        $blogPost = BlogPost::latest()->get();
        return view('backend.blog.post.blog_post_all', compact('blogPost'));

    }//end all blog post

    //add blog post
    public function AddBlogPost() {
        $blogCategory = BlogCategory::latest()->get();

        return view('backend.blog.post.blog_post_add', compact('blogCategory'));

    }

    //store blog post
    public function StoreBlogPost(Request $request) {

        $manager = new ImageManager(new Driver());
        $name_gen = hexdec(uniqid()).'.'.$request->file('post_image')->getClientOriginalExtension();

        $img = $manager->read($request->file('post_image'));
        $img = $img->resize(1103,906);

        $img->toJpeg()->save(base_path('public/upload/blog/'.$name_gen));
        $save_url = 'upload/blog/'.$name_gen;

        BlogPost::insert([
            'category_id' => $request->category_id,
            'post_title' => $request->post_title,
            'post_slug' => strtolower(str_replace(' ', '_', $request->post_title)),
            'post_image' => $save_url,
            'post_short_description' => $request->post_short_description,
            'post_long_description' => $request->post_long_description,
            'created_at' => Carbon::now(),
        ]);

        $notification = array(
            'message' => 'Blog Post Created Successfully',
            'alert-type' => 'success'
        );

        return redirect()->route('all.blog.post')->with($notification);
 
    }//store blog post

    //edit blog post
    public function EditBlogPost($id) {
        $blogCategories = BlogCategory::latest()->get();
        $blogPost = BlogPost::findOrFail($id);

        return view('backend.blog.post.blog_post_edit', compact('blogCategories', 'blogPost'));
    }//end edit blog post

    //update blog post
    public function UpdateBlogPost(Request $request, $id) {
        $manager = new ImageManager(new Driver);
        $name_gen = hexdec(uniqid()).'.'.$request->file('post_image')->getClientOriginalExtension();

        $img = $manager->read($request->file('post_image'));
        $img = $img->resize(1103, 906);

        $img->toJpeg()->save(base_path('public/upload/blog/'.$name_gen));
        $save_url = 'upload/blog/'.$name_gen;

        BlogPost::findOrFail($id)->update([
            'category_id' => $request->category_id,
            'post_title' => $request->post_title,
            'post_slug' => strtolower(str_replace(' ', '_', $request->post_title)),
            'post_image' => $save_url,
            'post_short_description' => $request->post_short_description,
            'post_long_description' => $request->post_long_description,
            'created_at' => Carbon::now(),
        ]);

        $notification = array(
            'message' => 'Blog Post Updated Successfully',
            'alert-type' => 'success'
        );

        return redirect()->route('all.blog.post')->with($notification);

    }// end update blog post

    //delete blog post
    public function DeleteBlogPost($id) {
        BlogPost::findOrFail($id)->delete();

         $notification = array(
            'message' => 'Blog Post Deleted Successfully',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);
    }

    /////////// end vlog post ////////////




//////////////////// frontend vlog /////////////////////////

public function HomeBlogPost() {
    $blogCategories = BlogCategory::latest()->get();
    $blogPost = BlogPost::latest()->get();

    return view('frontend.blog.home_blog', compact('blogCategories', 'blogPost'));
}

//post details page
public function BlogDetails(Request $request, $id) {
    $blogCategories = BlogCategory::latest()->get();
    $blogdetails = BlogPost::findOrFail($id);
    $bredCat = BlogCategory::where('id', $blogdetails->category_id)->get();

    return view('frontend.blog.blog_details', compact('blogCategories', 'blogdetails', 'bredCat'));
}//end post details page

//post category
public function BlogCategoryPost($id, $slug) {
    $blogCategories = BlogCategory::latest()->get();
    $blogPost = BlogPost::where('category_id', '=', $id)->get();
    $bredCat = BlogCategory::where('id', $id)->get();

    return view('frontend.blog.blog_category', compact('blogCategories', 'blogPost', 'bredCat'));
}

//////////////////// end frontend vlog /////////////////////////


}

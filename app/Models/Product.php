<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $guarded = [];

    //relationship between product and vendor
    public function vendor () {
        return $this->belongsTo(User::class,'vendor_id','id');
    }

    //relationship between product and category
    public function category () {
        return $this->belongsTo(Category::class,'category_id','id');
    }

    //relation between brand and product
    public function brand () {
        return $this->belongsTo(Brand::class,'brand_id','id');
    }

    //relationship between subcategory and product
    public function subcategory () {
        return $this->belongsTo(Subcategor::class,'subcategory_id','id');
    }
}


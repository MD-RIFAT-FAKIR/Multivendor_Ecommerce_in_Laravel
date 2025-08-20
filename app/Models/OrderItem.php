<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;
    protected $guarded = [];

    //make relation between orderitem model and order model get access order table data
    public function order() {
        return $this->belongsTo(Order::class, 'order_id');
    }
}

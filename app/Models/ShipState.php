<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShipState extends Model
{
    use HasFactory;
    protected $guarded = [];

    //division
    public function division() {
        return $this->belongsTo (ShipDivision::class, 'division_id', 'id');
    }

    //state
    public function district() {
        return $this->belongsTo (ShipDistrict::class, 'districts_id', 'id');
    }
}

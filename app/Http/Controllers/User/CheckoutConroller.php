<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Coupon;
use App\Models\ShipDivision;
use App\Models\ShipDistrict;
use App\Models\ShipState;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;
use Auth;


class CheckoutConroller extends Controller
{
    //
     public function DistrictGetAjax($division_id){

        $ship = ShipDistrict::where('division_id',$division_id)->orderBy('districts_name','ASC')->get();
        return json_encode($ship);

    } // End Method 

    public function StateGetAjax($district_id){

        $ship = ShipState::where('districts_id',$district_id)->orderBy('state_name','ASC')->get();
        return json_encode($ship);

    }// End Method 
}

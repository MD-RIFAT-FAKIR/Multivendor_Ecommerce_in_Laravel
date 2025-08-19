<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Charge;

class StripeController extends Controller
{
    //
     public function StripeOrder(Request $request){

        // Use secret key directly from .env
        Stripe::setApiKey(env('STRIPE_SECRET_KEY'));

        $token = $request->stripeToken;

        $charge = \Stripe\Charge::create([
          'amount' => 999*100,
          'currency' => 'usd',
          'description' => 'Easy Mulit Vendor Shop',
          'source' => $token,
          'metadata' => ['order_id' => '6735'],
        ]);

        dd($charge);

    }// End Method
}

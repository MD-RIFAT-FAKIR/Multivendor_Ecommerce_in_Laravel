<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Review;
use Carbon\Carbon;
use Auth;

class ReviewController extends Controller
{
    //
    public function StoreReview(Request $request) {
        $product = $request->product_id;
        $vendor = $request->vendor_id;

        $request->validate([
            'comment' => 'required',
        ]);

        Review::insert([
            'product_id' => $product,
            'user_id' => Auth::id(),
            'comment' => $request->comment,
            'rating' => $request->quality,
            'vendor_id' => $vendor,
            'created_at' => Carbon::now(),
        ]);

        $notification = array(
            'message' => 'Review will approve by  admin',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);
    }


    ///////////// admin review manage ////////////////////

    //admin pending review
    public function PendingReview() {
        $review = Review::where('status', 0)->orderBy('id', 'desc')->get();

        return view('backend.review.pending_review', compact('review'));
    }//end admin pending review

    //admin approve review
    public function ApproveReview($id) {
        Review::where('id',$id)->update([
            'status' => 1,
        ]);

        $notification = array(
            'message' => 'Review approved successfully',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);
    }//end admin approve review

    //admin published review
    public function PublishedReview() {
        $review = Review::where('status', 1)->orderBy('id', 'desc')->get();

        return view('backend.review.puplished_review', compact('review'));
    }//end admin published review


    ///////////// end admin review manage ////////////////
}

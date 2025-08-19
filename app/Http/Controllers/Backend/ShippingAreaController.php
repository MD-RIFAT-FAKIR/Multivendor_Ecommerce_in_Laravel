<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ShipDivision;
use App\Models\ShipDistrict;
use App\Models\ShipState;
use Carbon\Carbon;

class ShippingAreaController extends Controller
{
    //All division
    public function AllDivision() {
        $division = ShipDivision::latest()->get();
        return view('backend.ship.division.division_all', compact('division'));
    }//end

    //add division
    public function AddDivision() {
        return view('backend.ship.division.division_add');
    }//end

    //store division
    public function StoreDivision(Request $request) {
        ShipDivision::insert([
            'division_name' => strtoupper($request->division_name),
        ]);

        $notification = array (
            'message' => 'Division Inserted Successfully',
            'alert-type' => 'success'
        );
    
        return redirect()->route('all.division')->with($notification);
 
    }//end

    //edit division
    public function EditDivision($id) {
        $division = ShipDivision::findOrFail($id);

        return view('backend.ship.division.division_edit', compact('division'));
    }//end


    //update division
    public function UpdateDivision(Request $request) {
        $division_id = $request->id;

        ShipDivision::findOrFail($division_id)->update([
            'division_name' => strtoupper($request->division_name),
        ]);

        $notification = array (
            'message' => 'Division Updated Successfully',
            'alert-type' => 'success'
        );
    
        return redirect()->route('all.division')->with($notification);
    }//end

    //delete division
    public function DeleteDivision($id) {
        ShipDivision::findOrFail($id)->delete();

        $notification = array (
            'message' => 'Division Deleted Successfully',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);
    }//end


    ////////District/////////////

    //All district
    public function AllDistrict() {
        $district = ShipDistrict::latest()->get();
        return view('backend.ship.district.district_all', compact('district'));
    }//end

    //add district
    public function AddDistrict() {
        $division = ShipDivision::orderBy('division_name','ASC')->get();
        return view('backend.ship.district.district_add', compact('division'));
    }//end

    //store district
    public function StoreDistrict(Request $request) {
        ShipDistrict::insert([
            'division_id' => $request->division_id,
            'districts_name' => strtoupper($request->districts_name),
        ]);

        $notification = array (
            'message' => 'District Inserted Successfully',
            'alert-type' => 'success'
        );
    
        return redirect()->route('all.district')->with($notification);
 
    }//end

    //edit district
    public function EditDistrict($id) {
        $division = ShipDivision::orderBy('division_name','ASC')->get();
        $district = ShipDistrict::findOrFail($id);
        

        return view('backend.ship.district.district_edit', compact('division', 'district'));
    }//end

    //update district
    public function UpdateDistrict(Request $request) {
        $district_id = $request->id;

        ShipDistrict::findOrFail($district_id)->update([
            'division_id' => $request->division_id,
            'districts_name' => strtoupper($request->districts_name),
        ]);

        $notification = array (
            'message' => 'District Updated Successfully',
            'alert-type' => 'success'
        );
    
        return redirect()->route('all.district')->with($notification);
    }//end

    //delete district
    public function DeleteDistrict($id) {
        ShipDistrict::findOrFail($id)->delete();

        $notification = array (
            'message' => 'District Deleted Successfully',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);
    }//end


    ////////State/////////////

    //All state
    public function AllState() {
        $state = ShipState::latest()->get();
        return view('backend.ship.state.state_all', compact('state'));
    }//end

    //Add state
    public function AddState() {
        $division = ShipDivision::orderBy('division_name','ASC')->get();
        $district = ShipDistrict::orderBy('districts_name','ASC')->get();

        return view('backend.ship.state.state_add', compact('division', 'district'));
    }

    //
    public function GetDistrict($division_id){
        $dist = ShipDistrict::where('division_id',$division_id)->orderBy('districts_name','ASC')->get();
            return json_encode($dist);

    }// End Method 

    //store state to database
     public function StoreState(Request $request){ 

        ShipState::insert([ 
            'division_id' => $request->division_id, 
            'districts_id' => $request->districts_id, 
            'state_name' => $request->state_name,
        ]);

       $notification = array(
            'message' => 'State Inserted Successfully',
            'alert-type' => 'success'
        );

        return redirect()->route('all.state')->with($notification); 

    }// End Method 

    //edit state
    public function EditState($id){
        $division = ShipDivision::orderBy('division_name','ASC')->get();
        $district = ShipDistrict::orderBy('districts_name','ASC')->get();
        $state = ShipState::findOrFail($id);

         return view('backend.ship.state.state_edit',compact('division','district','state'));
    }// End Method 

    public function UpdateState(Request $request){

        $state_id = $request->id;

         ShipState::findOrFail($state_id)->update([
            'division_id' => $request->division_id, 
            'districts_id' => $request->district_id, 
            'state_name' => $request->state_name,
        ]);

       $notification = array(
            'message' => 'State Updated Successfully',
            'alert-type' => 'success'
        );

        return redirect()->route('all.state')->with($notification); 


    }// End Method 
}
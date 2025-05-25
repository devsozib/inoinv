<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\Admin\Schedule;
use App\Http\Controllers\Controller;
use App\Models\Admin\Location as AdminLocation;

class Location extends Controller
{

    public function index()
    {
        $location = AdminLocation::first();
        $coupons = AdminLocation::get();
        return view('admin.pages.settings.location.index', compact('coupons','location'));
    }

    public function store(Request $request)
    {
        $request->validate([                   
            'longitude' => 'required',
            'latitude' => 'required',   
            'address' => 'required',  
            'zoom' => 'required',
            'status' => 'required',
        ]);

        $location = AdminLocation::first();
        if(!$location) $location = new AdminLocation();
        $location->longitude = $request->input('longitude');      
        $location->latitude = $request->input('latitude');
        $location->address = $request->input('address');
        $location->zoom = $request->input('zoom');
        $location->status = $request->input('status');
        $location->save();
        session()->flash('sweet_alert', [
            'type' => 'success',
            'title' => 'Success!',
            'text' => 'Location added success',
        ]);
        return redirect()->back();
    }

    public function update(Request $request, $id)
    {

        $request->validate([                   
            'longitude' => 'required',
            'latitude' => 'required',   
            'status' => 'required',
        ]);


        $coupon = AdminLocation::findOrFail($id);
        $coupon->longitude = $request->input('longitude');      
        $coupon->latitude = $request->input('latitude');
        $coupon->status = $request->input('status');
        $coupon->update();
        session()->flash('sweet_alert', [
            'type' => 'success',
            'title' => 'Success!',
            'text' => 'Location update success',
        ]);
        return redirect()->back();
    }

    public function locationSchedule(){
        $location = AdminLocation::first();
        $schedule = Schedule::first();
        $data = [
            'address' => $location->address,
            'longitude' => $location->longitude,
            'latitude' => $location->latitude,
            'schedule' => $schedule->schedule,
        ];
        return response()->json($data);
    }
}

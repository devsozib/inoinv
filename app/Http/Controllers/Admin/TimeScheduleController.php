<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\Schedule;
use Illuminate\Http\Request;

class TimeScheduleController extends Controller
{

    public function index()
    {
        $coupons = Schedule::get();
        return view('admin.pages.settings.schedule.index', compact('coupons'));
    }

    public function store(Request $request)
    {
        $request->validate([                   
            'type' => 'required',
            'schedule' => 'required',   
            'status' => 'required',
        ]);

        $coupon = new Schedule();
        $coupon->schedule = $request->input('schedule');      
        $coupon->type = $request->input('type');
        $coupon->status = $request->input('status');
        $coupon->save();
        session()->flash('sweet_alert', [
            'type' => 'success',
            'title' => 'Success!',
            'text' => 'Schedule Added success',
        ]);
        return redirect()->back();
    }

    public function update(Request $request, $id)
    {

        $request->validate([                   
            'type' => 'required',
            'schedule' => 'required',   
            'status' => 'required',
        ]);

        $coupon = Schedule::findOrFail($id);
        $coupon->schedule = $request->input('schedule');      
        $coupon->type = $request->input('type');
        $coupon->status = $request->input('status');
        $coupon->update();
        session()->flash('sweet_alert', [
            'type' => 'success',
            'title' => 'Success!',
            'text' => 'Schedule update success',
        ]);
        return redirect()->back();
    }
}

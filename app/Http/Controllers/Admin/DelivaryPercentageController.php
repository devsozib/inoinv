<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin\DeliveryPercentage;
use Illuminate\Support\Facades\Validator;

class DelivaryPercentageController extends Controller
{
    public function index(){
        $categories = DeliveryPercentage::get();
        return view("admin.pages.settings.delivery_percentage", compact('categories'));
    }

    public function store(Request $request){
        $validator = Validator::make($request->all(), [
            'min_amount' => 'required|numeric',
            'charge_percentage' => 'required|numeric',
        ]);
        if ($validator->fails()) {
            return response()->redirect()->back()->with(['errors' => $validator->errors()], 422);
        }

        $deliveryCharege = new DeliveryPercentage;
        $deliveryCharege->min_amount = $request->min_amount;
        $deliveryCharege->charge_percentage = $request->charge_percentage;        
        $deliveryCharege->save();
        session()->flash('sweet_alert', [
            'type' => 'success',
            'title' => 'Success!',
            'text' => 'Delivery Charege Added success',
        ]);
        return redirect()->back();
    }

    public function update(Request $request, $id){
        $validator = Validator::make($request->all(), [
            'min_amount' => 'required|numeric',
            'charge_percentage' => 'required|numeric',
        ]);
        if ($validator->fails()) {
            return response()->redirect()->back()->with(['errors' => $validator->errors()], 422);
        }
        $deliveryCharege = DeliveryPercentage::find($id);
        if($deliveryCharege){
            $deliveryCharege->min_amount = $request->min_amount;
            $deliveryCharege->charge_percentage = $request->charge_percentage;         
            $deliveryCharege->save();
        }
        session()->flash('sweet_alert', [
            'type' => 'success',
            'title' => 'Success!',
            'text' => 'Delivery Charege update success',
        ]);
        return redirect()->back();
    }
}

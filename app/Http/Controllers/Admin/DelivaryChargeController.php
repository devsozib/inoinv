<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Admin\DelivaryCharge;
use Illuminate\Support\Facades\Validator;

class DelivaryChargeController extends Controller
{
    public function index(){
        $categories = DelivaryCharge::get();
        return view("admin.pages.settings.index", compact('categories'));
    }

    public function store(Request $request){
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'amount' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->redirect()->back()->with(['errors' => $validator->errors()], 422);
        }

        $deliveryCharege = new DelivaryCharge;
        $deliveryCharege->name = $request->name;
        $deliveryCharege->amount = $request->amount;        
        $deliveryCharege->save();
        session()->flash('sweet_alert', [
            'type' => 'success',
            'title' => 'Success!',
            'text' => 'Delivery Charege Added success',
        ]);
        return redirect()->route('delivery_charges.index');
    }
    
    public function update(Request $request, $id){
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'amount' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->redirect()->back()->with(['errors' => $validator->errors()], 422);
        }
        $deliveryCharege = DelivaryCharge::find($id);
        if($deliveryCharege){
            $deliveryCharege->name = $request->name;
            $deliveryCharege->amount = $request->amount;          
            $deliveryCharege->save();
        }
        session()->flash('sweet_alert', [
            'type' => 'success',
            'title' => 'Success!',
            'text' => 'Delivery Charege update success',
        ]);
        return redirect()->route('delivery_charges.index');
    }

    public function getDeliveryCharge(){
        $deliveryCharege = DelivaryCharge::first();
        return response()->json($deliveryCharege);
    }
}

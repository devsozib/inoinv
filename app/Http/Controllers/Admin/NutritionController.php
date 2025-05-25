<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Admin\Nutrition;

class NutritionController extends Controller
{
    public function index(){
        $nutritions = Nutrition::get();
        return view("admin.pages.product.nutrition", compact('nutritions'));
    }

    public function store(Request $request){
        $validator = Validator::make($request->all(), [
            'nutrition' => 'required|string|max:255',
            'status' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->redirect()->back()->with(['errors' => $validator->errors()], 422);
        }

        $nutrition = new Nutrition;
        $nutrition->name = $request->nutrition;
        $nutrition->status = $request->status;
        $nutrition->created_by = auth()->user()->id;
        $nutrition->save();
        session()->flash('sweet_alert', [
            'type' => 'success',
            'title' => 'Success!',
            'text' => 'Nutrition Added success',
        ]);
        return redirect()->route('nutritions.index');
    }

    public function update(Request $request, $id){
        $validator = Validator::make($request->all(), [
            'nutrition' => 'required|string|max:255',
            'status' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->redirect()->back()->with(['errors' => $validator->errors()], 422);
        }
        $nutrition = Nutrition::find($id);
        if($nutrition){
            $nutrition->name = $request->nutrition;
            $nutrition->status = $request->status;
            $nutrition->created_by = auth()->user()->id;
            $nutrition->save();
        }
        session()->flash('sweet_alert', [
            'type' => 'success',
            'title' => 'Success!',
            'text' => 'nutrition update success',
        ]);
        return redirect()->route('nutritions.index');
    }
}

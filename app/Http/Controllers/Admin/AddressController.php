<?php

namespace App\Http\Controllers\Admin;

use App\Models\Admin\Area;
use Illuminate\Http\Request;
use App\Models\Admin\District;
use App\Http\Controllers\Controller;

class AddressController extends Controller
{
    public function discrict(){

        // $dis = allDistrict();
        // $ars = districtWiseArea();
        // for($i=1; $i<=64; $i++){
        //     $district = new District;
        //     $district->name = $dis[$i];
        //     $district->status = '1';
        //     $district->save();

        //     for($j=1; $j<=count($ars[$i]); $j++){
        //         $area = new Area;
        //         $area->name = $ars[$i][$j];
        //         $area->district_id = $district->id;
        //         $area->status = '1';
        //         $area->save();
        //     }
        // }



        $districts = District::where('status','1')->get();
        return view('admin.pages.settings.district', compact('districts',));
    }
    public function area(){
        $areas = Area::where('status','1')->orderBy('district_id','asc')->get();
        $lib_districts = lib_districts();
        return view('admin.pages.settings.area', compact('areas','lib_districts'));
    }
    public function storeDistrict(Request $request){
        $district = new District;
        $district->name = $request->name;
        $district->status = $request->status;
        $district->save();
        return redirect()->back()->with(['success' => getNotify(1)]);
    }
    public function updateDistrict(Request $request, $id){
        $district = District::where('id',$id)->first();
        if(!$district) abort(404);
        $district->name = $request->name;
        $district->status = $request->status;
        $district->update();
        return redirect()->back()->with(['success' => getNotify(2)]);
    }

    public function deleteDistrict(Request $request, string $id){
        $district = District::where('id',$id)->first();
        if(!$district) abort(404);
        $district->delete();
        return redirect()->back();
    }

    public function storeArea(Request $request){
        $area = new Area;
        $area->name = $request->name;
        $area->district_id = $request->district_id;
        $area->status = $request->status;
        $area->save();
        return redirect()->back()->with(['success' => getNotify(1)]);
    }
    
    public function updateArea(Request $request, $id){
        $area = Area::where('id',$id)->first();
        if(!$area) abort(404);
        $area->name = $request->name;
        $area->district_id = $request->district_id;
        $area->status = $request->status;
        $area->update();
        return redirect()->back()->with(['success' => getNotify(2)]);
    }

    public function deleteArea(Request $request, string $id){
        $area = Area::where('id',$id)->first();
        if(!$area) abort(404);
        $area->delete();
        return redirect()->back();
    }
    
}

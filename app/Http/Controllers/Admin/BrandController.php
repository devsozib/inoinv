<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin\Brand;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\Admin\HomePage;

class BrandController extends Controller
{
    public function index(Request $request){
        $brands = Brand::get();
        return view("admin.pages.product.brand.index", compact('brands'));
    }
    public function store(Request $request){
        $imageName = "";
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $destinationPath = public_path('frontend/brand_images/');
            $imageName = now()->format('YmdHis') . '_' . Str::random(10) . '.' . $image->getClientOriginalExtension();
            $image->move($destinationPath, $imageName);
        }

        $brand = new Brand;
        $brand->name = $request->name;
        $brand->search_string = $request->name.' '.getBanglish($request->name);
        $brand->image = $imageName;
        $brand->status = $request->status;
        $brand->created_by = auth()->user()->id;
        $brand->save();
       
        return redirect()->back();
    }

    public function update(Request $request, string $id){

        $brand = Brand::where('id', $id)->first();

        $imageName = "";
        if ($image = $request->file('image')) {
            if ($brand->image != NULL) {
                $imagePath = public_path('frontend/brand_images/' . $brand->image);
                if (File::exists($imagePath)) {
                    unlink($imagePath);
                }
            }
            $destinationPath = public_path('frontend/brand_images/');
            $imageName = date('YmdHis') . "." . $image->getClientOriginalExtension();
            $image->move($destinationPath, $imageName);
        } else {
            $imageName = $brand->image;
        }

        $brand->name = $request->name;
        $brand->image = $imageName;
        $brand->status = $request->status;
        $brand->updated_by = auth()->user()->id;
        $brand->update();
       
        return redirect()->back();
    }

    public function destroy(Request $request, string $id){
        $brand = Brand::where('id', $id)->first();

        if($brand){
            if ($brand->image != NULL) {
                $imagePath = public_path('frontend/brand_images/' . $brand->image);
                if (File::exists($imagePath)) {
                    unlink($imagePath);
                }
            }
            $brand->delete();
        }
        
        return redirect()->back();

    }
    public function showOnHome(Request $request){
        $id = $request->id;
        $brand = Brand::where('id', $id)->first();
        if(!$brand){
            return response()->json([
                'type' => 'error',
                'message' => getNotify(10),
            ]);
        }

        $homePage = HomePage::where('for_book_or_product', '2')
        ->where('item_type','2')->where('item_id',$brand->id)->first();
        if(!$homePage){
            $homePage = new HomePage;
            $homePage->for_book_or_product = '2';
            $homePage->item_type = '2';
            $homePage->item_id = $brand->id;
            $homePage->title = $brand->name;
            $homePage->more_button_title = "আরও দেখুন";
            $homePage->created_by = auth()->user()->id;
            $homePage->save();
        }

        if($request->homeView == "true"){
            $brand->is_home_view = '1';
            $homePage->status = '1';
        }
        else{
            $brand->is_home_view = '0';
            $homePage->status = '0';
        }
        $brand->updated_by = auth()->user()->id;
        $brand->update();
        $homePage->updated_by = auth()->user()->id;
        $homePage->update();
        return response()->json([
            'type' => 'success',
            'message' => getNotify(2),
        ]);

    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin\Publisher;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\Admin\HomePage;

class PublisherController extends Controller
{
    public function index(Request $request){
        $publisher = Publisher::get();
        return view("admin.pages.book.publisher.index", compact('publisher'));
    }
    public function store(Request $request){
        $imageName = "";
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $destinationPath = public_path('frontend/publisher_images/');
            $imageName = now()->format('YmdHis') . '_' . Str::random(10) . '.' . $image->getClientOriginalExtension();
            $image->move($destinationPath, $imageName);
        }

        $publisher = new Publisher;
        $publisher->name = $request->name;
        $publisher->search_string = $request->name.' '.getBanglish($request->name);
        $publisher->image = $imageName;
        $publisher->status = $request->status;
        $publisher->created_by = auth()->user()->id;
        $publisher->save();
       
        return redirect()->back();
    }

    public function update(Request $request, string $id){

        $publisher = Publisher::where('id', $id)->first();

        $imageName = "";
        if ($image = $request->file('image')) {
            if ($publisher->image != NULL) {
                $imagePath = public_path('frontend/publisher_images/' . $publisher->image);
                if (File::exists($imagePath)) {
                    unlink($imagePath);
                }
            }
            $destinationPath = public_path('frontend/publisher_images/');
            $imageName = date('YmdHis') . "." . $image->getClientOriginalExtension();
            $image->move($destinationPath, $imageName);
        } else {
            $imageName = $publisher->image;
        }

        $publisher->name = $request->name;
        $publisher->image = $imageName;
        $publisher->status = $request->status;
        $publisher->updated_by = auth()->user()->id;
        $publisher->update();
       
        return redirect()->back();
    }

    public function destroy(Request $request, string $id){
        $publisher = Publisher::where('id', $id)->first();

        if($publisher){
            if ($publisher->image != NULL) {
                $imagePath = public_path('frontend/publisher_images/' . $publisher->image);
                if (File::exists($imagePath)) {
                    unlink($imagePath);
                }
            }
            $publisher->delete();
        }
        
        return redirect()->back();

    }

    public function showOnHome(Request $request){
        $id = $request->id;
        $publisher = Publisher::where('id', $id)->first();
        if(!$publisher){
            return response()->json([
                'type' => 'error',
                'message' => getNotify(10),
            ]);
        }

        $homePage = HomePage::where('for_book_or_product', '1')
        ->where('item_type','5')->where('item_id',$publisher->id)->first();
        if(!$homePage){
            $homePage = new HomePage;
            $homePage->for_book_or_product = '1';
            $homePage->item_type = '5';
            $homePage->item_id = $publisher->id;
            $homePage->title = $publisher->name;
            $homePage->more_button_title = "আরও দেখুন";
            $homePage->created_by = auth()->user()->id;
            $homePage->save();
        }

        if($request->homeView == "true"){
            $publisher->is_home_view = '1';
            $homePage->status = '1';
        }
        else{
            $publisher->is_home_view = '0';
            $homePage->status = '0';
        }
        $publisher->updated_by = auth()->user()->id;
        $publisher->update();
        $homePage->updated_by = auth()->user()->id;
        $homePage->update();
        return response()->json([
            'type' => 'success',
            'message' => getNotify(2),
        ]);

    }
}

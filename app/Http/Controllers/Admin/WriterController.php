<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin\Writer;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\Admin\HomePage;

class WriterController extends Controller
{
    public function index(Request $request){
        $writers = Writer::get();
        return view("admin.pages.book.writer.index", compact('writers'));
    }
    public function store(Request $request){
        $imageName = "";
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $destinationPath = public_path('frontend/writer_images/');
            $imageName = now()->format('YmdHis') . '_' . Str::random(10) . '.' . $image->getClientOriginalExtension();
            $image->move($destinationPath, $imageName);
        }

        $writer = new Writer;
        $writer->name = $request->name;
        $writer->search_string = $request->name.' '.getBanglish($request->name);
        $writer->image = $imageName;
        $writer->details = $request->details;
        $writer->status = $request->status;
        $writer->created_by = auth()->user()->id;
        $writer->save();
       
        return redirect()->back();
    }

    public function update(Request $request, string $id){

        $writer = Writer::where('id', $id)->first();

        $imageName = "";
        if ($image = $request->file('image')) {
            if ($writer->image != NULL) {
                $imagePath = public_path('frontend/writer_images/' . $writer->image);
                if (File::exists($imagePath)) {
                    unlink($imagePath);
                }
            }
            $destinationPath = public_path('frontend/writer_images/');
            $imageName = date('YmdHis') . "." . $image->getClientOriginalExtension();
            $image->move($destinationPath, $imageName);
        } else {
            $imageName = $writer->image;
        }

        $writer->name = $request->name;
        $writer->image = $imageName;
        $writer->details = $request->details;
        $writer->status = $request->status;
        $writer->updated_by = auth()->user()->id;
        $writer->update();
        return redirect()->back();
    }

    public function destroy(Request $request, string $id){
        $writer = Writer::where('id', $id)->first();

        if($writer){
            if ($writer->image != NULL) {
                $imagePath = public_path('frontend/writer_images/' . $writer->image);
                if (File::exists($imagePath)) {
                    unlink($imagePath);
                }
            }
            $writer->delete();
        }
        
        return redirect()->back();

    }
    public function showOnHome(Request $request){
        $id = $request->id;
        $writer = Writer::where('id', $id)->first();
        if(!$writer){
            return response()->json([
                'type' => 'error',
                'message' => getNotify(10),
            ]);
        }
        $homePage = HomePage::where('for_book_or_product', '1')
        ->where('item_type','4')->where('item_id',$writer->id)->first();
        if(!$homePage){
            $homePage = new HomePage;
            $homePage->for_book_or_product = '1';
            $homePage->item_type = '4';
            $homePage->item_id = $writer->id;
            $homePage->title = $writer->name;
            $homePage->more_button_title = "আরও দেখুন";
            $homePage->created_by = auth()->user()->id;
            $homePage->save();
        }
        if($request->homeView == "true"){
            $writer->is_home_view = '1';
            $homePage->status = '1';
        }
        else{
            $writer->is_home_view = '0';
            $homePage->status = '0';
        }
        $writer->updated_by = auth()->user()->id;
        $writer->update();
        $homePage->updated_by = auth()->user()->id;
        $homePage->update();
        return response()->json([
            'type' => 'success',
            'message' => getNotify(2),
        ]);

    }
}

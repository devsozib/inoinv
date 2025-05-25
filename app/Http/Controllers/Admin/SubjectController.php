<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin\Subject;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\Admin\HomePage;

class SubjectController extends Controller
{
    public function index(Request $request){
        $subjects = Subject::get();
        return view("admin.pages.book.subject.index", compact('subjects'));
    }
    public function store(Request $request){
       

        $subject = new Subject;
        $subject->name = $request->name;
        $subject->search_string = $request->name.' '.getBanglish($request->name);
        $subject->status = $request->status;
        $subject->created_by = auth()->user()->id;
        $subject->save();
       
        return redirect()->back();
    }

    public function update(Request $request, string $id){

        $subject = Subject::where('id', $id)->first();
        if($subject){
            $subject->name = $request->name;
            $subject->status = $request->status;
            $subject->updated_by = auth()->user()->id;
            $subject->update();
           
            return redirect()->back();
        }else{
            return redirect()->back();
        }
    }

    public function destroy(Request $request, string $id){
        $subject = Subject::where('id', $id)->first();

        if($subject){
            if ($subject->image != NULL) {
                $imagePath = public_path('frontend/writer_images/' . $subject->image);
                if (File::exists($imagePath)) {
                    unlink($imagePath);
                }
            }
            $subject->delete();
        }
        
        return redirect()->back();

    }
    public function showOnHome(Request $request){
        $id = $request->id;
        $subject = Subject::where('id', $id)->first();
        if(!$subject){
            return response()->json([
                'type' => 'error',
                'message' => getNotify(10),
            ]);
        }
        $homePage = HomePage::where('for_book_or_product', '1')
        ->where('item_type','3')->where('item_id',$subject->id)->first();
        if(!$homePage){
            $homePage = new HomePage;
            $homePage->for_book_or_product = '1';
            $homePage->item_type = '3';
            $homePage->item_id = $subject->id;
            $homePage->title = $subject->name;
            $homePage->more_button_title = "আরও দেখুন";
            $homePage->created_by = auth()->user()->id;
            $homePage->save();
        }
        if($request->homeView == "true"){
            $subject->is_home_view = '1';
            $homePage->status = '1';
        }
        else{
            $subject->is_home_view = '0';
            $homePage->status = '0';
        }
        $subject->updated_by = auth()->user()->id;
        $subject->update();
        $homePage->updated_by = auth()->user()->id;
        $homePage->update();
        return response()->json([
            'type' => 'success',
            'message' => getNotify(2),
        ]);

    }
}


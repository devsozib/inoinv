<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\Admin\OptionTitle;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class OptionTitleController extends Controller
{
    public function index()
    {
        $sizes = OptionTitle::get();
        return view("admin.pages.optiontitle.index", compact('sizes'));
    }

    public function store(Request $request)
    {
        // return $request;
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'status' => 'required',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->with(['errors' => $validator->errors()], 422);
        }

        $size = new OptionTitle();
        $size->name = $request->title;
        $size->status = $request->status;        
        $size->save();
        session()->flash('sweet_alert', [
            'type' => 'success',
            'title' => 'Success!',
            'text' => 'OptionTitle Added success',
        ]);
        return redirect()->route('optiontitles.index');
    }
    public function destroy($id)
    {
        $category = OptionTitle::findOrFail($id);
        $category->delete();
        session()->flash('sweet_alert', [
            'type' => 'success',
            'title' => 'Success!',
            'text' => 'OptionTitle Delete success',
        ]);
        return redirect()->route('optiontitles.index');
    }
    public function update(Request $request, $id)
    {
        // return $request;
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'status' => 'required',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->with(['errors' => $validator->errors()], 422);
        }
        $size = OptionTitle::find($id);
        if ($size) {
            $size->name = $request->title;
            $size->status = $request->status;           
            $size->save();
        }
        session()->flash('sweet_alert', [
            'type' => 'success',
            'title' => 'Success!',
            'text' => 'OptionTitle update success',
        ]);
        return redirect()->route('optiontitles.index');
    }
}

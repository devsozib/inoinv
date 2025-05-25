<?php

namespace App\Http\Controllers\Admin;

use App\Models\Admin\Size;
use Illuminate\Http\Request;
use App\Models\Admin\Category;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class SizeController extends Controller
{
    public function index()
    {
        $sizes = Size::get();
        return view("admin.pages.product.size", compact('sizes'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'size' => 'required|string|max:255',
            'status' => 'required',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->with(['error' => getNotify(4)])->withErrors($validation)->withInput();
        }

        $size = new Size;
        $size->name = $request->size;
        $size->status = $request->status;
        $size->created_by = auth()->user()->id;
        $size->save();
        return redirect()->back()->with(['success' => getNotify(1)]);
    }
    public function destroy($id)
    {
        $size = Size::findOrFail($id);
        $size->delete();
        return redirect()->back()->with(['success' => getNotify(3)]);
    }
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'size' => 'required|string|max:255',
            'status' => 'required',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->with(['error' => getNotify(4)])->withErrors($validation)->withInput();
        }
        $size = Size::findOrFail($id);
        if ($size) {
            $size->name = $request->size;
            $size->status = $request->status;
            $size->updated_by = auth()->user()->id;
            $size->save();
        }
        return redirect()->back()->with(['success' => getNotify(2)]);
    }
}

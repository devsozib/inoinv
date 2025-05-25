<?php

namespace App\Http\Controllers\Admin;

use App\Models\Admin\Size;
use App\Models\Admin\Toping;
use Illuminate\Http\Request;
use App\Models\SizeVsTopingPrice;
use App\Models\Admin\ProductToping;
use App\Http\Controllers\Controller;

class TopingsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $topings = Toping::orderBy('.id', 'desc')->get();
        return view('admin.pages.toping.index', compact('topings'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $sizes = Size::where('status','1')->get();
        return view('admin.pages.toping.create',compact('sizes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        // return $request->all();

        $request->validate([
            'name' => 'required|string',
            'price' => 'required|numeric',
            'status' => 'required|in:0,1',
        ]);
        $imageName = "";
        if ($image = $request->file('images')) {
            $destinationPath = public_path('frontend/toping_images/');
            $imageName = date('YmdHis') . "." . $image->getClientOriginalExtension();
            $image->move($destinationPath, $imageName);
        }
        // Create a new product instance
        $product = new Toping([
            'name' => $request->input('name'),
            'image' =>   $imageName,
            'price' => $request->input('price'),
            'status' => $request->input('status'),
            'created_by' => auth()->user()->id,
        ]);
        $product->save();
        
        $sizeIds = $request->sizeId;
        $prices = $request->prices;
        
        foreach($sizeIds as $key => $sizeId){
            if($sizeId){
                $row = SizeVsTopingPrice::where('size_id',$sizeId)->where('toping_id', $product->id)->first();
                if(!$row){
                    $row = new  SizeVsTopingPrice;
                    $row->size_id = $sizeId;
                    $row->toping_id = $product->id;
                    $row->price = $prices[$key]??0;
                    $row->save();
                }
            }
        }



        session()->flash('sweet_alert', [
            'type' => 'success',
            'title' => 'Success!',
            'text' => 'Toping added success',
        ]);
        // Redirect or return a response as needed
        return redirect()->route('topings.index')->with('success', 'Toping created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $toping = Toping::where('id', $id)->first();
        $sizes = Size::where('status','1')->get();
        $sizeVsToppings = SizeVsTopingPrice::join('sizes','sizes.id','=','size_vs_toping_price.size_id')->select('size_vs_toping_price.id as mainId','sizes.name','sizes.id as sizeId','size_vs_toping_price.price')->where('size_vs_toping_price.toping_id',$id)->get();
        return view('admin.pages.toping.edit', compact('toping','sizes','sizeVsToppings'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $product = Toping::findOrFail($id);
    
        $request->validate([
            'name' => 'required|string',
            'price' => 'required|numeric',
            'status' => 'required|in:0,1',
            'sizeId' => 'required|array',
            'sizeId.*' => 'exists:sizes,id',
            'prices' => 'required|array',
            'prices.*' => 'numeric',
        ]);
    
        $imageName = $product->image;
    
        if ($image = $request->file('images')) {
            if ($product->image != NULL) {
                unlink(public_path('frontend/toping_images/' . $product->image));
            }
            $destinationPath = public_path('frontend/toping_images/');
            $imageName = date('YmdHis') . "." . $image->getClientOriginalExtension();
            $image->move($destinationPath, $imageName);
        }
    
        $product->update([
            'name' => $request->input('name'),
            'price' => $request->input('price'),
            'status' => $request->input('status'),
            'image' => $imageName,
            'updated_by' => auth()->user()->id,
        ]);
    
        // Delete existing SizeVsTopingPrice records for the product
        SizeVsTopingPrice::where('toping_id', $product->id)->delete();
    
        $sizeIds = $request->sizeId;
        $prices = $request->prices;
    
        foreach($sizeIds as $key => $sizeId){
            if($sizeId){
                $row = new SizeVsTopingPrice;
                $row->size_id = $sizeId;
                $row->toping_id = $product->id;
                $row->price = $prices[$key] ?? 0;
                $row->save();
            }
        }
    
        session()->flash('sweet_alert', [
            'type' => 'success',
            'title' => 'Success!',
            'text' => 'Toping update success',
        ]);
    
        return redirect()->route('topings.index')->with('success', 'Toping update successfully');
    }
    

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, string $id)
    {
        if ($request->deleteProductToping == '1') {
            $product = ProductToping::where('id', $id)->first();
            $product->delete();
        } else {
            $product = Toping::where('id', $id)->first();
            $product->delete();
            if ($product->image != NULL) {
                unlink(public_path('frontend/toping_images/' . $product->image));
            }
        }

        session()->flash('sweet_alert', [
            'type' => 'success',
            'title' => 'Success!',
            'text' => 'Toping delete success',
        ]);

        if ($request->deleteProductToping == '1') {
            return redirect()->route('product_topting', $request->product_id)->with('warning', 'Toping delete successfully');
        } else {
            return redirect()->route('topings.index')->with('warning', 'Toping delete successfully');
        }
    }
}

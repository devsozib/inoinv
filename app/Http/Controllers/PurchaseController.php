<?php

namespace App\Http\Controllers;

use App\Models\Vendor;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\Inventory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PurchaseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {  
        $purchases  = Purchase::latest()->get();
        $products = Product::with('latestPurchase')->latest()->get();
        $vendors = Vendor::latest()->get();
        return view('frontend.pages.purchase.index', compact('purchases','products','vendors'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
   public function store(Request $request)
    {
        $request->validate([
            'product_id'  => 'required|exists:products,id',
            'quantity'    => 'required|numeric|min:1',
            'unit_price'  => 'required|numeric|min:0',
            'sub_price'   => 'nullable|numeric',
            'total_price' => 'required|numeric|min:0',
            'payment'     => 'required|numeric|min:0',
            'due'         => 'required|numeric|min:0',
            'vendor_id'   => 'required|exists:vendors,id',
        ]);

        // Create purchase
        $purchase = Purchase::create([
            'product_id'  => $request->product_id,
            'quantity'    => $request->quantity,
            'unit_price'  => $request->unit_price,
            'sub_price'   => $request->sub_price ?? ($request->quantity * $request->unit_price),
            'total_price' => $request->total_price,
            'payment'     => $request->payment,
            'due'         => $request->due,
            'vendor_id'         => $request->vendor_id,
            'created_by'  => Auth::id(),
        ]);

        // Increment inventory quantity
        $inventory = Inventory::where('product_id', $request->product_id)->first();

        if ($inventory) {
            $inventory->current_stock += $request->quantity;
            $inventory->save();
        } else {
            $newInventory  = new Inventory();
            $newInventory->product_id = $request->product_id;
            $newInventory->current_stock = $request->quantity;
            $newInventory->opening_stock = $request->quantity;
            $newInventory->notes = 'Opening stock entry';
            $newInventory->save();
        }

        return redirect()->back()->with('success', 'Purchase created and inventory updated successfully.');
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
        //
    }

    /**
     * Update the specified resource in storage.
     */
  public function update(Request $request, Purchase $purchase)
{
    $request->validate([
        'product_id'  => 'required|exists:products,id',
        'quantity'    => 'required|numeric|min:1',
        'unit_price'  => 'required|numeric|min:0',
        'sub_price'   => 'nullable|numeric',
        'total_price' => 'required|numeric|min:0',
        'payment'     => 'required|numeric|min:0',
        'due'         => 'required|numeric|min:0',
        'vendor_id'         => 'required|exists:vendors,id',
    ]);

   

    $purchase = Purchase::findOrFail($purchase->id);
    $inventory = Inventory::where('product_id', $purchase->product_id)->first();
    if ($inventory) {
        $inventory->current_stock -= $purchase->quantity;
        $inventory->current_stock += $request->quantity;
        $inventory->update();        
    }else{
        $newInventory  = new Inventory();
        $newInventory->product_id = $request->product_id;
        $newInventory->current_stock = $request->quantity;
        $newInventory->opening_stock = $request->quantity;
        $newInventory->notes = 'Opening stock entry';
        $newInventory->save();
    }
    $purchase->product_id  = $request->product_id;
    $purchase->quantity    = $request->quantity;
    $purchase->unit_price  = $request->unit_price;
    $purchase->sub_price   = $request->sub_price ?? ($request->quantity * $request->unit_price);
    $purchase->total_price = $request->total_price;
    $purchase->payment     = $request->payment;
    $purchase->due         = $request->due;
    $purchase->vendor_id         = $request->vendor_id;    
    $purchase->updated_by  = Auth::id();

    $purchase->update();

    return redirect()->back()->with('success', 'Purchase updated and inventory adjusted successfully.');
}


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Purchase $purchase)
    {
        $purchase->delete();
        return redirect()->back()->with('success', 'Purchase deleted successfully.');
    }

    public function getLatestPrice($id)
    {
        $product = Product::with('latestPurchase')->find($id);

        if (!$product) {
            return response()->json(['price' => 0]);
        }

        $price = $product->latestPurchase ? $product->latestPurchase->unit_price : 0;

        return response()->json(['price' => $price]);
    }

}

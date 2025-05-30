<?php

namespace App\Http\Controllers;

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
        $products = Product::latest()->get();
        return view('frontend.pages.purchase.index', compact('purchases','products'));
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
            'created_by'  => Auth::id(),
        ]);

        // Increment inventory quantity
        $inventory = Inventory::where('product_id', $request->product_id)->first();

        if ($inventory) {
            $inventory->current_stock += $request->quantity;
            $inventory->save();
        } else {
            // If inventory doesn't exist for this product, create it
            Inventory::create([
                'product_id' => $request->product_id,
                'current_stock'        => $request->quantity,
                'opening_stock'        => $request->quantity,
                'status'     => 1, // default to active, adjust as needed
            ]);
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
    ]);

    $oldProductId = $purchase->product_id;
    $oldQuantity = $purchase->quantity;

    // Update purchase
    $purchase->update([
        'product_id'  => $request->product_id,
        'quantity'    => $request->quantity,
        'unit_price'  => $request->unit_price,
        'sub_price'   => $request->sub_price ?? ($request->quantity * $request->unit_price),
        'total_price' => $request->total_price,
        'payment'     => $request->payment,
        'due'         => $request->due,
        'updated_by'  => Auth::id(),
    ]);

    // Handle inventory
    if ($oldProductId == $request->product_id) {
        // If the same product, adjust difference in quantity
        $inventory = Inventory::where('product_id', $request->product_id)->first();
        if ($inventory) {
            $inventory->current_stock += ($request->quantity - $oldQuantity);
            $inventory->save();
        }
    } else {
        // If the product was changed:
        // 1. Subtract from old product inventory
        $oldInventory = Inventory::where('product_id', $oldProductId)->first();
        if ($oldInventory) {
            $oldInventory->current_stock -= $oldQuantity;
            $oldInventory->save();
        }

        // 2. Add to new product inventory
        $newInventory = Inventory::where('product_id', $request->product_id)->first();
        if ($newInventory) {
            $newInventory->current_stock += $request->quantity;
            $newInventory->save();
        } else {
            // Create new inventory if not exists
            Inventory::create([
                'product_id' => $request->product_id,
                'current_stock'        => $request->quantity,
                'status'     => 1,
            ]);
        }
    }

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
}

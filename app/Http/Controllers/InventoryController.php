<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Inventory;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $productIdsInInventory = Inventory::pluck('product_id')->toArray();
        $products = Product::whereNotIn('id', $productIdsInInventory)
                        ->latest()
                        ->get();
        $inventories = Inventory::with('product')->get();
        return view('frontend.pages.inventory.index', compact('products','inventories'));
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
            'product_id' => 'required|exists:products,id',
            'opening_stock' => 'required|integer|min:0',
        ]);

        $inventory = new Inventory();
        $inventory->product_id = $request->product_id;
        $inventory->opening_stock = $request->opening_stock;
        $inventory->current_stock = $request->opening_stock;
        $inventory->notes = 'Opening stock entry';
        $inventory->save();

        return redirect()->back()->with('success', 'Product with opening stock added successfully.');
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
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}

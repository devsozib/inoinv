<?php

namespace App\Http\Controllers;

use App\Models\Vendor;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\Inventory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

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




public function reportIndex(Request $request)
{
    $query = Purchase::query();

    // Check if any filters are applied
    $hasFilters = $request->filled('vendor_id') || $request->filled('product_id') || $request->filled('from_date') || $request->filled('to_date');

    // Default to current month if no filters are applied
    if (!$hasFilters) {
        $query->whereBetween('created_at', [
            Carbon::now()->startOfMonth(),
            Carbon::now()->endOfMonth()
        ]);
    } else {
        if ($request->filled('vendor_id')) {
            $query->where('vendor_id', $request->vendor_id);
        }

        if ($request->filled('product_id')) {
            $query->where('product_id', $request->product_id);
        }

        if ($request->filled('from_date')) {
            $query->whereDate('created_at', '>=', $request->from_date);
        }

        if ($request->filled('to_date')) {
            $query->whereDate('created_at', '<=', $request->to_date);
        }
    }

    $purchases = $query
            ->selectRaw('product_id, SUM(quantity) as total_qty, SUM(total_price) as total_amount')
            ->groupBy('product_id')
            ->with('product') // Eager load product details
            ->get(); // You can add pagination if needed

    $products = Product::with('brand')->latest()->get();
    $vendors = Vendor::latest()->get();

    return view('frontend.pages.report.purchase.index', compact('purchases', 'products', 'vendors'));
}


    public function report(Request $request)
    {
         $request->all(); // For debugging purposes, you can remove this later
        $query = Purchase::query();
        
        // Apply filters
        if ($request->filled('vendor_id')) {
            $query->where('vendor_id', $request->vendor_id);
        }

        if ($request->filled('item_name')) {
            $query->where('product_id', $request->item_name);
        }

        if ($request->filled('from')) {
            $query->whereDate('created_at', '>=', $request->from);
        }

        if ($request->filled('to')) {
            $query->whereDate('created_at', '<=', $request->to);
        }
        // Group by product to get item-wise purchase data
        $purchases = $query
            ->selectRaw('product_id, SUM(quantity) as total_qty, SUM(total_price) as total_amount')
            ->groupBy('product_id')
            ->with('product') // Eager load product details
            ->get();
        
        $products = Product::latest()->get();
        $vendors = Vendor::latest()->get();

        return view('frontend.pages.report.purchase.index', compact('purchases', 'products', 'vendors', 'request'));
    }

}

<?php

namespace App\Http\Controllers;

use Input;
use Validator;
use Carbon\Carbon;
use App\Models\Sale;
use App\Models\SalesItem;
use App\Models\User;
use App\Models\Payment;
use App\Models\Product;
use App\Models\Inventory;
use App\Models\Service;
use Twilio\Rest\Client;
use App\Models\Customer;
use App\Models\DailySale;
use Illuminate\Http\Request;
use App\Mail\CreateSalesMail;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;

class SalesController extends Controller
{
    public function index(Request $request)
    {

        $services = Sale::join('customers', 'customers.id','sales.customer_id')->leftjoin('users','users.id','=','sales.sales_by');

        $defaultFilter = true;
        
        if ($request->from != "" && $request->to != "") {
            $from = date('Y-m-d 00:00:00', strtotime($request->from));
            $to = date('Y-m-d 23:59:59', strtotime($request->to));
            $services = $services->whereBetween('sales.created_at', [$from, $to]);
            $defaultFilter = false;
        }

        if ($request->sales_type != "") {
            if($request->sales_type=="paid"){
                $services = $services->where('sales.due_amount', '=', '0');
                $defaultFilter = false;
            }
            if($request->sales_type=="due"){
                $services = $services->where('sales.due_amount', '>', '0');
                $defaultFilter = false;
            }
        }

        if ($request->serach_by != "" && $request->key != "") {
           $services = $services->where('sales.'.$request->serach_by, 'like', '%' . $request->key . '%');
           $defaultFilter = false;
        }

        if($defaultFilter){
            $startOfMonth = date('Y-m-01 00:00:00');
            $endOfMonth = date('Y-m-t 23:59:59');
            $services = $services->whereBetween('sales.created_at', [$startOfMonth, $endOfMonth]);
        }

        $services = $services->select('sales.*','users.name as sales_by', 'customers.name', 'customers.phone','customers.address')
        ->orderBy('id','desc')->get();

        $users = lib_salesMan();
        if($request->search_for == 'pdf'){
            // return view('pdf.sales',compact('services','request'));
            $pdf = Pdf::loadView('pdf.sales', compact('services', 'request'))
                ->setPaper('A4', 'portrait');
            return $pdf->download('Sales.pdf');
        }


        //Report
        $todaysRevenue = Service::whereDate('created_at', Carbon::today())->where('status','1')->sum('bill');
        $thisWeeksRevenue = Service::whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->where('status','1')->sum('bill');
        $thisMonthsRevenue = Service::whereBetween('created_at', [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()])->where('status','1')->sum('bill');
        $thisYearsRevenue = Service::whereBetween('created_at', [Carbon::now()->startOfYear(), Carbon::now()->endOfYear()])->where('status','1')->sum('bill');
        $totalServiceDues = Service::where('status','1')->where('due_amount', '>', 0)->sum('due_amount');

        $todaysSalesRevenue = Sale::whereDate('created_at', Carbon::today())->where('status','1')->sum('bill');
        $thisWeeksSalesRevenue = Sale::whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->where('status','1')->sum('bill');
        $thisMonthsSalesRevenue = Sale::whereBetween('created_at', [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()])->where('status','1')->sum('bill');
        $thisYearsSalesRevenue = Sale::whereBetween('created_at', [Carbon::now()->startOfYear(), Carbon::now()->endOfYear()])->where('status','1')->sum('bill');
        $totalSalesDues = 0;

        $todaysDailySalesRevenue = DailySale::whereDate('date', Carbon::today())->where('status','1')->sum('total_amount');
        $thisWeeksDailySalesRevenue = DailySale::whereBetween('date', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->where('status','1')->sum('total_amount');
        $thisMonthsDailySalesRevenue = DailySale::whereBetween('date', [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()])->where('status','1')->sum('total_amount');
        $thisYearsDailySalesRevenue = DailySale::whereBetween('date', [Carbon::now()->startOfYear(), Carbon::now()->endOfYear()])->where('status','1')->sum('total_amount');

        $monthlyRevenue = Service::selectRaw('MONTH(created_at) as month, SUM(bill) as total')
        ->whereYear('created_at', Carbon::now()->year)
        ->where('status','1')
        ->groupBy('month')
        ->pluck('total', 'month')
        ->mapWithKeys(function ($total, $month) {
            $monthName = Carbon::createFromFormat('m', $month)->format('M');
            return [$monthName => $total];
        });

        $yearlyRevenue = Service::selectRaw('YEAR(created_at) as year, SUM(bill) as total')
        ->whereRaw('YEAR(created_at) >= YEAR(CURDATE()) - 9')
        ->where('status','1')
        ->groupBy('year')
        ->pluck('total', 'year');


        return view('frontend.pages.sales.index',compact('services','request','users','todaysRevenue','thisWeeksRevenue','thisMonthsRevenue','thisYearsRevenue','monthlyRevenue','yearlyRevenue','todaysSalesRevenue','thisWeeksSalesRevenue','thisMonthsSalesRevenue','thisYearsSalesRevenue','totalServiceDues','totalSalesDues','todaysDailySalesRevenue','thisWeeksDailySalesRevenue','thisMonthsDailySalesRevenue','thisYearsDailySalesRevenue'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $users  = User::get();
        $products = Product::with('latestPurchase')->where('status','1')->get();
        return view('frontend.pages.sales.create',compact('products','users'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        // return $request->all();
    
      
        $validated = $request->validate([
            'name' => 'required|string',
            'phone' => 'required|string',
            'address' => 'nullable|string',
            'product' => 'required|array',
            'product.*' => 'required|integer|exists:products,id',
            'qty' => 'required|array',
            'qty.*' => 'required|numeric|min:1',
            'unit_price' => 'required|array',
            'unit_price.*' => 'required|numeric|min:1',
            'discount' => 'required|numeric',
        ]);
        
        

        DB::beginTransaction();

        try {
            
            $customer = Customer::firstOrCreate(
                ['name' => $validated['name'], 'phone' => $validated['phone']],
                ['address' => $validated['address'] ?? null]
            );

            $sale = Sale::create([
                'order_no' => 'INV-' . strtoupper(uniqid()),
                'customer_id' => $customer->id,
                'bill' => 0,
                'discount' => 0,
                'payble' => 0,
                'sales_by' => auth()->id(),
                'status' => '1',
            ]);
            
            

            $totalBill = 0;

            $warranties = Product::wherein('id', $validated['product'])->pluct('warranty','id');

            foreach ($validated['product'] as $index => $productId) {
                $qty = $validated['qty'][$index];
                $unitPrice = $validated['unit_price'][$index];

                $total = $unitPrice * $qty;
                $totalBill += $total;

                SalesItem::create([
                    'order_id' => $sale->id,
                    'product_id' => $productId,
                    'unit_price' => $unitPrice,
                    'qty' => $qty,
                    'total_price' => $total,
                    'warranty' => isset($warranties[$productId]) ? $warranties[$productId] : 0,
                ]);

                $inventory = Inventory::where('product_id', $productId)->first();
                if($inventory){
                    $inventory->current_stock -= $qty;
                    $inventory->update();
                }
                
            }
            

            $discount = $validated['discount'];
            $payble = $totalBill - $discount;

            $sale->update([
                'bill' => $totalBill,
                'discount' => $discount,
                'payble' => $payble,
            ]);

            DB::commit();

            return redirect()->route('sales.invoice', $sale->id);

        } catch (\Exception $e) {
            // return $e;
            DB::rollBack();
            return redirect()->back()->with(['error' =>  $e->getMessage()]);
           
        }

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $sales = Sale::join('customers','customers.id','=','sales.customer_id')
                    ->where('sales.id',$id)
                    ->select('sales.*')
                    ->first();
        if(!$sales)abort(404);
        $users  = User::get();
        $products = Product::where('status','1')->get();

        $customer = Customer::where('id', $sales->customer_id)->first();
        if(!$customer)abort(404);

        $items = SalesItem::where('order_id',  $sales->id) ->get();

        return view('frontend.pages.sales.edit',compact('sales','products','items','customer'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {

        // return $request->all();
    
      
        $validated = $request->validate([
            'name' => 'required|string',
            'phone' => 'required|string',
            'address' => 'nullable|string',
            'product' => 'required|array',
            'product.*' => 'required|integer|exists:products,id',
            'qty' => 'required|array',
            'qty.*' => 'required|numeric|min:1',
            'unit_price' => 'required|array',
            'unit_price.*' => 'required|numeric|min:1',
            'discount' => 'required|numeric|min:0',
        ]);

        DB::beginTransaction();

        try {
            
            $customer = Customer::firstOrCreate(
                ['name' => $validated['name'], 'phone' => $validated['phone']],
                ['address' => $validated['address'] ?? null]
            );

            $sale = Sale::where('id', $id)->first();
            if(!$sale)return 'Sales not found.';


            $oldItems = SalesItem::where('order_id', $sale->id)->get();
            foreach ($oldItems as $item) {
                $inventory = Inventory::where('product_id', $item->product_id)->first();
                if ($inventory) {
                    $inventory->current_stock += $item->qty;
                    $inventory->update();
                }
            }

            SalesItem::where('order_id', $sale->id)->delete();

            $totalBill = 0;
            $warranties = Product::wherein('id', $validated['product'])->pluct('warranty','id');

            foreach ($validated['product'] as $index => $productId) {
                $qty = $validated['qty'][$index];
                $unitPrice = $validated['unit_price'][$index];

                $total = $unitPrice * $qty;
                $totalBill += $total;

                SalesItem::create([
                    'order_id' => $sale->id,
                    'product_id' => $productId,
                    'unit_price' => $unitPrice,
                    'qty' => $qty,
                    'total_price' => $total,
                    'warranty' => isset($warranties[$productId]) ? $warranties[$productId] : 0,
                ]);

                $inventory = Inventory::where('product_id', $productId)->first();
                if ($inventory) {
                    $inventory->current_stock -= $qty;
                    $inventory->update();
                }
            }

            $discount = $validated['discount'];
            $payble = $totalBill - $discount;

            $sale->update([
                'bill' => $totalBill,
                'discount' => $discount,
                'payble' => $payble,
            ]);

            DB::commit();



            return redirect()->route('sales.invoice', $sale->id);

        } catch (\Exception $e) {
            DB::rollBack();
            // return $e->getMessage();
            return redirect()->back()->with(['error' =>  $e->getMessage()]);
           
        }

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $service = Sale::where('id',$id)->first();
        if(!$service)abort(404);
        $service->delete();

        return redirect()->back()->with(['success' => getNotify(3)]);
    }

    public function makeInvoice(Request $request, $serviceId){
        $sales = Sale::where('id', $serviceId)->first();
        if(!$sales)abort(404);
        $customer = Customer::where('id',$sales->customer_id)->first();
        if(!$customer)abort(404);
        $items = SalesItem::join('products', 'products.id', 'sales_items.product_id')
        ->where('order_id',  $sales->id)
        ->select('sales_items.*','products.name','products.model')
        ->get();



        return view('frontend.pages.sales.invoice',compact('sales','items','customer'));
    }

    public function payments(Request $request){ 

        $payments = Payment::where('payment_for', 2);

        $defaultFilter = true;

        if ($request->from != "" && $request->to != "") {
            $from = date('Y-m-d 00:00:00', strtotime($request->from));
            $to = date('Y-m-d 23:59:59', strtotime($request->to));
            $payments = $payments->whereBetween('payments.created_at', [$from, $to]);
            $defaultFilter = false;
        }

        if ($request->payments_method != "") {
            $payments = $payments->where('payments.payment_method_id', $request->payments_method);
            $defaultFilter = false;
        }

        if($defaultFilter){
            $startOfMonth = date('Y-m-01 00:00:00');
            $endOfMonth = date('Y-m-t 23:59:59');
            $payments = $payments->whereBetween('payments.created_at', [$startOfMonth, $endOfMonth]);
        }

        $payments = $payments->get();

        if($request->search_for == 'pdf'){
            $pdf = Pdf::loadView('pdf.service_payments', compact('payments', 'request'))
                ->setPaper('A4', 'portrait');
            return $pdf->download('service Payments.pdf');
        }

        return view('frontend.pages.sales.payments',compact('payments','request'));
    }
}


//ALTER TABLE `products` ADD `price` INT NOT NULL DEFAULT '0' AFTER `model`; 
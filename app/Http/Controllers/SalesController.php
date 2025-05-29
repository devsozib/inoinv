<?php

namespace App\Http\Controllers;

use Input;
use Validator;
use Carbon\Carbon;
use App\Models\Sale;
use App\Models\User;
use App\Models\Payment;
use App\Models\Product;
use App\Models\Service;
use Twilio\Rest\Client;
use App\Models\Customer;
use App\Models\DailySale;
use Illuminate\Http\Request;
use App\Mail\CreateSalesMail;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;

class SalesController extends Controller
{
    public function index(Request $request)
    {

        $services = Sale::leftjoin('users','users.id','=','sales.sales_by');

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

        $services = $services->select('sales.*','users.name as sales_by')->orderBy('id','desc')->get();

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
        $totalSalesDues = Sale::where('due_amount', '>', 0)->sum('due_amount');

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
        $products = Product::where('status','1')->get();
        return view('frontend.pages.sales.create',compact('products','users'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
    
       $attributes = $request->all();
        $rules = [
            'name' => 'required',
            'email' => 'nullable|email',
            'country_code' => 'required',
            'phone' => 'required|numeric',
            'address' => 'nullable',
            'product_name' => 'required',
            'price' => 'required|numeric',
            'discount' => 'nullable|numeric',
            'qty' => 'required|numeric',
            'paid_amount' => 'nullable|numeric',
            'due_amount' => 'nullable|numeric',
            'payment_method_id' => [function ($attribute, $value, $fail) use ($request) {
                if ($request->paid_amount > 0 && !$value) {
                    $fail('The payment method is required when the paid amount is greater than 0.');
                }
            }],
            'sales_by' => 'required|numeric',
        ];
        $validation = Validator::make($attributes, $rules);
        if ($validation->fails()) {
            return redirect()->back()->with(['error' => getNotify(4)])->withErrors($validation)->withInput();
        }

        if(!is_numeric($request->product_name)){
            $product = new Product;
            $product->name = $request->product_name;
            $product->type = '2';
            $product->save();
        }else{
            $product = Product::where('id', $request->product_name)->first();
            if($product)$request->product_name =  $product->name;
        }

        $customerByPhone = Customer::where('phone', $request->phone)->first();
        $customerByEmail = Customer::where('email', $request->email)->first();
        if($request->email == "") $customerByEmail = null;
        $customer =  new Customer;

        if((!$customerByPhone && $customerByEmail)){
            $customer = $customerByEmail;
        }elseif(($customerByPhone && !$customerByEmail)){
            $customer = $customerByPhone;
        }elseif($customerByPhone && $customerByEmail && $customerByPhone->id == $customerByEmail->id){
            $customer = $customerByPhone;
        }elseif($customerByPhone && $customerByEmail && $customerByPhone->id != $customerByEmail->id){
            return redirect()->back()->with(['error' => 'The email is added for another customer.'])->withInput();
        }

        $customer->name = $request->name;
        if($request->email != "" )$customer->email = $request->email;
        $customer->country_code = $request->country_code;
        $customer->phone = $request->phone;
        $customer->address = $request->address;
        $customer->save();

        $service = new Sale;
        $service->customer_id = $customer->id;
        $service->name = $customer->name;
        $service->country_code = $request->country_code;
        $service->phone = $customer->phone;
        $service->email = $customer->email;
        $service->address = $customer->address;
        $service->product_name = $request->product_name;
        $service->price = $request->price??0;
        $service->qty = $request->qty??0;
        $service->total = max(0,$request->price * $request->qty);
        $service->discount = $request->discount??0;
        $service->bill = $service->total - $service->discount; // max(0,$request->price * $request->qty);
        $service->paid_amount = $request->paid_amount??0;
        $service->due_amount = max(0,$service->bill - $request->paid_amount);
        $service->sales_by = $request->sales_by;
        $service->save();

        if($request->paid_amount > 0){
            $payment = new Payment;
            $payment->payment_for = '2';
            $payment->customer_id = $customer->id;
            $payment->bill_id = $service->id;
            $payment->payment_method_id = $request->payment_method_id;
            $payment->amount = $request->paid_amount;
            $payment->save();
        }

        $service = Sale::where('id', $service->id)->first();
        $salesMans = lib_salesMan();
        if($request->email){
            Mail::to($request->email)->send(new CreateSalesMail($service));
        }

        if($request->phone){
            $twilio = new Client(env('TWILIO_SID'), env('TWILIO_AUTH_TOKEN'));

            $man = getArrayData($salesMans,$service->sales_by);

            $message  = "Quick Phone Fix N More\n";
            $message .= "7157 Ogontaz Ave, Philadelphia PA 19138\n";
            $message .= "Hotline: +234 901 791 9699\n\n";
            
            $message .= "Customer Info:\n";
            $message .= "Name: {$service->name}\n";
            $message .= "Phone: {$service->phone}\n";
            $message .= "Email: {$service->email}\n";
            $message .= "Address: {$service->address}\n\n";
            
            $message .= "Sales Info:\n";
            $message .= "Product: {$service->product_name}\n";
            $message .= "Price: \${$service->price}\n";
            $message .= "Qty: {$service->qty}\n";
            $message .= "Total: \${$service->bill}\n";
            $message .= "Paid: \${$service->paid_amount}\n";
            $message .= "Due: \${$service->due_amount}\n\n";
            
            $message .= "Date: " . now()->format('Y-m-d g:i A') . "\n\n";
            
            $message .= "Thank You. Please come again.";
            

            try{
                $twilio->messages->create(
                    $request->country_code . $request->phone,
                    [
                        'from' => env('TWILIO_PHONE_NUMBER'),
                        'body' => $message
                    ]
                );
            } catch (\Twilio\Exceptions\RestException $e) {}
        }

        // return view('frontend.pages.sales.invoice',compact('service'));

        return redirect()->back()->with(['success' => getNotify(1)]);

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
        $service = Sale::join('customers','customers.id','=','sales.customer_id')
                    ->where('sales.id',$id)
                    ->select('sales.*')
                    ->first();
        if(!$service)abort(404);
        $products = Product::where('status','1')->where('type','2')->get();
        $salesMans = lib_salesMan();

        return view('frontend.pages.sales.edit',compact('service','products','salesMans'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $service = Sale::where('id',$id)->first();
        if(!$service)abort(404);

        $attributes = $request->all();
        $rules = [
            'name' => 'required',
            'email' => 'nullable|email',
            'country_code' => 'required',
            'phone' => 'required|numeric',
            'address' => 'nullable',
            'product_name' => 'required',
            'price' => 'required|numeric',
            'discount' => 'nullable|numeric',
            'qty' => 'required|numeric',
            'sales_by' => 'required|numeric',
        ];
        $validation = Validator::make($attributes, $rules);
        if ($validation->fails()) {
            return redirect()->back()->with(['error' => getNotify(4)])->withErrors($validation)->withInput();
        }

        if(!is_numeric($request->product_name)){
            $product = new Product;
            $product->type = '2';
            $product->name = $request->product_name;
            $product->save();
        }else{
            $product = Product::where('id', $request->product_name)->first();
            if($product)$request->product_name =  $product->name;
        }

        $customerByPhone = Customer::where('phone', $request->phone)->first();
        $customerByEmail = Customer::where('email', $request->email)->first();
        if($request->email == "") $customerByEmail = null;
        $customer =  new Customer;

        if((!$customerByPhone && $customerByEmail)){
            $customer = $customerByEmail;
        }elseif(($customerByPhone && !$customerByEmail)){
            $customer = $customerByPhone;
        }elseif($customerByPhone && $customerByEmail && $customerByPhone->id == $customerByEmail->id){
            $customer = $customerByPhone;
        }elseif($customerByPhone && $customerByEmail && $customerByPhone->id != $customerByEmail->id){
            return redirect()->back()->with(['error' => 'The email is added for another customer.'])->withInput();
        }

        $customer->name = $request->name;
        if($request->email != "" )$customer->email = $request->email;
        $customer->country_code = $request->country_code;
        $customer->phone = $request->phone;
        $customer->address = $request->address;
        $customer->save();

        $service->customer_id = $customer->id;
        $service->name = $customer->name;
        $service->country_code = $request->country_code;
        $service->phone = $customer->phone;
        $service->email = $customer->email;
        $service->address = $customer->address;
        $service->product_name = $request->product_name;
        $service->price = $request->price??0;
        $service->qty = $request->qty??0;
        $service->total = max(0,$request->price * $request->qty);
        $service->discount = $request->discount??0;
        $service->bill = $service->total - $service->discount; // max(0,$request->price * $request->qty);
        $service->due_amount = max(0,$service->bill-$service->paid_amount);
        $service->sales_by = $request->sales_by;
        $service->save();

        return redirect()->back()->with(['success' => getNotify(2)]);

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
        $service = Sale::where('id', $serviceId)->first();
        if(!$service)abort(404);

        return view('frontend.pages.sales.invoice',compact('service'));
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

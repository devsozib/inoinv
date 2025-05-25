<?php

namespace App\Http\Controllers;

use Input;
use Validator;
use App\Models\Sale;
use App\Models\User;
use App\Models\Payment;
use App\Models\Product;
use Twilio\Rest\Client;
use App\Models\Customer;
use App\Models\DailyExpense;
use Illuminate\Http\Request;
use App\Mail\CreateSalesMail;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Mail;

class ExpenseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
         $request;
        $dailyExpense = DailyExpense::leftjoin('users','users.id','=','daily_expenses.assign_person');

        $defaultFilter = true;
        
        if ($request->from != null && $request->to != null) {
            $from = date('Y-m-d 00:00:00', strtotime($request->from));
            $to = date('Y-m-d 23:59:59', strtotime($request->to));
            $dailyExpense = $dailyExpense->whereBetween('daily_expenses.created_at', [$from, $to]);
            $defaultFilter = false;
        }

        if ($request->spend_method != null) {
                $dailyExpense = $dailyExpense->where('daily_expenses.spend_method', $request->spend_method);
                $defaultFilter = false;
            
        }

        if ($request->assign_person != null) {
                $dailyExpense = $dailyExpense->where('daily_expenses.assign_person', $request->assign_person);
                $defaultFilter = false;
            
        }

        if ($request->key != null) {
           $dailyExpense = $dailyExpense->where('daily_expenses.purpose_of_expense', 'like', '%' . $request->key . '%');
           $defaultFilter = false;
        }

        if($defaultFilter){
            $startOfMonth = date('Y-m-01 00:00:00');
            $endOfMonth = date('Y-m-t 23:59:59');
            $dailyExpense = $dailyExpense->whereBetween('daily_expenses.created_at', [$startOfMonth, $endOfMonth]);
        }

        $dailyExpense = $dailyExpense->select('daily_expenses.*','users.name as assign_by')->orderBy('id','desc')->get();

        // return $dailyExpense;
        $users =  User::leftJoin('model_has_roles', 'model_has_roles.model_id', '=', 'users.id')
        ->leftJoin('roles', 'model_has_roles.role_id', '=', 'roles.id')
        ->select('users.*', 'roles.name as roleName')
        ->orderBy('users.id', 'desc')
        ->get();
        if($request->search_for == 'pdf'){
            return view('pdf.daily_expense',compact('dailyExpense','request'));
            $pdf = Pdf::loadView('pdf.sales', compact('dailyExpense', 'request'))
                ->setPaper('A4', 'portrait');
            return $pdf->download('DailyExpense.pdf');
        }

        return view('frontend.pages.expense.index',compact('dailyExpense','request','users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $users = User::leftJoin('model_has_roles', 'model_has_roles.model_id', '=', 'users.id')
            ->leftJoin('roles', 'model_has_roles.role_id', '=', 'roles.id')
            ->select('users.*', 'roles.name as roleName')
            ->orderBy('users.id', 'desc')
            ->get();
        return view('frontend.pages.expense.create',compact('users'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $attributes = $request->all();
        $rules = [
            'date' => 'required|date',
            'purpose_of_expense' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0.01',
            'spend_method' => 'required|in:cash,card,bank_transfer',
            'assign_person' => 'required|exists:users,id',
            'to_payment' => 'required'           
        ];

        $validation = Validator::make($attributes, $rules);
        if ($validation->fails()) {
            return redirect()->back()->with(['error' => getNotify(4)])->withErrors($validation)->withInput();
        }
        
        // Store data using the save() method
        $expense = new DailyExpense();
        $expense->date = $request->date;
        $expense->purpose_of_expense = $request->purpose_of_expense;
        $expense->amount = $request->amount;
        $expense->spend_method = $request->spend_method;
        $expense->assign_person = $request->assign_person;
        $expense->to_payment = $request->to_payment;
        $expense->save(); // Save the data in the database

        return redirect()->back()->with(['success' => getNotify(1)]);
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
        $expense = DailyExpense::where('id', $id)->first();        
        $users = User::leftJoin('model_has_roles', 'model_has_roles.model_id', '=', 'users.id')
            ->leftJoin('roles', 'model_has_roles.role_id', '=', 'roles.id')
            ->select('users.*', 'roles.name as roleName')
            ->orderBy('users.id', 'desc')
            ->get();
        return view('frontend.pages.expense.edit',compact('users','expense'));

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $attributes = $request->all();
        $rules = [
            'date' => 'required|date',
            'purpose_of_expense' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0.01',
            'spend_method' => 'required|in:cash,card,bank_transfer',
            'assign_person' => 'required|exists:users,id',
            'to_payment' => 'required',      
        ];
    
        $validation = Validator::make($attributes, $rules);
        if ($validation->fails()) {
            return redirect()->back()->with(['error' => getNotify(4)])->withErrors($validation)->withInput();
        }
    
        // Find the existing expense entry
        $expense = DailyExpense::findOrFail($id);
    
        // Update the expense details
        $expense->date = $request->date;
        $expense->purpose_of_expense = $request->purpose_of_expense;
        $expense->amount = $request->amount;
        $expense->spend_method = $request->spend_method;
        $expense->assign_person = $request->assign_person;
        $expense->to_payment = $request->to_payment;
        $expense->save(); // Save the updated data in the database
    
        return redirect()->back()->with(['success' => getNotify(2)]);
    }
    

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $expense = DailyExpense::findOrFail($id);
        $expense->delete();
        return redirect()->back()->with(['success' => getNotify(3)]);
    }
}

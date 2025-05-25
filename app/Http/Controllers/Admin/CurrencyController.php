<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\Currency;
use Illuminate\Http\Request;

class CurrencyController extends Controller
{

    public function index()
    {
        $coupons = Currency::get();
        return view('admin.pages.product.currency.index', compact('coupons'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'symbol' => 'required|unique:currencies',
            'type' => 'required|numeric',
            'status' => 'required',
        ]);

        $coupon = new Currency();
        $coupon->name = $request->input('name');
        $coupon->symbol = $request->input('symbol');
        $coupon->type = $request->input('type');
        $coupon->status = $request->input('status');
        $coupon->save();

        return redirect()->back();
    }

    public function update(Request $request, $id)
    {

        $request->validate([
            'name' => 'required',
            'symbol' => 'required',
            'type' => 'required',
            'status' => 'required',
        ]);

        $coupon = Currency::findOrFail($id);
        $coupon->name = $request->input('name');
        $coupon->symbol = $request->input('symbol');
        $coupon->type = $request->input('type');
        $coupon->status = $request->input('status');
        $coupon->update();
        return redirect()->back();
    }

    public function getCurrency()
    {
        return Currency::where('status', '1')->first();
    }
}

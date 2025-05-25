<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use App\Models\Admin\Coupon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CouponController extends Controller
{

    public function index()
    {
        $coupons = Coupon::get();
        return view('admin.pages.product.coupon.index', compact('coupons'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|unique:coupons',
            'discount' => 'required|numeric|min:0',
            'discount_type' => 'required|boolean',
            'quantity' => 'nullable|integer|min:1',
            'expires_at' => 'nullable|date|after:today',
            'status' => 'required',
        ]);

        $coupon = new Coupon();
        $coupon->code = $request->input('code');
        $coupon->discount = $request->input('discount');
        $coupon->discount_type = $request->input('discount_type');
        $coupon->quantity = $request->input('quantity');
        $coupon->expires_at = $request->input('expires_at');
        $coupon->status = $request->input('status');
        $coupon->save();

        return redirect()->back();
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'code' => 'required|unique:coupons,code,' . $id,
            'discount' => 'required|numeric|min:0',
            'discount_type' => 'required|boolean',
            'quantity' => 'nullable|integer|min:1',
            'expires_at' => 'nullable|date|after:today',
            'status' => 'required',
        ]);
        $coupon = Coupon::findOrFail($id);

        $coupon->code = $request->input('code');
        $coupon->discount = $request->input('discount');
        $coupon->discount_type = $request->input('discount_type');
        $coupon->quantity = $request->input('quantity');
        $coupon->expires_at = $request->input('expires_at');
        $coupon->status = $request->input('status');
        $coupon->update();
        return redirect()->back();
    }

    public function getCoupon()
    {
        $coupon = Coupon::where('expires_at', '>', Carbon::now())
            ->where('status', '1')
            ->first();
        return response()->json(['coupon' => $coupon]);
    }
    public function checkCoupon(Request $request)
    {
        $couponCode = $request->coupon;
        $coupon = Coupon::where('code', $couponCode)
            ->where('expires_at', '>', Carbon::now())
            ->where('status', '1')
            ->first();
        return response()->json(['coupon' => $coupon]);
    }
}

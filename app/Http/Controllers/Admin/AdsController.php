<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Str;
use App\Models\Admin\HomeAd;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class AdsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $ad = HomeAd::first();
        return view('admin.pages.content.ads.index', compact('ad'));
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
        $attributes = $request->all();
        $rules = [
            'first_ad' => [
                'image',
                'mimes:jpg,png,jpeg,gif',
                'max:2048',
                'dimensions:width=375 ,height=374,',
            ],
            'second_ad' => [
                'image',
                'mimes:jpg,png,jpeg,gif',
                'max:2048',
                'dimensions:width=375 ,height=374,',
            ],
        ];
        $validation = Validator::make($attributes, $rules);
        $messages = [
            'first_ad.image' => 'The uploaded file must be an image.',
            'first_ad.mimes' => 'The image must be a file of type: jpg, png, jpeg, gif.',
            'first_ad.max' => 'The image size should not exceed 2MB.',
            'first_ad.dimensions' => 'The image dimensions should be 375x374 pixels.',
            'second_ad.image' => 'The uploaded file must be an image.',
            'second_ad.mimes' => 'The image must be a file of type: jpg, png, jpeg, gif.',
            'second_ad.max' => 'The image size should not exceed 2MB.',
            'second_ad.dimensions' => 'The image dimensions should be 375x374 pixels.',
        ];
        $validation->setCustomMessages($messages);
        if ($validation->fails()) {
            return back()->with(['error_code' => 'update'])->withErrors($validation)->withInput();
        } else {

            $slider = HomeAd::first();

            // Handling the image upload
            if ($request->hasFile('first_ad')) {
                if ($slider->image) {
                    unlink(public_path('frontend/assets/images/ads/' . $slider->image));
                }
                $image = $request->file('first_ad');
                $destinationPath = public_path('frontend/assets/images/ads/');
                $first_ad = now()->format('YmdHis') . '_' . Str::random(10) . '.' . $image->getClientOriginalExtension();
                $image->move($destinationPath, $first_ad);
                $slider->first_ad = $first_ad;
            }

            if ($request->hasFile('second_ad')) {
                if ($slider->image) {
                    unlink(public_path('frontend/assets/images/ads/' . $slider->image));
                }
                $image = $request->file('second_ad');
                $destinationPath = public_path('frontend/assets/images/ads/');
                $second_ad = now()->format('YmdHis') . '_' . Str::random(10) . '.' . $image->getClientOriginalExtension();
                $image->move($destinationPath, $second_ad);
                $slider->second_ad = $second_ad;
            }
            $slider->save();

            // Flash success message
            session()->flash('sweet_alert', [
                'type' => 'success',
                'title' => 'Success!',
                'text' => 'Ads updated successfully',
            ]);

            // Redirect to the slider index page with a success message
            return redirect()->route('home-ad.index')->with('success', 'Ads updated successfully');
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }


    public function getAds()
    {
        return HomeAd::first();
    }
}

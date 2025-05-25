<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\Admin\Category;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Models\Admin\HomePage;
use App\Models\Admin\NavItem;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::where('for_book_or_product','2')->orderBy('order_by')->get();
        return view("admin.pages.product.category", compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'category' => 'required|string|max:255',
            'order_by' => 'required',
            'status' => 'required',
            'image' => [
                'image',
                'mimes:jpg,png,jpeg,gif,webp',
                'max:2048',
                'dimensions:width=370 ,height=260,',
            ],
        ]);
        $name = "";
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $destinationPath = public_path('frontend/assets/images/service/');
            $name = now()->format('YmdHis') . '_' . Str::random(10) . '.' . $image->getClientOriginalExtension();
            $image->move($destinationPath, $name);
        }
        $slug = Str::slug($request->input('category'));
        $category = new Category;
        $category->for_book_or_product = '2';
        $category->name = $request->category;
        $category->search_string = $request->category.' '.getBanglish($request->category);
        $category->order_by = $request->order_by;
        $category->image = $name;
        $category->status = $request->status;        
        $category->slug = $slug;       
        $category->created_by = auth()->user()->id;
        $category->save();
        session()->flash('sweet_alert', [
            'type' => 'success',
            'title' => 'Success!',
            'text' => 'Category Added success',
        ]);
        return redirect()->route('categories.index');
    }

    public function update(Request $request, $id)
    {
        $attributes = $request->all();
        $rules = [
            'category' => 'required|string|max:255',
            'order_by' => 'required',
            'status' => 'required',
            'uimage' => [
                'image',
                'mimes:jpg,png,jpeg,gif,webp',
                'max:2048',
                'dimensions:width=370 ,height=260,',
            ],
        ];
        $validation = Validator::make($attributes, $rules);
        $messages = [
            'category.required' => 'The category field is required.',
            'order_by.required' => 'The order by field is required.',
            'status.required' => 'The status field is required.',
            'uimage.image' => 'The uploaded file must be an image.',
            'uimage.mimes' => 'The image must be a file of type: jpg, png, jpeg, gif.',
            'uimage.max' => 'The image size should not exceed 2MB.',
            'uimage.dimensions' => 'The image dimensions should be 370x260 pixels.',
        ];
        $validation->setCustomMessages($messages);
        if ($validation->fails()) {
            return back()->with(['error_code' => $id])->withErrors($validation)->withInput();
        } else {
            $category = Category::find($id);
            if ($category) {
            // Handling the image upload
            if ($request->hasFile('uimage')) {
                if ($category->image) {
                        unlink(public_path('frontend/assets/images/category/' . $category->image));
                    }
                    $image = $request->file('uimage');
                    $destinationPath = public_path('frontend/assets/images/category/');
                    $name = now()->format('YmdHis') . '_' . Str::random(10) . '.' . $image->getClientOriginalExtension();
                    $image->move($destinationPath, $name);
                    $category->image = $name;
                }
                $slug = Str::slug($request->input('category'));                        
                $category->name = $request->category;
                $category->order_by = $request->order_by;
                $category->status = $request->status;        
                $category->slug = $slug;
                $category->updated_by = auth()->user()->id;
                $category->save();
            }
            session()->flash('sweet_alert', [
                'type' => 'success',
                'title' => 'Success!',
                'text' => 'Category update success',
            ]);            
            return redirect()->route('categories.index')->with('success', 'Category updated successfully');
        }        
    }

    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        $category->delete();
        session()->flash('sweet_alert', [
            'type' => 'success',
            'title' => 'Success!',
            'text' => 'Category Delete success',
        ]);
        return redirect()->route('categories.index');
    }
    //For get data with axios

    public function getCategories()
    {
        return $categories = Category::where('status', '1')->orderBy('order_by')->get();
    }

    public function showOnHome(Request $request){
        $id = $request->id;
        $category = Category::where('id', $id)->first();
        if(!$category){
            return response()->json([
                'type' => 'error',
                'message' => getNotify(10),
            ]);
        }

        $homePage = HomePage::where('for_book_or_product', '2')
        ->where('item_type','1')->where('item_id',$category->id)->first();
        if(!$homePage){
            $homePage = new HomePage;
            $homePage->for_book_or_product = '2';
            $homePage->item_type = '1';
            $homePage->item_id = $category->id;
            $homePage->title = $category->name;
            $homePage->more_button_title = "আরও দেখুন";
            $homePage->created_by = auth()->user()->id;
            $homePage->save();
        }

        if($request->homeView == "true"){
            $category->is_home_view = '1';
            $homePage->status = '1';
        }
        else{
            $category->is_home_view = '0';
            $homePage->status = '0';
        }
        $category->updated_by = auth()->user()->id;
        $category->update();
        $homePage->updated_by = auth()->user()->id;
        $homePage->update();
        return response()->json([
            'type' => 'success',
            'message' => getNotify(2),
        ]);

    }

    function showOnNav(Request $request){
        $id = $request->id;
        $category = Category::where('id', $id)->first();
        if(!$category){
            return response()->json([
                'type' => 'error',
                'message' => getNotify(10),
            ]);
        }

        $homePage = NavItem::where('for_book_or_product', '2')
        ->where('item_type','1')->where('item_id',$category->id)->first();
        if(!$homePage){
            $homePage = new NavItem;
            $homePage->for_book_or_product = '2';
            $homePage->item_type = '1';
            $homePage->item_id = $category->id;
            $homePage->title = $category->name;
            $homePage->created_by = auth()->user()->id;
            $homePage->save();
        }

        if($request->navView == "true"){
            $category->is_nav_view = '1';
            $homePage->status = '1';
        }
        else{
            $category->is_nav_view = '0';
            $homePage->status = '0';
        }
        $category->updated_by = auth()->user()->id;
        $category->update();
        $homePage->updated_by = auth()->user()->id;
        $homePage->update();
        return response()->json([
            'type' => 'success',
            'message' => getNotify(2),
        ]);
    }
}

<?php

namespace App\Http\Controllers\Admin;

use Input;
use Validator;
use App\Models\Admin\Size;
use Illuminate\Support\Str;
use App\Models\Admin\Toping;
use Illuminate\Http\Request;
use App\Models\Admin\Product;
use App\Models\Admin\Category;
use App\Models\Admin\ProductTag;
use App\Models\Admin\OptionTitle;
use App\Models\Admin\ProductSize;
use App\Models\SizeVsTopingPrice;
use App\Models\Admin\ProductImage;
use Illuminate\Support\Facades\DB;
use App\Models\Admin\ProductOption;
use App\Models\Admin\ProductToping;
use App\Http\Controllers\Controller;
use App\Models\Admin\ProductOptionTopping;
use Carbon\Carbon;
use App\Models\Admin\SubCategory;
use Illuminate\Support\Facades\File;
use App\Models\Admin\Subject;
use App\Models\Admin\Writer;
use App\Models\Admin\Publisher;

class BookController extends Controller
{
    public function index(Request $request){
        $products = Product::where('products.is_book_or_product','1')->where('is_package','0')
        ->select('products.*')->orderBy('products.id', 'desc')->get();
        $categories = lib_book_category();
        $subjects = lib_subject();
        $writers = lib_writer();
        $publishers = lib_publisher();
        return view('admin.pages.book.index', compact('products', 'categories', 'subjects', 'writers', 'publishers'));
    }

    public function create()
    {
        $categories = Category::where('for_book_or_product', '1')->where('status', '1')->get();
        $subCategories = SubCategory::where('for_book_or_product', '1')->where('status', '1')->get();
        $tmp = [];
        foreach($subCategories as $subCategory){
            $tmp[$subCategory->category_id][] = $subCategory;
        }
        $subCategories = $tmp;

        $subjects = Subject::where('status','1')->get();
        $writers = Writer::where('status','1')->get();
        $publishers = Publisher::where('status','1')->get();
        return view('admin.pages.book.create', compact('categories','subCategories','subjects','writers','publishers'));
    }

    public function store(Request $request){
        
        $attributes = $request->all();
        $rules = [
            'name' => 'required',
            'category' => 'nullable',
            'sub_category' => 'nullable',
            'price' => 'numeric|required',
            'offer_price' => 'nullable|numeric',
            'status' => 'required',
            'subject' => 'required',
            'writer' => 'required',
            'publisher' => 'required',
        ];
        $validation = Validator::make($attributes, $rules);
        if ($validation->fails()) {
            return redirect()->back()->with(['error' => getNotify(4), 'error_code' => 'Add'])->withErrors($validation)->withInput();
        }


        $imageName = "";
        if ($request->hasFile('images')) {
            $image = $request->file('images');
            $destinationPath = public_path('frontend/product_images/');
            $imageName = now()->format('YmdHis') . '_' . Str::random(10) . '.' . $image->getClientOriginalExtension();
            $image->move($destinationPath, $imageName);
        }

        $pdfName = "";
        if ($request->hasFile('pdf')) {
            $pdf = $request->file('pdf');
            $destinationPath = public_path('frontend/product_pdf/');
            $pdfName = now()->format('YmdHis') . '_' . Str::random(10) . '.' . $pdf->getClientOriginalExtension();
            $pdf->move($destinationPath, $pdfName);
        }
        // Create a new product instance
        $product = new Product([
            'name' => $request->input('name'),
            'search_string' => $request->input('name').' '.getBanglish($request->input('name')),
            'category_id' => implode(',', $request->category),
            'sub_category_id' => $request->sub_category,
            'description' => $request->input('description'),
            'image' => $imageName,
            'pdf' => $pdfName,
            'price' => $request->price,
            'offer_price' => $request->offer_price??0,
            'offer_from' => $request->offer_from,
            'offer_to' => $request->offer_to,
            'subject_id' => implode(',', $request->subject),
            'writer_id' => implode(',', $request->writer),
            'publisher_id' => implode(',', $request->publisher),
            'is_book_or_product' => '1',
            'status' => $request->input('status'),
            'created_by' => auth()->user()->id,
        ]);

        $product->save();

        return redirect()->back()->with(['success' => getNotify(1)]);
    }

    public function edit(string $id)
    {
        $product = Product::where('id', $id)->first();
        $categories = Category::where('for_book_or_product', '1')->where('status', '1')->get();
        $subCategories = SubCategory::where('for_book_or_product', '1')->where('status', '1')->get();
        $tmp = [];
        foreach($subCategories as $subCategory){
            $tmp[$subCategory->category_id][] = $subCategory;
        }
        $subCategories = $tmp;

        $subjects = Subject::where('status','1')->get();
        $writers = Writer::where('status','1')->get();
        $publishers = Publisher::where('status','1')->get();

        if(!$product){
            return redirect()->back()->with(['error' => getNotify(10)])->withInput();
        }

        return view('admin.pages.book.edit', compact('categories', 'product', 'id', 'subCategories','subjects','writers','publishers'));
    }

    public function update(Request $request, string $id){
        $attributes = $request->all();
        $rules = [
            'name' => 'required',
            'category' => 'nullable',
            'sub_category' => 'nullable|numeric',
            'price' => 'numeric|required',
            'offer_price' => 'nullable|numeric',
            'status' => 'required',
            'subject' => 'required',
            'writer' => 'required',
            'publisher' => 'required',
        ];
        $validation = Validator::make($attributes, $rules);
        if ($validation->fails()) {
            return redirect()->back()->with(['error' => getNotify(4), 'error_code' => 'edit'])->withErrors($validation)->withInput();
        }


        $product = Product::where('id', $id)->first();
        if(!$product){
            return redirect()->back()->with(['error' => getNotify(10)])->withInput();
        }

        $imageName = "";
        if ($image = $request->file('images')) {
            if ($product->image != NULL) {
                $imagePath = public_path('frontend/product_images/' . $product->image);
                if (File::exists($imagePath)) {
                    unlink($imagePath);
                }
            }
            $destinationPath = public_path('frontend/product_images/');
            $imageName = date('YmdHis') . "." . $image->getClientOriginalExtension();
            $image->move($destinationPath, $imageName);
        } else {
            $imageName = $product->image;
        }


        $pdfName = "";
        if ($pdf = $request->file('pdf')) {
            if ($product->pdf != NULL) {
                $pdfPath = public_path('frontend/product_pdf/' . $product->pdf);
                if (File::exists($pdfPath)) {
                    unlink($pdfPath);
                }
            }
            $destinationPath = public_path('frontend/product_pdf/');
            $pdfName = date('YmdHis') . "." . $pdf->getClientOriginalExtension();
            $pdf->move($destinationPath, $pdfName);
        } else {
            $pdfName = $product->image;
        }
        $product->name = $request->input('name');
        $product->category_id = implode(',', $request->category);
        $product->sub_category_id = $request->sub_category;
        $product->description = $request->input('description');
        $product->image = $imageName;
        $product->pdf = $pdfName;
        $product->price = $request->price;
        $product->offer_price = $request->offer_price??0;
        $product->offer_from = $request->offer_from;
        $product->offer_to = $request->offer_to;
        $product->subject_id = implode(',', $request->subject);
        $product->writer_id = implode(',', $request->writer);
        $product->publisher_id = implode(',', $request->publisher);
        $product->is_book_or_product = '1';
        $product->status = $request->input('status');
        $product->updated_by = auth()->user()->id;
        $product->update();

        return redirect()->back()->with(['success' => getNotify(2)]);

    }

    public function destroy(string $id)
    {
        $product = Product::where('id', $id)->first();
        if(!$product){
            return redirect()->back()->with(['error' => getNotify(10)])->withInput();
        }
        if ($product->image != NULL) {
            $imagePath = public_path('frontend/product_images/' . $product->image);
            if (File::exists($imagePath)) {
                unlink($imagePath);
            }
        }
        $product->delete();
        return redirect()->back()->with(['success' => getNotify(3)]);
    }

    public function package(Request $request){
        $products = Product::where('products.is_book_or_product','1')->where('is_package','1')
        ->select('products.*')->orderBy('products.id', 'desc')
        ->get()->map(function($product) {
            $bookNames = Product::whereIn('id', explode(',', $product->package_item_ids))
                ->pluck('name')
                ->unique()
                ->implode(','); 
            $product->setAttribute('book_names', $bookNames);
    
            return $product;
        });;

        return view('admin.pages.book.package.index', compact('products'));
    }

    public function packageCraete(Request $request){
        $products = Product::where('products.is_book_or_product','1')
        ->where('is_package','0')
        ->where('status','1')
        ->get();
        return view('admin.pages.book.package.create', compact('products'));
    }

    public function packageStore(Request $request){
        $attributes = $request->all();
        $rules = [
            'name' => 'required',
            'books' => 'required',
            'price' => 'numeric|required',
            'offer_price' => 'nullable|numeric',
            'status' => 'required',
        ];
        $validation = Validator::make($attributes, $rules);
        if ($validation->fails()) {
            return redirect()->back()->with(['error' => getNotify(4), 'error_code' => 'Add'])->withErrors($validation)->withInput();
        }


        $imageName = "";
        if ($request->hasFile('images')) {
            $image = $request->file('images');
            $destinationPath = public_path('frontend/product_images/');
            $imageName = now()->format('YmdHis') . '_' . Str::random(10) . '.' . $image->getClientOriginalExtension();
            $image->move($destinationPath, $imageName);
        }
        // Create a new product instance
        $product = new Product([
            'name' => $request->input('name'),
            'description' => $request->input('description'),
            'image' => $imageName,
            'price' => $request->price,
            'offer_price' => $request->offer_price??0,
            'offer_from' => $request->offer_from,
            'offer_to' => $request->offer_to,
            'is_book_or_product' => '1',
            'is_package' => '1',
            'package_item_ids' => implode(',', $request->books),
            'status' => $request->input('status'),
            'created_by' => auth()->user()->id,
        ]);

        $product->save();

        return redirect()->back()->with(['success' => getNotify(1)]);
    }

    public function packageEdit(Request $request, $id){
        $product = Product::where('id', $id)->first();
        if(!$product || $product->is_package=='0'){
            return redirect()->back()->with(['error' => getNotify(10)]);
        }

        $products = Product::where('products.is_book_or_product','1')
        ->where('is_package','0')
        ->where('status','1')
        ->get();

        return view('admin.pages.book.package.edit', compact('products','product'));
    }

    public function packageUpdate(Request $request, $id){

        $attributes = $request->all();
        $rules = [
            'name' => 'required',
            'books' => 'required',
            'price' => 'numeric|required',
            'offer_price' => 'nullable|numeric',
            'status' => 'required',
        ];
        $validation = Validator::make($attributes, $rules);
        if ($validation->fails()) {
            return redirect()->back()->with(['error' => getNotify(4), 'error_code' => 'Add'])->withErrors($validation)->withInput();
        }

        $product = Product::where('id', $id)->first();
        if(!$product || $product->is_package=='0'){
            return redirect()->back()->with(['error' => getNotify(10)]);
        }

        $imageName = "";
        if ($image = $request->file('images')) {
            if ($product->image != NULL) {
                $imagePath = public_path('frontend/product_images/' . $product->image);
                if (File::exists($imagePath)) {
                    unlink($imagePath);
                }
            }
            $destinationPath = public_path('frontend/product_images/');
            $imageName = date('YmdHis') . "." . $image->getClientOriginalExtension();
            $image->move($destinationPath, $imageName);
        } else {
            $imageName = $product->image;
        }

        $product->name = $request->input('name');
        $product->description = $request->input('description');
        $product->image = $imageName;
        $product->price = $request->price;
        $product->offer_price = $request->offer_price??0;
        $product->offer_from = $request->offer_from;
        $product->offer_to = $request->offer_to;
        $product->is_book_or_product = '1';
        $product->is_package = '1';
        $product->package_item_ids = implode(',', $request->books);
        $product->status = $request->input('status');
        $product->updated_by = auth()->user()->id;
        $product->update();

        return redirect()->back()->with(['success' => getNotify(2)]);
    }
    public function packageDelete(string $id)
    {
        $product = Product::where('id', $id)->first();
        if(!$product || $product->is_package=='0'){
            return redirect()->back()->with(['error' => getNotify(10)])->withInput();
        }
        if ($product->image != NULL) {
            $imagePath = public_path('frontend/product_images/' . $product->image);
            if (File::exists($imagePath)) {
                unlink($imagePath);
            }
        }
        $product->delete();
        return redirect()->back()->with(['success' => getNotify(3)]);
    }


}

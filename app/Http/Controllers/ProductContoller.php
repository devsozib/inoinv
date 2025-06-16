<?php

namespace App\Http\Controllers;

use Validator;
use Carbon\Carbon;
use App\Models\Sale;
use App\Models\Service;
use App\Models\DailySale;
use App\Models\Admin\Size;
use App\Models\Admin\Brand;
use Illuminate\Support\Str;
use App\Models\Admin\Toping;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Admin\Category;
use App\Models\Admin\ProductTag;
use App\Models\Admin\OptionTitle;
use App\Models\Admin\ProductSize;
use App\Models\Admin\SubCategory;
use App\Models\SizeVsTopingPrice;
use App\Models\Admin\ProductImage;
use Illuminate\Support\Facades\DB;
use App\Models\Admin\ProductOption;
use App\Models\Admin\ProductToping;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use App\Models\Admin\ProductOptionTopping;

class ProductContoller extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $products = Product::with('brand')->latest()->get();
        $brands = Brand::where('status', '1')->latest()->get();
        return view('frontend.pages.product.index', compact('products', 'brands'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::where('for_book_or_product', '2')->where('status', '1')->get();
        $subCategories = SubCategory::where('for_book_or_product', '2')->where('status', '1')->get();
        $tmp = [];
        foreach($subCategories as $subCategory){
            $tmp[$subCategory->category_id][] = $subCategory;
        }
        $subCategories = $tmp;
        $brands = Brand::where('status', '1')->get();

        return view('admin.pages.product.create', compact('categories','subCategories','brands'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $attributes = $request->all();
        $rules = [
            'name' => 'required',
            'model_name' => 'required',
            'status' => 'required',
            'warranty' => 'required',
            'brand_id' => 'required',
        ];
        $validation = Validator::make($attributes, $rules);
        if ($validation->fails()) {
            return redirect()->back()->with(['error' => getNotify(4), 'error_code' => 'edit'])->withErrors($validation)->withInput();
        }

        $product = new Product;
        $product->name = $request->name;
        $product->model = $request->model_name;
        $product->status = $request->status;
        $product->brand_id = $request->brand_id;
        $product->warranty = $request->warranty;
        $product->save();
    
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
        $product = Product::where('id', $id)->first();
        $categories = Category::where('for_book_or_product', '2')->where('status', '1')->get();
        $subCategories = SubCategory::where('for_book_or_product', '2')->where('status', '1')->get();
        $tmp = [];
        foreach($subCategories as $subCategory){
            $tmp[$subCategory->category_id][] = $subCategory;
        }
        $subCategories = $tmp;

        $brands = Brand::where('status','1')->get();

        if(!$product){
            return redirect()->back()->with(['error' => getNotify(10)])->withInput();
        }

        return view('admin.pages.product.edit', compact('categories', 'product', 'id', 'subCategories','brands'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $attributes = $request->all();
        $rules = [
            'name' => 'required',
            'model_name' => 'required',
            'status' => 'required',
            'brand_id' => 'required',
            'warranty' => 'required',
        ];
        $validation = Validator::make($attributes, $rules);
        if ($validation->fails()) {
            return redirect()->back()->with(['error' => getNotify(4), 'error_code' => 'edit'])->withErrors($validation)->withInput();
        }

        $product = Product::where('id', $id)->first();
        if(!$product){
            return redirect()->back()->with(['error' => getNotify(10)])->withInput();
        }
        $product->name = $request->name;
        $product->model = $request->model_name;
        $product->status = $request->status;
        $product->brand_id = $request->brand_id;
        $product->warranty = $request->warranty;
        $product->update();

        return redirect()->back()->with(['success' => getNotify(2)]);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $product = Product::where('id', $id)->first();
        if(!$product) abort(404);

        $product->delete();
        return redirect()->back()->with('success', 'Product delete successfully');
    }

    public function size($id)
    {
        $productSizes = ProductSize::join('sizes', 'sizes.id', '=', 'product_sizes.size_id')
            ->where('product_id', $id)
            ->select('product_sizes.*', 'sizes.name')->get();
        return view('admin.pages.product.product_size', compact('id', 'productSizes'));
    }

    public function getProductSize(Request $request){

        $productSizes = ProductSize::join('sizes', 'sizes.id', '=', 'product_sizes.size_id')
            ->where('product_sizes.product_id', $request->id)
            ->where('product_sizes.status', '1')
            ->select('product_sizes.*', 'sizes.name')->get();
        $product = Product::where('id', $request->id)->first();

        return ['product' => $product, 'productSizes' => $productSizes];
    }

    public function createProductSize($id)
    {
        $product = Product::where('id', $id)->first();
        if(!$product){
            return redirect()->back()->with(['error' => getNotify(10)])->withInput();
        }
        $sizes = Size::where('status', '1')->get();
        return view('admin.pages.product.create_product_size', compact('id', 'sizes','product'));
    }

    public function editProductSize($id)
    {
        $productSize = ProductSize::find($id);
        if(!$productSize){
            return redirect()->back()->with(['error' => getNotify(10)]);
        }
        $product = Product::where('id', $productSize->product_id)->first();
        if(!$product){
            return redirect()->back()->with(['error' => getNotify(10)]);
        }

        $sizes = Size::where('status', '1')->get();
        if ($productSize) {
            return view('admin.pages.product.edit_product_size', compact('productSize', 'sizes', 'product'));
        }
    }

    public function storeSize(Request $request)
    {
        $request->validate([
            'product_id' => 'required|numeric',
            'size_id' => 'required|numeric',
            'price' => 'nullable|numeric',
            'status' => 'required|in:0,1',
            // 'description' => 'required',
            'offer_price' => 'nullable|numeric',
            'offer_from' => 'nullable|date',
            'offer_to' => 'nullable|date',
            'quantity' => 'numeric|nullable'
        ]);

        $product = Product::where('id', $request->product_id)->first();
        if(!$product){
            return redirect()->back()->with(['error' => getNotify(10)]);
        }

        if($product->is_size_wise_price == '1' && $request->price==""){
            return redirect()->back()->with(['error' => 'Price field is required.', 'error_code' => 'edit'])->withInput();
        }

        $imageName = "";
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $destinationPath = public_path('frontend/product_images/');
            $imageName = now()->format('YmdHis') . '_' . Str::random(10) . '.' . $image->getClientOriginalExtension();
            $image->move($destinationPath, $imageName);
        }

        $size = new ProductSize;
        $size->product_id = $request->product_id;
        $size->size_id = $request->size_id;
        $size->price = $request->price??0;
        $size->offer_price = $request->offer_price;
        $size->offer_from = $request->offer_from;
        $size->offer_to = $request->offer_to;
        $size->quantity = $request->quantity;
        $size->description = $request->description;
        $size->status = $request->status;
        $size->created_by = auth()->user()->id;
        $size->image = $imageName;
        $size->save();

        return redirect()->back()->with(['success' => getNotify(1)]);
    }
    //Assign topings
    public function topings($id)
    {
        $productTopings = ProductToping::join('topings', 'topings.id', '=', 'product_topings.toping_id')->where('product_topings.product_id', $id)->select('topings.*', 'product_topings.id as topId')->get();
        $topings = Toping::where('status', '1')->get();
        return view('admin.pages.product.topings', compact('productTopings', 'topings', 'id'));
    }

    public function storeToping(Request $request)
    {
        $request->validate([
            'product_id' => 'required|numeric',
            'toping' => 'required|numeric',
            'status' => 'required|in:0,1',
        ]);

        $checkExist = ProductToping::where('product_id', $request->product_id)->where('toping_id', $request->toping)->first();
        if (!$checkExist) {
            $size = new ProductToping();
            $size->product_id = $request->product_id;
            $size->toping_id = $request->toping;
            $size->status = $request->status;
            $size->created_by = auth()->user()->id;
            $size->save();
            session()->flash('sweet_alert', [
                'type' => 'success',
                'title' => 'Success!',
                'text' => 'Product toping added success',
            ]);
        } else {
            session()->flash('sweet_alert', [
                'type' => 'warning',
                'title' => 'warning!',
                'text' => 'Already exists this toping! Try another',
            ]);
        }


        return redirect()->back();
    }

    public function updateSize(Request $request, $id)
    {
        // return $request->all();
        $request->validate([
            'product_id' => 'required|numeric',
            'size_id' => 'required|numeric',
            'price' => 'nullable|numeric',
            'status' => 'required|in:0,1',
            'offer_price' => 'nullable|numeric',
            'offer_from' => 'nullable|date',
            'offer_to' => 'nullable|date',
            'quantity' => 'numeric|nullable'
        ]);

        $product = Product::where('id', $request->product_id)->first();
        if(!$product){
            return redirect()->back()->with(['error' => getNotify(10)]);
        }

        if($product->is_size_wise_price != '1' && $request->price==""){
            return redirect()->back()->with(['error' => 'Price field is required.', 'error_code' => 'edit'])->withInput();
        }
        
        $size = ProductSize::find($id);
        if ($size) {

            $imageName = $size->image;
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $destinationPath = public_path('frontend/product_images/');
                $imageName = now()->format('YmdHis') . '_' . Str::random(10) . '.' . $image->getClientOriginalExtension();
                $image->move($destinationPath, $imageName);
                if ($size->image)
                    unlink(public_path('frontend/product_images/' . $size->image));
            }


            $size->size_id = $request->size_id;
            $size->price = $request->price??0;
            $size->offer_price = $request->offer_price;
            $size->offer_from = $request->offer_from;
            $size->offer_to = $request->offer_to;
            $size->quantity = $request->quantity;
            $size->status = $request->status;
            $size->description = $request->description;
            $size->image = $imageName;
            $size->updated_by = auth()->user()->id;
            $size->update();

            return redirect()->back()->with(['success' => getNotify(2)]);
        }
    }

    public function deleteProductSize($id)
    {
        $productSizes = ProductSize::find($id);
        if ($productSizes)
            $productSizes->delete();
        session()->flash('sweet_alert', [
            'type' => 'success',
            'title' => 'Success!',
            'text' => 'Product Size delete success',
        ]);
        return redirect()->back();
    }

    public function getProducts()
    {
        $categories = Category::leftJoin('products', 'categories.id', '=', 'products.category_id')
            ->select(
                'categories.id as category_id',
                'categories.order_by as OrderBY',
                'categories.name as category_name',
                'products.id as product_id',
                'products.name as product_name',
                'products.description as description',
                'products.image as image',
            )
            ->where('products.status', '1')
            ->orderBy('categories.order_by')
            ->orderBy('products.id')
            ->get();

            $currentDate = Carbon::today();
            foreach($categories as $key => $category){
                $productSizes = ProductSize::where('product_id',$category->product_id)->get();
                $offerMin = null;
                $regularMin = null;
                foreach($productSizes as $size){
                    if ($size->offer_from <= $currentDate && $currentDate <= $size->offer_to) {
                        $offerPrice = $size->offer_price;
                        if($offerMin==null)$offerMin = $offerPrice;
                        $$offerMin =  min($offerMin,$offerPrice);
                    }
                    $price = $size->price;
                    if($regularMin==null) $regularMin = $price;
                    $regularMin = min($regularMin, $price);
                }
                $categories[$key]->calculated_offer_price = ($offerMin<$regularMin ? $offerMin : null);
                $categories[$key]->min_price = $regularMin;
            }


        // return $categories;
        // Organize the result into a more usable format
        $groupedCategories = [];
        $categories = $categories->sortBy('order_by');
        foreach ($categories as $category) {
            // $category->min_price = null;
            // $category->calculated_offer_price = null;
            $categoryId = $category->category_id;
            if (!isset($groupedCategories[$categoryId])) {
                $groupedCategories[$categoryId] = [
                    'category_id' => $category->category_id,
                    'category_name' => $category->category_name,
                    'order_by' => $category->OrderBY,
                    'products' => [],
                ];
            }
            if ($category->product_id) {
                $groupedCategories[$categoryId]['products'][] = [
                    'id' => $category->product_id,
                    'name' => $category->product_name,
                    'description' => $category->description,
                    'image' => $category->image,
                    'min_price' => $category->min_price,
                    'calculated_offer_price' => $category->calculated_offer_price,
                ];
            }
        }
        $productAllTages = ProductTag::pluck('tag_name', 'id');
        return [$groupedCategories, $productAllTages];
    }

    public function getProductDetails(Request $request)
    {
        $productId = $request->query('id');
        $product = Product::where('id', $productId)->first();
        $productSizes = ProductSize::join('sizes', 'sizes.id', '=', 'product_sizes.size_id')
            ->where('product_id', $productId)
            ->where('product_sizes.status', '1')
            ->select('product_sizes.*', 'sizes.name', 'sizes.id as size_id')
            ->get();


        $currentDate = Carbon::today();
        $maxPrice = $productSizes->max('price');
        $minPrice = $productSizes->min('price');
        $tem = [];

        foreach ($productSizes as $row) {
            if ($row->offer_from <= $currentDate && $currentDate <= $row->offer_to) {
                $row->price = $row->offer_price;
            }
            $tem[$row->id] = $row;
        }
        $productSizes = $tem;
        $productTopings = ProductToping::join('topings', 'topings.id', '=', 'product_topings.toping_id')
            ->where('product_topings.product_id', $productId)
            ->where('product_topings.status', '1')
            ->select('topings.*')
            ->get();
        $favoritToppingsIds = [];
        foreach ($productTopings as $toping) {
            $favoritToppingsIds[$toping->id] = $toping->id;
        }


        $tem = [];
        foreach ($productTopings as $row) {
            $tem[$row->id] = $row;
        }
        $productTopings = $tem;

        $allTopings = Toping::where('status', '1')->get();

        $tem = [];
        foreach ($allTopings as $row) {
            $tem[$row->id] = $row;
        }
        $allTopings = $tem;

        $moreTopings = Toping::whereNotIn('id', $favoritToppingsIds)->where('status', '1')->get();

        $tem = [];
        foreach ($moreTopings as $row) {
            $tem[$row->id] = $row;
        }
        $moreTopings = $tem;

        $sizeVsTopings = SizeVsTopingPrice::get();
        $bindData = [];
        foreach ($sizeVsTopings as $item) {
            $bindData[$item->toping_id][$item->size_id] = $item->price;
        }
        $sizeVsTopings = $bindData;

        $maxMin = [$minPrice, $maxPrice];

        $productTages = ProductTag::where('pro_id', $productId)->get()->toArray();

        $options = ProductOption::join('product_option_toppings as option_topping', 'option_topping.product_option_id', '=', 'product_options.id')
            ->join('option_titles', 'option_titles.id', '=', 'product_options.title_id')
            ->where('product_options.product_id', $productId)
            ->select('option_topping.*', 'product_options.title_id', 'product_options.type', 'product_options.free_qty', 'option_titles.name')->get();

        $temp = [];
        foreach ($options as $option) {
            $option->type = strtolower($option->type);
            $temp[$option->product_option_id]['details']['title'] = $option->name;
            $temp[$option->product_option_id]['details']['freeQty'] = $option->free_qty;
            $temp[$option->product_option_id]['options'][] = $option;
        }
        $productOptions = $temp;


        return response()->json([$product, $productSizes, $productTopings, $maxMin, $allTopings, $moreTopings, $sizeVsTopings, $productTages, $productOptions]);
    }


    public function getPopularProducts()
    {
        return $topSellingProducts = \DB::table('products')
            ->join('order_items', 'products.id', '=', 'order_items.product_id')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->leftJoin('product_sizes', function ($join) {
                $join->on('products.id', '=', 'product_sizes.product_id')
                    ->whereRaw('NOW() BETWEEN product_sizes.offer_from AND product_sizes.offer_to');
            })
            ->select(
                'products.id',
                'products.name',
                'products.image',
                \DB::raw('COUNT(orders.id) as total_orders'),
                \DB::raw('(SELECT MIN(price) FROM product_sizes WHERE product_sizes.product_id = products.id) as min_price'),
                'product_sizes.offer_price as calculated_offer_price'
            )
            ->groupBy('products.id', 'products.name', 'products.image', 'product_sizes.offer_price')
            ->orderBy('total_orders', 'desc')
            ->limit(10)
            ->get();
    }

    public function getRelatedProduct(Request $request)
    {
        $product_ids = $request->product_ids;
        $product_ids = explode(",", $product_ids);
        $catIds = Product::whereIn("id", $product_ids)->pluck('category_id');
        $products = Product::whereIn('category_id', $catIds)->where('status', '1')->take(10)->get();

        $proData = [];
        foreach ($products as $pro) {
            $proData[] = [
                'id' => $pro->id,
                'name' => $pro->name,
                'image' => asset("frontend/product_images/$pro->image"),
            ];
        }

        return $proData;
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin\Menu;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\Admin\HomePage;
use App\Models\Admin\NavItem;

class MenuController extends Controller
{
    public function index(){
        $menus = Menu::where('status', '1')->get();
        return view('admin.pages.menu.index', compact('menus'));
    }

    public function update(Request $request, $id){
        $menu = Menu::where('id', $id)->first();
        if(!$menu){ return redirect()->back()->with(['error_code' => $id]);}
        $menu->name = $request->name;
        $menu->update();
        return redirect()->back()->with('success', 'Menu updated successfully');
    }



    public function showOnHome(Request $request){
        $id = $request->id;
        $menu = Menu::where('id', $id)->first();
        if(!$menu){
            return response()->json([
                'type' => 'error',
                'message' => getNotify(10),
            ]);
        }
        $for_book_or_product = null;
        if($id == 4) $for_book_or_product = 1;
        $homePage = HomePage::where('for_book_or_product', $for_book_or_product)
        ->where('item_type','6')->where('item_id',$menu->id)->first();
        if(!$homePage){
            $homePage = new HomePage;
            $homePage->for_book_or_product = $for_book_or_product;
            $homePage->item_type = '6';
            $homePage->item_id = $menu->id;
            $homePage->title = $menu->name;
            $homePage->more_button_title = "আরও দেখুন";
            $homePage->created_by = auth()->user()->id;
            $homePage->save();
        }
        if($request->homeView == "true"){
            $menu->is_home_view = '1';
            $homePage->status = '1';
        }
        else{
            $menu->is_home_view = '0';
            $homePage->status = '0';
        }
        $menu->updated_by = auth()->user()->id;
        $menu->update();
        $homePage->updated_by = auth()->user()->id;
        $homePage->update();
        return response()->json([
            'type' => 'success',
            'message' => getNotify(2),
        ]);

    }

    function showOnNav(Request $request){
        $id = $request->id;
        $menu = Menu::where('id', $id)->first();
        if(!$menu){
            return response()->json([
                'type' => 'error',
                'message' => getNotify(10),
            ]);
        }
        $for_book_or_product = null;
        if($id == 4) $for_book_or_product = 1;
        $homePage = NavItem::where('for_book_or_product', $for_book_or_product)
        ->where('item_type','6')->where('item_id',$menu->id)->first();
        if(!$homePage){
            $homePage = new NavItem;
            $homePage->for_book_or_product = $for_book_or_product;
            $homePage->item_type = '6';
            $homePage->item_id = $menu->id;
            $homePage->title = $menu->name;
            $homePage->created_by = auth()->user()->id;
            $homePage->save();
        }
        if($request->navView == "true"){
            $menu->is_nav_view = '1';
            $homePage->status = '1';
        }
        else{
            $menu->is_nav_view = '0';
            $homePage->status = '0';
        }
        $menu->updated_by = auth()->user()->id;
        $menu->update();
        $homePage->updated_by = auth()->user()->id;
        $homePage->update();
        return response()->json([
            'type' => 'success',
            'message' => getNotify(2),
        ]);
    }
}

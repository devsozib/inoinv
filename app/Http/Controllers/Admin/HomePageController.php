<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin\HomePage;
use App\Models\Admin\NavItem;
use Illuminate\Support\Facades\DB;

class HomePageController extends Controller
{
    public function index(){
        $items = HomePage::where('status', '1')
                        ->select(
                            'home_pages.*',
                            DB::raw("CASE
                                WHEN home_pages.item_type = '1' THEN (
                                    SELECT categories.name
                                    FROM categories
                                    WHERE categories.id = home_pages.item_id
                                    LIMIT 1
                                )
                                WHEN home_pages.item_type = '2' THEN (
                                    SELECT brands.name
                                    FROM brands
                                    WHERE brands.id = home_pages.item_id
                                    LIMIT 1
                                )
                                WHEN home_pages.item_type = '3' THEN (
                                    SELECT subjects.name
                                    FROM subjects
                                    WHERE subjects.id = home_pages.item_id
                                    LIMIT 1
                                )
                                WHEN home_pages.item_type = '4' THEN (
                                    SELECT writers.name
                                    FROM writers
                                    WHERE writers.id = home_pages.item_id
                                    LIMIT 1
                                )
                                WHEN home_pages.item_type = '5' THEN (
                                    SELECT publishers.name
                                    FROM publishers
                                    WHERE publishers.id = home_pages.item_id
                                    LIMIT 1
                                )
                                WHEN home_pages.item_type = '6' THEN (
                                    SELECT menus.name
                                    FROM menus
                                    WHERE menus.id = home_pages.item_id
                                    LIMIT 1
                                )
                            END AS name")
                        )
                        ->orderBy('order_by', 'asc')
                        ->get();
    
        return view('admin.pages.settings.home_page.index', compact('items'));
    }

    public function navItem(){
        $items = NavItem::where('status', '1')
        ->select(
            'nav_items.*',
            DB::raw("CASE
                WHEN nav_items.item_type = '1' THEN (
                    SELECT categories.name
                    FROM categories
                    WHERE categories.id = nav_items.item_id
                    LIMIT 1
                )
                WHEN nav_items.item_type = '2' THEN (
                    SELECT brands.name
                    FROM brands
                    WHERE brands.id = nav_items.item_id
                    LIMIT 1
                )
                WHEN nav_items.item_type = '3' THEN (
                    SELECT subjects.name
                    FROM subjects
                    WHERE subjects.id = nav_items.item_id
                    LIMIT 1
                )
                WHEN nav_items.item_type = '4' THEN (
                    SELECT writers.name
                    FROM writers
                    WHERE writers.id = nav_items.item_id
                    LIMIT 1
                )
                WHEN nav_items.item_type = '5' THEN (
                    SELECT publishers.name
                    FROM publishers
                    WHERE publishers.id = nav_items.item_id
                    LIMIT 1
                )
                WHEN nav_items.item_type = '6' THEN (
                    SELECT menus.name
                    FROM menus
                    WHERE menus.id = nav_items.item_id
                    LIMIT 1
                )
            END AS name")
        )
        ->orderBy('order_by', 'asc')
        ->get();

        return view('admin.pages.settings.home_page.navItem', compact('items'));
    }

    public function updatePriyority(Request $request){
        $ids = $request->ids;
        $ids = json_decode($ids, true);
        foreach($ids as $id => $priyority){
            if($priyority != ""){
                $home = HomePage::where('id', $id)->first();
                if($home){
                    $home->order_by = $priyority;
                    $home->updated_by = auth()->user()->id;
                    $home->update();
                }
            }
        }

        return response()->json([
            'type' => 'success',
            'message' => getNotify(2),
        ]);
    }

    public function updateNaveItemPriyority(Request $request){
        $ids = $request->ids;
        $ids = json_decode($ids, true);
        foreach($ids as $id => $priyority){
            if($priyority != ""){
                $home = NavItem::where('id', $id)->first();
                if($home){
                    $home->order_by = $priyority;
                    $home->updated_by = auth()->user()->id;
                    $home->update();
                }
            }
        }

        return response()->json([
            'type' => 'success',
            'message' => getNotify(2),
        ]);
    }

    public function updateViewType(Request $request){
        $home = HomePage::where('id', $request->id)->first();
        if(!$home){
            return response()->json([
                'type' => 'error',
                'message' => getNotify(10),
            ]);
        }
        $home->view_type =  $request->type;
        $home->updated_by = auth()->user()->id;
        $home->update();
        return response()->json([
            'type' => 'success',
            'message' => getNotify(2),
        ]);
    }

    public function updateTitle(Request $request){
        $home = HomePage::where('id', $request->id)->first();
        if(!$home){
            return response()->json([
                'type' => 'error',
                'message' => getNotify(10),
            ]);
        }
        $home->title =  $request->title;
        $home->updated_by = auth()->user()->id;
        $home->update();
        return response()->json([
            'type' => 'success',
            'message' => getNotify(2),
        ]);
    }

    public function updateNavItemTitle(Request $request){
        $home = NavItem::where('id', $request->id)->first();
        if(!$home){
            return response()->json([
                'type' => 'error',
                'message' => getNotify(10),
            ]);
        }
        $home->title =  $request->title;
        $home->updated_by = auth()->user()->id;
        $home->update();
        return response()->json([
            'type' => 'success',
            'message' => getNotify(2),
        ]);
    }

    public function updateMoreButtonTitle(Request $request){
        $home = HomePage::where('id', $request->id)->first();
        if(!$home){
            return response()->json([
                'type' => 'error',
                'message' => getNotify(10),
            ]);
        }
        $home->more_button_title =  $request->more_button_title;
        $home->updated_by = auth()->user()->id;
        $home->update();
        return response()->json([
            'type' => 'success',
            'message' => getNotify(2),
        ]);
    }
}

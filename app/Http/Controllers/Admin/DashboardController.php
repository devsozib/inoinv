<?php

namespace App\Http\Controllers\Admin;

use Auth;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function index(Request $request){
        $user = Auth::user();
        $assignedRoles = $user->getRoleNames()['0'];
        // // Get the permissions of the user
        $permissions = $user->getAllPermissions(); 
        if($assignedRoles != 'Customer'){
            return view('admin/index'); 
        }else{
            Auth::logout();
            return redirect('/');
        }
    }
    public function create(){
        return "fd";
    }
}

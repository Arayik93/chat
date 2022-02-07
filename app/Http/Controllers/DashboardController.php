<?php


namespace App\Http\Controllers;


use App\Models\User;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;

class DashboardController extends BaseController
{
    public function dashboard(){
        $users = User::where('id','<>',Auth::id())->get();
        $rooms = Auth::user()->rooms ?? [];
        return view("dashboard")->with(['users' => $users,'rooms' => $rooms]);
    }

}

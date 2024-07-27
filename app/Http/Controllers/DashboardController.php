<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Consultations;

class DashboardController extends Controller
{
    public function index(){
        $consultations=Consultations::all();
        return view('user/dashboard',compact('consultations'));
    }
}

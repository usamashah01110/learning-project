<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MainController extends Controller
{
    public function home(){
        $data =  "This is a home page";

        return view('index',compact('data'));
    }

    public function portfolio($id){

        $data =  "This is a portfolio page";

        return view('portfolio',compact('data'));
    }
}

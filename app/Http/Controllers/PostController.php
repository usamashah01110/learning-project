<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PostController extends Controller
{
    public function index(){
        return view('admin.posts');
    }

    public function create(){
        return view('admin.createpost');
    }

    public function store(Request $request){
        dd($request->tags);
    }
}

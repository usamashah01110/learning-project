<?php

namespace App\Http\Controllers;

use App\Models\POST;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function index(){
        $posts = Post::all();
        return view('admin.posts',compact('posts'));
    }

    public function create(){
        return view('admin.createpost');
    }

    public function edit($id){
        $post = POST::find($id);
        return view('admin.edit',compact('post'));
    }

    public function store(Request $request){

        $post = Post::create($request->all());

        return redirect('/posts');

    }

    public function delete($id){
      $post = POST::find($id);
      $post->delete();

      return redirect()->back();
    }
}

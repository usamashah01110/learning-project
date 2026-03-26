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

    public function store(Request $request){
//        $title = $request->title;
//        $description = $request->description;
//        $tags= $request->tags;
//        $date = $request->date;
//        $status = $request->status;
//        $type = $request->type;
//
//        $post = new Post();
//        $post->title = $title;
//        $post->description = $description;
//        $post->tags = $tags;
//        $post->date = $date;
//        $post->status = $status;
//        $post->type = $type;
//        $post->save();
//
//        return redirect('/posts');


        $post = Post::create($request->all());

        return redirect('/posts');

    }
}

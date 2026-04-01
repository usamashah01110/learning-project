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

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'tags' => 'nullable|string|max:255',
            'date' => 'nullable|date_format:Y-m-d H:i:s',
            'status' => 'nullable|boolean',
            'type' => 'nullable|in:post,comment',
        ]);

        $post = Post::create($validated);

        return redirect('/posts');

    }

    public function delete($id){
      $post = POST::find($id);
      $post->delete();

      return redirect()->back();
    }
}

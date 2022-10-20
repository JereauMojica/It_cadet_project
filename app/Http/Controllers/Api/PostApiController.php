<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Post;

class PostApiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
         //return Post::orderBy('id', 'desc')->paginate(5);
         return Post::with('company_info' , 'department_info', 'attachment')->orderBy('id', 'desc')->paginate(5);
    }
    

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
       // return $request->all();
        $request->validate([
            'name' => 'required',
            'title' => 'required',
            'detail' => 'required',
            'company_id' => 'required',
            'department_id' => 'required',
            'attachment' => 'mimes:jpeg,bmp,png,gif,tiff,svg,pdf|max:10240'

        ]);
        $path = $request->file('attachment')->store('attachments', 'public');
;
        $post = Post::create($request->all());
        $post->attachment()->create([
            'path' => $path,
        ]);
        return $post->load('company_info', 'department_info' , 'attachment');//prod with type
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Post $post)
    {
        $request->validate([
            'name' => 'required',
            'title' => 'required',
            'detail' => 'required',

        ]);

        $post->name = $request->name;
        $post->title = $request->title;
        $post->detail = $request->detail;
        //$post->type= $request->type;
        $post->save();

        return $post;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Post $post)
    {
        $post->delete();
        return $post;
    }
}

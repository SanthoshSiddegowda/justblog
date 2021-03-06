<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\post;
use DB;

class postcontroller extends Controller
{

  public function __construct()
  {
      $this->middleware('auth', ['except' => ['index','show']]);
  }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //$posts=post::orderBy('title','asc')->get();
        //return post::where('title','post two')->get();
        //$posts= DB::select('select * from posts');
        //$posts=post::all();
        //$posts=post::orderBy('title','asc')->get();
        $posts=post::orderBy('title','desc')->Paginate(5);
        return view('posts.index')->with('posts',$posts);
        //return view('posts.index');
    }

    /*
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('posts.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request,[
          'title'=>'required',
          'body'=>'required'
        ]);


        //create Posts
        $post= new Post;
        $post->title = $request->input('title');
        $post->body = $request->input('body');
        $post->user_id = auth()->user()->id;
        $post->save();

        return redirect('/posts')->with('success','post created');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $post= post::find($id);
        return view('posts.show')->with('post',$post);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
      $post= post::find($id);

      if(auth()->user()->id !== $post->user_id){
          return redirect('/posts')->with('error','Unauthorized page');
      }
      return view('posts.edit')->with('post',$post);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
      $this->validate($request,[
        'title'=>'required',
        'body'=>'required'
      ]);


      //create Posts
      $post= Post::find($id);
      $post->title = $request->input('title');
      $post->body = $request->input('body');

      $post->save();

      return redirect('/posts')->with('success','post updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $post=Post::find($id);
        $post->delete();

          return redirect('/posts')->with('success','post deleted');


    }
}

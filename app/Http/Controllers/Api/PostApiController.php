<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Post;

class PostApiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $post = Post::latest()->take(5)->get();
        return response()->json([
            'status' => true,
            'message' => 'Get Posts Success',
            'post' => $post
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
{
    try {
            $validator = validator($request->all(), [
        'title' => 'required',
        'content' => 'required',
        'category_id' => 'required',
        'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
    ]);

    if ($validator->fails()) {
        return response()->json([
            'status' => false,
            'message' => "Validation failed",
            'errors' => $validator->errors()
        ], 409);
    }

    $post = new Post;
    $post->user_id = $request->user()->id;
    $post->title = $request->title;
    $post->content = $request->content;
    $post->category_id = $request->category_id;

    if ($request->hasFile('photo')) {
        $photoPath = $request->file('photo')->store('post_photos', 'public');
        $post->photo = $photoPath;
    } else {
        $post->photo = null;
    }

    $post->save();

    return response()->json([
        'status' => true,
        'message' => "Post created successfully",
        'post' => $post
    ], 201);
    } catch (\Exception $e) {
         return response()->json([
        'status' => false,
        'message' => "Post creation failed",
        'error' => $e->getMessage() // This will show exact error
    ], 500);
    }
}


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $post = Post::find($id);
        return  response()->json([
            'status' => true,
            'message' => 'Post Detail',
            'post' => $post
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $post = Post::find($id);
        $post->delete();
        return response()->json([
            'status' => true,
            'message'=> "Dlete post succeffully."
        ], 200);
    }
}

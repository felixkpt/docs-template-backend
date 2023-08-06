<?php

namespace App\Http\Controllers\Admin\Posts\Post;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Dummy data representing a list of posts
        $posts = [
            [
                'id' => 1,
                'title' => 'Post 1',
                'content' => 'This is the content of Post 1.',
                'created_at' => '2023-07-25 10:00:00',
                'updated_at' => '2023-07-25 12:30:00',
            ],
            [
                'id' => 2,
                'title' => 'Post 2',
                'content' => 'This is the content of Post 2.',
                'created_at' => '2023-07-26 11:00:00',
                'updated_at' => '2023-07-26 14:45:00',
            ],
            // Add more posts as needed...
        ];

        return response()->json([
            'status' => 'success',
            'message' => 'Posts retrieved successfully',
            'results' => $posts,
        ], 200);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return response()->json([
            'status' => 'success',
            'message' => 'Show the form for creating a new post',
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Code to store the newly created post in the database

        return response()->json([
            'status' => 'success',
            'message' => 'Post created successfully',
            'results' => [
                'id' => 3,
                'title' => $request->input('title'),
                'content' => $request->input('content'),
                'created_at' => now()->toDateTimeString(),
                'updated_at' => now()->toDateTimeString(),
            ],
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // Dummy data representing a single post with the given ID
        $post = [
            'id' => $id,
            'title' => 'Post ' . $id,
            'content' => 'This is the content of Post ' . $id . '.',
            'created_at' => '2023-07-27 09:30:00',
            'updated_at' => '2023-07-27 11:45:00',
        ];

        return response()->json([
            'status' => 'success',
            'message' => 'Post retrieved successfully',
            'results' => $post,
        ], 200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        return response()->json([
            'status' => 'success',
            'message' => 'Show the form for editing post ' . $id,
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Code to update the post with the given ID in the database

        return response()->json([
            'status' => 'success',
            'message' => 'Post updated successfully',
            'results' => [
                'id' => $id,
                'title' => $request->input('title'),
                'content' => $request->input('content'),
                'updated_at' => now()->toDateTimeString(),
            ],
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Code to delete the post with the given ID from the database

        return response()->json([
            'status' => 'success',
            'message' => 'Post deleted successfully',
        ], 200);
    }
}

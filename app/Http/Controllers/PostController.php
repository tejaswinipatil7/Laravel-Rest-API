<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use Illuminate\Support\Facades\Storage;
use App\Services\PostService;


class PostController extends Controller
{
    protected $postService;

    public function __construct(PostService $postService)
    {
        $this->postService = $postService;
    }

    public function index()
    {
        $posts = $this->postService->getAllPosts();
        return view('posts.index', compact('posts'));
    }

    public function show($id)
    {
        $post = $this->postService->getPostById($id);
        return view('posts.show', compact('post'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'file' => 'nullable|file|mimes:jpg,png,pdf|max:2048',          
        ]);       
      
        // Handle the base64 file upload
        $filePath = null;
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $fileName = time() . '.' . $file->getClientOriginalExtension();
             $filePath = 'uploads/' . $fileName; // Desired path and file name
            // Store the file in the public disk
            $fileContent = file_get_contents($file->getRealPath());
            Storage::disk('public')->put($filePath, $fileContent);
            $post = Post::create([
                    'title' => $request->input('title'),
                    'content' => $request->input('content'),
                    'file_path' => $filePath,
                    'user_id' => $request->user()->id, // Associate the post with the authenticated user
                ]);                
            return response()->json([
                'message' => 'File uploaded successfully',
                'path' => $filePath
            ]);
        }

        //BELOW CODE IS FOR SAVE BASE64 STRING 

        //filePath = null;
        // if ($request->has('file')) {
        //     // Extract the base64 string and decode it
        //     $fileData = $request->file;
        //     $fileParts = explode(',', $fileData);
        //     $fileMime = explode(';', explode(':', $fileParts[0])[1])[0]; // Extract MIME type

        //     // Decode the base64 string
        //     $fileContent = base64_decode($fileParts[1]);

        //     // Create a unique file name
        //     $fileName = uniqid() . '.' . explode('/', $fileMime)[1];
            
        //     // Store the file
        //     $filePath = 'uploads/' . $fileName;
        //     Storage::disk('public')->put($filePath, $fileContent);
        // }
       
       
        // $post = Post::create([
        //     'title' => $request->title,
        //     'content' => $request->content,
        //     'file_path' => $filePath,
        //     'user_id' => $request->user()->id, // Associate the post with the authenticated user
        // ]);

        // return response()->json([
        //     'message' => 'Post created successfully.',
        //     'post' => $post,
        // ], 201);
    }
    

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Post $post)
    {
        $validated = $request->validate([
            'title' => 'sometimes|string|max:255',
            'content' => 'sometimes|string',
        ]);

        $post->update($validated);

        return response()->json($post);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        $post->delete();

        return response()->json(null, 204);
    }
}

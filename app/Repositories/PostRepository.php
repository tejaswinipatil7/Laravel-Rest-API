<?php

namespace App\Repositories;

use App\Models\Post;

class PostRepository implements PostRepositoryInterface
{
    public function getAllPosts()
    {
        return Post::all();
    }

    public function findPostById($id)
    {
        return Post::find($id);
    }
}

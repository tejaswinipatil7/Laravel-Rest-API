<?php

namespace App\Services;

use App\Repositories\PostRepositoryInterface;

class PostService
{
    protected $postRepository;

    public function __construct(PostRepositoryInterface $postRepository)
    {
        $this->postRepository = $postRepository;
    }

    public function getAllPosts()
    {
        return $this->postRepository->getAllPosts();
    }

    public function getPostById($id)
    {
        return $this->postRepository->findPostById($id);
    }
}

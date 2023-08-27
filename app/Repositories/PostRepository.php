<?php

namespace App\Repositories;

use App\Interfaces\PostRepositoryInterface;
use App\Models\Post;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;

class PostRepository implements PostRepositoryInterface
{

    public function remove(int $id): void
    {
        $post = Post::where('id', $id);
        $post->delete();
    }

    public function edit(int $id, array $details): void
    {
        $post = Post::where('id', $id)->first();
        $post->update($details);
        $post->clearMediaCollection('thumbnails');

        if ($image = $details['thumbnail']) {
            $post->addMedia($image)->toMediaCollection('thumbnails');
        }
    }

    public function create(array $details): Post
    {
        $post = Auth::user()?->posts()->create($details);

        if ($image = $details['thumbnail']) {
            $post->addMedia($image)->toMediaCollection('thumbnails');
        }
        $post->load('media');

        return $post;
    }

    public function getAll(): LengthAwarePaginator
    {
        return Post::with('media')->paginate();
    }
}

<?php

namespace App\Http\Controllers;

use App\Http\Requests\EditPostRequest;
use App\Http\Requests\RemovePostRequest;
use App\Http\Requests\StorePostRequest;
use App\Interfaces\PostRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    public function __construct(private readonly PostRepositoryInterface $post_repository)
    {
    }

    public function index(): LengthAwarePaginator
    {
        return $this->post_repository->getAll();
    }

    public function store(StorePostRequest $request): JsonResponse
    {
        $post = $this->post_repository->create([
            'title' => $request->title,
            'body' => $request->body,
            'thumbnail' => $request->thumbnail,
            'author_id' => Auth::user()->id,
        ]);

        return response()->json([
            'data' => $post->toArray(),
        ], 201);
    }

    public function remove(RemovePostRequest $request): JsonResponse
    {
        $this->post_repository->remove($request->id);

        return response()->json([
            'message' => 'Post removed successfully',
        ]);
    }

    public function edit(EditPostRequest $request): JsonResponse
    {
        $this->post_repository->edit($request->id, [
            'title' => $request->title,
            'body' => $request->body,
            'thumbnail' => $request->thumbnail,
        ]);

        return response()->json([
            'message' => 'Post edited successfully',
        ]);
    }
}

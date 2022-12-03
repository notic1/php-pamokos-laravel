<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\PostRequest;
use App\Http\Resources\PostResource;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $posts = Post::with('user')->get();
        
        return PostResource::collection($posts);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PostRequest $request)
    {
        $post = User::find(1)->posts()->create($request->all());

        return PostResource::make($post);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Post $post)
    {
        return PostResource::make($post);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(PostRequest $request, Post $post)
    {
        $data = $request->validated();
        $status = $post->update($data);

        return $this->jsonResponse([
            'success' => $status,
            'post' => PostResource::make($post)
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $postModel = Post::withTrashed()->findOrFail($id);

        if ($postModel->trashed()) {
            $status = $postModel->forceDelete();

            return $this->jsonResponse([
                'success' => $status,
            ]);
        }

        $status = $postModel->delete();

        return $this->jsonResponse([
            'success' => $status,
        ]);
    }

    public function restore($id)
    {
        $postModel = Post::withTrashed()->findOrFail($id);

        if ($postModel) {
            $status = $postModel->restore();
        }

        return $this->jsonResponse([
            'success' => $status,
            'post' => PostResource::make($postModel)
        ]);
    }
}

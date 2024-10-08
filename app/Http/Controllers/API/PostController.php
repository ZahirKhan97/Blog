<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class PostController extends BaseController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $posts = Post::with('comments')->paginate(2);
        if ($posts->isNotEmpty()) {
            return $this->sendResponse($posts, 'All Posts');
        } else {
            return $this->sendResponse($posts, 'No Posts Available');
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|min:3',
            'content' => 'required|min:20',
            'image' => 'image|mimes:jpg,jpeg,png,gif'
        ]);
        if ($validator->fails()) {
            return $this->sendError('Validation Errors', $validator->errors(), 401);
        }

        if ($request->hasFile('image')) {
            $manager = new ImageManager(new Driver());
            $file = $request->file('image');
            $ext = $file->getClientOriginalExtension();
            $filename = time() . '.' . $ext;
            $path = public_path('uploads/blog/' . $filename);
            $image = $manager->read($file->getRealPath());
            $image->cover(300, 300);
            $image->save($path);
        } else {
            $filename = '';
        }

        $post = Post::create([
            'title' => $request->title,
            'content' => $request->content,
            'image' => $filename,
            'status' => $request->status,
            'user_id' => Auth::user()->id,
        ]);
        return $this->sendResponse($post, 'Post Created Successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $post = Post::with('comments')->find($id);
        if ($post) {
            return $this->sendResponse($post, 'Single Post');
        } else {
            return $this->sendError('Post not Found', '', 401);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $post = Post::find($id);
        if ($post->user_id !== Auth::id()) {
            return $this->sendResponse([], 'Unauthorized');
        }
        if ($post) {
            $validator = Validator::make($request->all(), [
                'title' => 'required|min:3',
                'content' => 'required|min:20',
                'image' => 'mimes:jpg,jpeg,png,gif'
            ]);
            if ($validator->fails()) {
                return $this->sendError('Validation Errors', $validator->errors(), 401);
            }

            if ($request->hasFile('image')) {
                if ($post->image && File::exists(public_path('uploads/blog/' . $post->image))) {
                    File::delete(public_path('uploads/blog/' . $post->image));
                }
                $manager = new ImageManager(new Driver());
                $file = $request->file('image');
                $ext = $file->getClientOriginalExtension();
                $filename = time() . '.' . $ext;
                $path = public_path('uploads/blog/' . $filename);
                $image = $manager->read($file->getRealPath());
                $image->cover(300, 300);
                $image->save($path);
            } else {
                $filename = $post->image;
            }

            $post->update([
                'title' => $request->title,
                'content' => $request->content,
                'image' => $filename,
                'status' => $request->status,
                'user_id' => Auth::user()->id,
            ]);
            return $this->sendResponse($post, 'Post Updated Successfully');
        }
        return $this->sendError('Post not Found', '', 401);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $post = Post::find($id);
        if (!$post) {
            return $this->sendError('Post not found', [], 404);
        }

        if ($post->user_id !== Auth::id()) {
            return $this->sendError('You cannot delete this Post', 'You are not Authorized', 403);
        }
        if ($post->image && File::exists(public_path('uploads/blog/' . $post->image))) {
            File::delete(public_path('uploads/blog/' . $post->image));
        }
        $post->delete();
        return $this->sendResponse(null, 'Post Deleted Successfully');
    }
}

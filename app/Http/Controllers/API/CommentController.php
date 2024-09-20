<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\Comment;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;

class CommentController extends BaseController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $comments = Comment::where('user_id', Auth::user()->id)->get();
        if ($comments->isEmpty()) {
            return $this->sendResponse([], 'No Comments Found');
        }
        return $this->sendResponse($comments, 'All Comments');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, $post_id)
    {
        $validator = Validator::make($request->all(), [
            'comment' => 'required|min:3',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Errors', $validator->errors(), 401);
        }


        $post = Post::find($post_id);

        if (!$post) {
            return $this->sendError("Post not Found", [], 401);
        }
        if (!Auth::check()) {
            return $this->sendError('Unauthorized', [], 403);
        }

        // Create the comment
        $comment = Comment::create([
            'comment' => $request->comment,
            'post_id' => $post->id,
            'user_id' => Auth::id(),
        ]);

        return $this->sendResponse($comment, 'Comment Added Successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show($postId, string $commentId)
    {
        $comment = Comment::where(['id' => $commentId, 'post_id' => $postId, 'user_id' => Auth::id()])->first();
        if (!$comment) {
            return $this->sendError('Comment not found or does not belong to you', [], 404);
        }
        return $this->sendResponse($comment, 'Single Comment');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $postId, $commentId)
    {
        // Check if the user is authenticated
        if (!Auth::check()) {
            return $this->sendError('Unauthorized', [], 403);
        }

        // Validate request input
        $validator = Validator::make($request->all(), [
            'comment' => 'required|min:3',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Errors', $validator->errors(), 401);
        }

        // Find the post
        $post = Post::find($postId);
        if (!$post) {
            return $this->sendError("Post not found", [], 404);
        }

        // Find the comment
        $comment = Comment::find($commentId);
        if (!$comment) {
            return $this->sendError('Comment not found', [], 404);
        }

        // Check if the authenticated user is the owner of the comment
        if ($comment->user_id !== Auth::id()) {
            return $this->sendError('Unauthorized', [], 403);
        }

        // Update the comment
        $comment->update([
            'comment' => $request->comment,
        ]);

        return $this->sendResponse($comment, 'Comment Updated Successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $postId, string $commentId)
    {
        if (!Auth::check()) {
            return $this->sendError('Unauthorized', [], 403);
        }

        $post = Post::find($postId);
        if (!$post) {
            return $this->sendError('Post not found', [], 404);
        }

        $comment = Comment::find($commentId);
        if (!$comment) {
            return $this->sendError('Comment not found', [], 404);
        }
        if ($comment->user_id !== Auth::user()->id) {
            return $this->sendError('Unauthorized', [], 403);
        }

        // Delete the comment
        $comment->delete();
        return $this->sendResponse(null, 'Comment deleted successfully');
    }
}

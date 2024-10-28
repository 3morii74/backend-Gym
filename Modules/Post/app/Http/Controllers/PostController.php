<?php

namespace Modules\Post\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Models\Gallery;
use App\Http\Traits\ApiResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Modules\Post\Models\Post;
use Modules\Post\Models\Reel;
use Modules\Post\Models\View;

class PostController extends Controller
{
    use ApiResponseTrait;
    public function index(Request $request)
    {
        // Define pagination parameters
        $perPage = $request->input('per_page', 10); // Number of items per page, default to 10
        $page = $request->input('page', 1); // Current page, default to 1

        // Fetch posts with their views, likes, and comments counts
        $posts = Post::withCount(['views', 'likes', 'comments'])
            ->select('id', 'media_url', 'content', DB::raw('views_count as popularity'))
            ->orderBy('popularity', 'desc')
            ->paginate($perPage, ['*'], 'page', $page);

        // Fetch reels with their views, likes, and comments counts
        $reels = Reel::withCount(['views', 'likes', 'comments'])
            ->select('id', 'title', 'video_url', DB::raw('views_count as popularity'))
            ->orderBy('popularity', 'desc')
            ->paginate($perPage, ['*'], 'page', $page);

        // Combine both collections
        $combined = $posts->getCollection()->merge($reels->getCollection());

        // Sort combined collection by popularity
        $sortedCombined = $combined->sortByDesc('popularity');

        $result = new \Illuminate\Pagination\LengthAwarePaginator(
            $sortedCombined,
            $posts->total() + $reels->total(),
            $perPage,
            $page,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        // Log views for posts and reels
        foreach ($posts as $post) {
            $this->logView($post->id, null); // Assuming null for reel_id when logging post view
        }

        foreach ($reels as $reel) {
            $this->logView(null, $reel->id); // Assuming null for post_id when logging reel view
        }

        return $this->apiResponse($result, 200, 'success');
    }
    protected function logView($postId = null, $reelId = null)
    {
        // Get the authenticated user, if applicable
        $userId = auth()->id(); // This will return the ID of the authenticated user or null
        
        // Create a new view entry
        View::create([
            'user_id' => $userId,
            'post_id' => $postId,
            'reel_id' => $reelId,
        ]);

        // Increment the view count on the post or reel
        if ($postId) {
            Post::where('id', $postId)->increment('views_count');
        } elseif ($reelId) {
            Reel::where('id', $reelId)->increment('views_count');
        }
    }
    public function createContent(Request $request)
    {
        // Validation rules
        $validator = Validator::make($request->all(), [
            'type' => 'required|string|in:post,reel',
            'content' => 'nullable|string',
            'media' => 'required|file|mimes:jpeg,jpg,png,mp4,mov,avi|max:20480', // max size 20MB
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        // Handle the media upload
        $mediaPath = $request->file('media')->store('media', 'public'); // Store in 'public/media' directory
        $gallery = new Gallery();
        $media = $gallery->storeFile($request, "posts", auth()->user()->slug);
        // Create post or reel based on the type
        if ($request->type === 'post') {
            $post = Post::create([
                'user_id' => auth()->user()->id,
                'content' => $request->content,
                'image_id' => $media->id,
                'media_url' => $media->path, // Store the media path
                // Add any other necessary fields
            ]);
            return response()->json(['message' => 'Post created successfully', 'post' => $post], 201);
        } elseif ($request->type === 'reel') {
            $reel = Reel::create([
                'user_id' => auth()->user()->id,
                'description' => $request->content,
                'reel_id' => $media->id,
                'video_url' => $media->path, // Store the media path
                // Add any other necessary fields
            ]);

            return response()->json(['message' => 'Reel created successfully', 'reel' => $reel], 201);
        }
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\View\View;

class PostController extends Controller
{
    public function index(): View
    {
        $posts = Post::query()
            ->published()
            ->with(['author', 'categories', 'comments', 'likes'])
            ->latest('published_at')
            ->take(9)
            ->get();

        return view('employee-portal.newspage.main-newspage', [
            'posts' => $posts,
        ]);
    }

    public function show(Post $post): View
    {
        $post->load(['author', 'categories', 'comments.author', 'likes']);

        return view(
            'employee-portal.newspage.posts.show',
            [
                'post' => $post,
            ]
        );
    }
}

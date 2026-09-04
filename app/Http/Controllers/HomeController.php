<?php

namespace App\Http\Controllers;

use App\Models\Carousel;
use App\Models\DepartmentModule;
use App\Models\Event;
use App\Models\Post;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        $carousels = Carousel::active()
            ->ordered()
            ->get();

        $posts = Post::query()
            ->featured()
            ->with(['author', 'categories', 'likes'])
            ->latest('published_at')
            ->take(3)
            ->get();

        $departments = DepartmentModule::with(['faqs', 'workflows', 'downloadables'])
            ->get();

        $events = Event::query()
            ->where('date', '>=', now()->startOfDay())
            // ->with(['department'])
            ->orderBy('date', 'asc')
            ->limit(6)
            ->get();

        return view('employee-portal.homepage.welcome', compact('carousels', 'posts', 'departments', 'events'));
    }
}

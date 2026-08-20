<?php

// filepath: app/Http/Controllers/FAQController.php

namespace App\Http\Controllers;

use App\Models\DepartmentModule;
use App\Models\FAQModule;
use App\Services\FAQService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class FAQController extends Controller
{
    public function __construct(
        private readonly FAQService $faqService
    ) {}

    /**
     * Display all FAQs for a specific department
     * URL: /department/{department}/faq
     */
    public function index(Request $request, string $department): View
    {
        // Get the department by slug
        $currentDepartment = DepartmentModule::where('cms_department_slug', $department)->firstOrFail();

        // Use service for search and filtering
        $faqs = $this->faqService->searchFAQs($currentDepartment, $request->search);

        // Filter by tag if provided
        if ($request->has('tag') && $request->tag) {
            $faqs = $this->faqService->getFAQsByTag($currentDepartment, (int) $request->tag);
        }

        // Group FAQs by tag
        $faqsByTag = $this->faqService->groupFAQsByTag($faqs);

        // Get tags with counts for this department only
        $tags = $this->faqService->getTagsWithCounts($currentDepartment);

        return view('faqpage', compact('faqs', 'faqsByTag', 'tags', 'currentDepartment'));
    }

    /**
     * Display a single FAQ
     * URL: /department/{department}/faq/{slug}
     */
    public function show(string $department, string $slug): View
    {
        // Get the department by slug
        $currentDepartment = DepartmentModule::where('cms_department_slug', $department)->firstOrFail();

        // Get the FAQ using service
        $faq = $this->faqService->getFAQBySlug($currentDepartment, $slug);

        if (! $faq) {
            abort(404);
        }

        // Get related FAQs
        $faqModel = FAQModule::find($faq->id);
        $relatedFaqs = $this->faqService->getRelatedFAQs($faqModel);

        return view('faq.show', compact('faq', 'relatedFaqs', 'currentDepartment'));
    }
}

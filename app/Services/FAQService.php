<?php

namespace App\Services;

use App\Data\FAQItemDTO;
use App\Models\DepartmentModule;
use App\Models\FAQModule;
use App\Models\FAQTagModule;
use Illuminate\Support\Collection;

/**
 * FAQService - Handles all FAQ-related business logic
 */
class FAQService
{
    /**
     * Get all FAQs for a department
     */
    public function getFAQsByDepartment(DepartmentModule $department): Collection
    {
        return FAQModule::query()
            ->where('faq_is_published', true)
            ->where('cms_department_id', $department->id)
            ->with(['department', 'tag'])
            ->latest()
            ->get()
            ->map(fn (FAQModule $faq) => FAQItemDTO::fromModel($faq));
    }

    /**
     * Search FAQs by department and query
     */
    public function searchFAQs(DepartmentModule $department, ?string $query = null): Collection
    {
        $queryBuilder = FAQModule::query()
            ->where('faq_is_published', true)
            ->where('cms_department_id', $department->id)
            ->with(['department', 'tag'])
            ->latest();

        if ($query && strlen(trim($query)) >= 2) {
            $search = trim($query);
            $queryBuilder->where(function ($q) use ($search) {
                $q->where('faq_title', 'like', "%{$search}%")
                    ->orWhere('faq_body', 'like', "%{$search}%");
            });
        }

        return $queryBuilder->get()
            ->map(fn (FAQModule $faq) => FAQItemDTO::fromModel($faq));
    }

    /**
     * Filter FAQs by tag
     */
    public function getFAQsByTag(DepartmentModule $department, int $tagId): Collection
    {
        return FAQModule::query()
            ->where('faq_is_published', true)
            ->where('cms_department_id', $department->id)
            ->where('faq_tag_id', $tagId)
            ->with(['department', 'tag'])
            ->latest()
            ->get()
            ->map(fn (FAQModule $faq) => FAQItemDTO::fromModel($faq));
    }

    /**
     * Get FAQ by slug
     */
    public function getFAQBySlug(DepartmentModule $department, string $slug): ?FAQItemDTO
    {
        $faq = FAQModule::where('faq_slug', $slug)
            ->where('cms_department_id', $department->id)
            ->where('faq_is_published', true)
            ->with(['department', 'tag'])
            ->first();

        return $faq ? FAQItemDTO::fromModel($faq) : null;
    }

    /**
     * Get related FAQs by tag
     */
    public function getRelatedFAQs(FAQModule $faq, int $limit = 5): Collection
    {
        return FAQModule::query()
            ->where('faq_tag_id', $faq->faq_tag_id)
            ->where('id', '!=', $faq->id)
            ->where('faq_is_published', true)
            ->with(['tag'])
            ->take($limit)
            ->get()
            ->map(fn (FAQModule $f) => FAQItemDTO::fromModel($f));
    }

    /**
     * Get all tags for a department with FAQ counts
     */
    public function getTagsWithCounts(DepartmentModule $department): Collection
    {
        return FAQTagModule::whereHas('faqs', function ($query) use ($department) {
            $query->where('faq_is_published', true)
                ->where('cms_department_id', $department->id);
        })->withCount(['faqs' => function ($query) use ($department) {
            $query->where('faq_is_published', true)
                ->where('cms_department_id', $department->id);
        }])->get();
    }

    /**
     * Group FAQs by tag name
     */
    public function groupFAQsByTag(Collection $faqs): Collection
    {
        return $faqs->groupBy(fn (FAQItemDTO $faq) => $faq->tagName ?? 'General');
    }

    /**
     * Get total FAQ count for a department
     */
    public function getFAQCount(DepartmentModule $department): int
    {
        return FAQModule::query()
            ->where('faq_is_published', true)
            ->where('cms_department_id', $department->id)
            ->count();
    }
}

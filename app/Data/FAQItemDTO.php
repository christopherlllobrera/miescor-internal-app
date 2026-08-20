<?php

namespace App\Data;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Str;

/**
 * FAQItemDTO - Data Transfer Object for FAQ item data
 */
readonly class FAQItemDTO implements Arrayable
{
    public function __construct(
        public int $id,
        public ?string $title,
        public ?string $slug,
        public ?string $body,
        public int $departmentId,
        public ?string $departmentName,
        public ?int $tagId,
        public ?string $tagName,
        public bool $isPublished,
    ) {}

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'body' => $this->body,
            'department_id' => $this->departmentId,
            'department_name' => $this->departmentName,
            'tag_id' => $this->tagId,
            'tag_name' => $this->tagName,
            'is_published' => $this->isPublished,
        ];
    }

    public static function fromModel($faq): self
    {
        return new self(
            id: $faq->id,
            title: $faq->faq_title,
            slug: $faq->faq_slug ?? ($faq->faq_title ? Str::slug($faq->faq_title) : null),
            body: $faq->faq_body,
            departmentId: $faq->cms_department_id,
            departmentName: $faq->department?->cms_department_name,
            tagId: $faq->faq_tag_id,
            tagName: $faq->tag?->faq_tag_name,
            isPublished: (bool) $faq->faq_is_published,
        );
    }
}

<?php

namespace App\Data;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Str;

/**
 * WorkflowItemDTO - Data Transfer Object for Workflow item data
 */
readonly class WorkflowItemDTO implements Arrayable
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

    public static function fromModel($workflow): self
    {
        return new self(
            id: $workflow->id,
            title: $workflow->workflow_title,
            slug: $workflow->workflow_slug ?? ($workflow->workflow_title ? Str::slug($workflow->workflow_title) : null),
            body: $workflow->workflow_body,
            departmentId: $workflow->cms_department_id,
            departmentName: $workflow->department?->cms_department_name,
            tagId: $workflow->workflow_tag_id,
            tagName: $workflow->tag?->workflow_tag_name,
            isPublished: (bool) $workflow->workflow_is_published,
        );
    }
}

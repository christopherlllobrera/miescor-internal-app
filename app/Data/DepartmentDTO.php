<?php

namespace App\Data;

use Illuminate\Contracts\Support\Arrayable;

/**
 * DepartmentDTO - Data Transfer Object for Department data
 */
readonly class DepartmentDTO implements Arrayable
{
    public function __construct(
        public int $id,
        public string $name,
        public string $slug,
        public ?string $description,
        public ?string $image,
        public array $faqs = [],
        public array $workflows = [],
        public array $downloadables = [],
        public array $directories = [],
    ) {}

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'description' => $this->description,
            'image' => $this->image,
            'faqs_count' => count($this->faqs),
            'workflows_count' => count($this->workflows),
            'downloadables_count' => count($this->downloadables),
            'directories_count' => count($this->directories),
        ];
    }

    public static function fromModel($department): self
    {
        return new self(
            id: $department->id,
            name: $department->cms_department_name,
            slug: $department->cms_department_slug,
            description: $department->cms_department_description ?? null,
            image: $department->cms_department_image ?? null,
            faqs: $department->faqs?->map(fn ($faq) => FAQItemDTO::fromModel($faq))->toArray() ?? [],
            workflows: $department->workflows?->map(fn ($wf) => WorkflowItemDTO::fromModel($wf))->toArray() ?? [],
            downloadables: $department->downloadables?->pluck('form_title')->toArray() ?? [],
            directories: $department->directories?->pluck('name')->toArray() ?? [],
        );
    }
}

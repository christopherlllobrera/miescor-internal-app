<?php

namespace App\Http\Controllers;

use App\Models\DepartmentModule;
use App\Models\DownloadableModule;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\Response;

class DepartmentController extends Controller
{
    public function show(string $slug): View
    {
        $department = DepartmentModule::where('cms_department_slug', $slug)
            ->with(['downloadables',
                'workflows' => function ($query) {
                    $query->where('workflow_is_published', true)
                        ->with('tag')
                        ->latest()
                        ->limit(5);
                },
                'faqs' => function ($query) {
                    $query->where('faq_is_published', true)
                        ->with('tag')
                        ->latest()
                        ->limit(5);
                },
                'directories' => function ($query) {
                    $query->with('employee')
                        ->limit(5);
                },
            ])
            ->firstOrFail();

        return view('departmentpage', compact('department'));
    }

    public function download(int $id): Response
    {
        $record = DownloadableModule::findOrFail($id);
        $binary = $record->getRawOriginal('form_attachment');

        if (! $binary) {
            abort(404, 'No attachment found.');
        }

        // Detect MIME type from magic bytes
        $magic = substr($binary, 0, 8);
        $mimeType = 'application/octet-stream';
        $extension = 'bin';

        if (str_starts_with($magic, '%PDF')) {
            $mimeType = 'application/pdf';
            $extension = 'pdf';
        } elseif (str_starts_with($magic, "\xFF\xD8\xFF")) {
            $mimeType = 'image/jpeg';
            $extension = 'jpg';
        } elseif (str_starts_with($magic, "\x89PNG")) {
            $mimeType = 'image/png';
            $extension = 'png';
        } elseif (str_starts_with($magic, "\xD0\xCF\x11\xE0")) {
            $mimeType = 'application/msword';
            $extension = 'doc';
        } elseif (str_starts_with($magic, "PK\x03\x04")) {
            if (str_contains($binary, 'word/')) {
                $mimeType = 'application/vnd.openxmlformats-officedocument.wordprocessingml.document';
                $extension = 'docx';
            } elseif (str_contains($binary, 'xl/')) {
                $mimeType = 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet';
                $extension = 'xlsx';
            }
        }

        $fileName = ($record->form_title ?? 'attachment').'.'.$extension;

        return response($binary, 200, [
            'Content-Type' => $mimeType,
            'Content-Disposition' => 'attachment; filename="'.$fileName.'"',
            'Content-Length' => strlen($binary),
        ]);
    }
}

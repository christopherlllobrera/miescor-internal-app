<?php

namespace App\Filament\Resources\EmployeePortal\DownloadableModules\Schemas;

use App\Filament\Forms\Components\HeroiconOptions;
use App\Models\Department;
use App\Models\DepartmentModule;
use App\Models\DownloadableModule;
use App\Models\Employee;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\HtmlString;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class DownloadableModuleForm
{
    public static function configure(Schema $schema): Schema
    {

        return $schema
            ->columns(3)
            ->components([
                Section::make('Department Details')
                    ->columns([
                        'default' => 1,
                        'sm' => 2,
                        'md' => 2,
                        'lg' => 2,
                        'xl' => 2,
                        '2xl' => 2,
                    ])
                    ->columnSpan(['lg' => fn (?DownloadableModule $record) => $record === null ? 3 : 2])
                    ->description('Use this section to upload a new form and define its display details. Ensure the Title is descriptive. The Icon will help users quickly identify the form type.')
                    ->schema([
                        Select::make('cms_department_id')
                            ->label('Department Name')
                            ->options(function () {
                                // preload departments to avoid N+1 queries
                                $departments = Department::all()->keyBy('DeptNo');

                                return DepartmentModule::query()
                                    ->select('id', 'cms_department_name')
                                    ->get()
                                    ->mapWithKeys(function ($module) use ($departments) {
                                        $dept = $departments[$module->cms_department_name] ?? null;

                                        if (! $dept) {
                                            return [];
                                        }
                                        $words = explode(' ', $dept->DeptDesc);
                                        $formatted = collect($words)->map(function ($word, $index) {
                                            $word = strtolower($word);
                                            $smallWords = ['and', 'or', 'of', 'the', 'in', 'on', 'at', 'to', 'for'];
                                            if ($index > 0 && in_array($word, $smallWords)) {
                                                return $word;
                                            }
                                            if (strlen($word) <= 3) {
                                                return strtoupper($word);
                                            }

                                            return ucfirst($word);
                                        })->join(' ');

                                        return [
                                            $module->id => $formatted,
                                        ];
                                    })
                                    ->toArray();
                            })
                            ->required()
                            ->dehydrated(true)
                            ->default(function () {
                                $user = auth()->user();
                                if ($user && $user->hasRole('Department PIC') && $user->empNo) {
                                    $employee = Employee::where('EmpNo', $user->empNo)->first();
                                    if ($employee && $employee->DeptNo) {
                                        $deptGroup = substr($employee->DeptNo, 0, 4);
                                        $departmentModule = DepartmentModule::where('cms_department_name', 'like', $deptGroup.'%')->first();

                                        return $departmentModule ? $departmentModule->id : null;
                                    }
                                }

                                return null;
                            })
                            ->disabled(fn () => auth()->user()->hasRole('Department PIC'))
                        // ->visible(fn() => !auth()->user()->hasRole('superdmin'))
                        ,

                        TextInput::make('form_title')
                            ->label('Title')
                            ->unique(ignoreRecord: true)
                            ->maxLength(255)
                            ->required(),
                        Select::make('form_icon')
                            ->label('Icon')
                            ->options(HeroiconOptions::solid())
                            ->searchable()
                            ->preload()
                            ->optionsLimit(2000)
                            ->native(false)
                            ->allowHtml()
                            ->hint(function () {
                                return new HtmlString(Blade::render('<x-filament::link href="https://heroicons.com/">Heroicon</x-filament::link>'));
                            })
                            ->getOptionLabelUsing(
                                fn ($value) => HeroiconOptions::solid()[$value] ?? $value
                            )
                            ->required()
                            ->columnSpanFull(),
                        FileUpload::make('form_attachment')
                            ->label('Attachment')
                            ->required(fn ($record) => $record === null)
                            ->acceptedFileTypes(['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'image/jpeg', 'image/png', 'image/jpg', 'application/vnd.ms-excel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'])
                            ->columnSpanFull()
                            ->multiple(false)
                            ->disk('local')
                            ->directory('livewire-tmp')
                            ->storeFiles(false)
                            ->dehydrated(false)
                            ->afterStateHydrated(function ($component, $state) {
                                if (is_string($state) && (! ctype_print($state) || strlen($state) > 255)) {
                                    $component->state(null);
                                }
                            })
                            ->saveRelationshipsUsing(function ($record, $state) {
                                if (is_array($state)) {
                                    $state = array_values($state)[0] ?? null;
                                }
                                if (is_string($state) && \Storage::disk('local')->exists($state)) {
                                    $record->updateQuietly(['form_attachment' => \Storage::disk('local')->get($state)]);
                                } elseif ($state instanceof TemporaryUploadedFile) {
                                    $record->updateQuietly(['form_attachment' => $state->get()]);
                                }
                            }),
                    ]),
                Section::make('Downloadable Details')
                    ->visibleOn(['edit'])
                    ->collapsible()
                    ->columnSpan(['lg' => 1])
                    ->schema([
                        TextEntry::make('form_attachment')
                            ->label('Attachment')
                            ->columnSpanFull()
                            ->state(function ($record): string {
                                $binary = $record?->getRawOriginal('form_attachment');

                                if (! $record || ! $binary) {
                                    return '
                                        <div class="overflow-hidden border rounded-lg">
                                            <table class="min-w-full divide-y divide-neutral-200">
                                                <thead class="bg-neutral-50">
                                                    <tr class="text-neutral-500">
                                                        <th class="px-5 py-3 text-xs font-medium text-left uppercase">Attachment</th>
                                                        <th class="px-5 py-3 text-xs font-medium text-right uppercase">Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="divide-y divide-neutral-200">
                                                    <tr>
                                                        <td colspan="2" class="px-5 py-4 text-sm text-center text-gray-400">
                                                            No attachments found.
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    ';
                                }

                                $binary = $record?->getRawOriginal('form_attachment');

                                if (! $record || ! $binary) {
                                    // ... empty state
                                }

                                // If stored as base64, decode it first
                                if (! preg_match('/[\x00-\x08\x0B\x0E-\x1F\x80-\xFF]/', substr($binary, 0, 100))) {
                                    $decoded = base64_decode($binary, strict: true);
                                    if ($decoded !== false) {
                                        $binary = $decoded;
                                    }
                                }

                                $magic = substr($binary, 0, 8);
                                $extension = 'bin';

                                if (str_starts_with($magic, '%PDF')) {
                                    $extension = 'pdf';
                                } elseif (str_starts_with($magic, "\xFF\xD8\xFF")) {
                                    $extension = 'jpg';
                                } elseif (str_starts_with($magic, "\x89PNG")) {
                                    $extension = 'png';
                                } elseif (str_starts_with($magic, "\xD0\xCF\x11\xE0")) {
                                    $extension = 'doc';
                                } elseif (str_starts_with($magic, "PK\x03\x04")) {
                                    $extension = 'bin'; // default ZIP-based
                                    if (str_contains($binary, 'word/')) {
                                        $extension = 'docx';
                                    } elseif (str_contains($binary, 'xl/')) {
                                        $extension = 'xlsx';
                                    }
                                }

                                $fileName = ($record->form_title ?? 'attachment').'.'.$extension;
                                $downloadUrl = route('downloadable-modules.download', $record->id);

                                return "
                                    <div class='overflow-hidden border border-gray-300 rounded-lg'>
                                        <table class='min-w-full divide-y divide-neutral-200'>
                                            <thead class='bg-neutral-50'>
                                                <tr class='text-neutral-500'>
                                                    <th class='px-5 py-3 text-xs font-medium text-left uppercase'>Attachment</th>
                                                    <th class='px-5 py-3 text-xs font-medium text-right uppercase'>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody class='divide-y divide-neutral-200'>
                                                <tr class='text-neutral-800'>
                                                    <td class='px-5 py-4 text-sm font-medium whitespace-nowrap'>
                                                        {$fileName}
                                                    </td>
                                                    <td class='px-5 py-4 text-sm font-medium text-right whitespace-nowrap'>
                                                        <a 
                                                            href='{$downloadUrl}'
                                                            class='inline-flex items-center gap-1 text-blue-600 hover:text-blue-700 font-medium'
                                                        >
                                                            <svg xmlns='http://www.w3.org/2000/svg' class='w-4 h-4' fill='none' viewBox='0 0 24 24' stroke='currentColor' stroke-width='2'>
                                                                <path stroke-linecap='round' stroke-linejoin='round' d='M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2M7 10l5 5m0 0l5-5m-5 5V4'/>
                                                            </svg>
                                                            Download
                                                        </a>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                ";
                            })
                            ->html(),
                        TextEntry::make('created_at')
                            ->state(fn (DownloadableModule $record): ?string => $record->created_at?->diffForHumans()),
                        TextEntry::make('updated_at')
                            ->label('Last modified at')
                            ->state(fn (DownloadableModule $record): ?string => $record->updated_at?->diffForHumans()),
                    ]),
            ]);
    }
}

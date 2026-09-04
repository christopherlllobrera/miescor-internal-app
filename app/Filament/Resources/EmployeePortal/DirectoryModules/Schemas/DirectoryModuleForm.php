<?php

namespace App\Filament\Resources\EmployeePortal\DirectoryModules\Schemas;

use App\Models\Department;
use App\Models\DepartmentModule;
use App\Models\DirectoryModule;
use App\Models\Employee;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Collection;
use Illuminate\Support\HtmlString;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class DirectoryModuleForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(3)
            ->components([
                Section::make('Directory Details')
                    ->columns([
                        'default' => 1,
                        'sm' => 2,
                        'md' => 2,
                        'lg' => 2,
                        'xl' => 2,
                        '2xl' => 2,
                    ])
                    ->columnSpan(['lg' => fn (?DirectoryModule $record) => $record === null ? 3 : 2])
                    ->description('Complete these fields to add a new person to the staff directory.
                            Ensure the Department and Job Position are accurate, and
                            upload a clear, professional Profile Image.')
                    ->schema([
                        Select::make('poc_name_id')
                            ->label('Name')
                            ->options(
                                fn (): Collection => Employee::where('CompNo', 1101)
                                    ->whereNotNull('PostNo')
                                    ->get()
                                    ->mapWithKeys(fn ($employee) => [
                                        $employee->EmpNo => $employee->full_name,
                                    ])
                            )
                            ->searchable()
                            ->loadingMessage('Loading employee...')
                            ->live()
                            ->afterStateUpdated(function ($state, callable $set) {
                                $employee = Employee::with('position')->where('EmpNo', $state)->first();

                                if ($employee?->position) {
                                    $set('poc_job_position', $employee->position->PostDesc);
                                } else {
                                    $set('poc_job_position', null);
                                }
                            }),
                        TextInput::make('poc_job_position')
                            ->label('Job Position')
                            ->disabled()
                            ->dehydrated()
                            ->live(),
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
                            ->disabledOn('edit'),
                        FileUpload::make('poc_image')
                            ->label('Profile Image')
                            ->image()
                            ->columnSpanFull()
                            ->acceptedFileTypes(['image/jpeg', 'image/png'])
                            ->multiple(false)
                            ->required(fn ($record) => $record === null)
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
                                    $record->updateQuietly(['poc_image' => \Storage::disk('local')->get($state)]);
                                } elseif ($state instanceof TemporaryUploadedFile) {
                                    $record->updateQuietly(['poc_image' => $state->get()]);
                                }
                            }),
                    ]),
                Section::make('Profile Image')
                    ->visibleOn(['edit'])
                    ->collapsible()
                    ->columnSpan(['lg' => 1])
                    ->schema([
                        Placeholder::make('poc_image_preview')
                            ->label('Preview')
                            ->columnSpanFull()
                            ->content(function ($record): HtmlString {
                                if (! $record || ! $record->poc_image) {
                                    return new HtmlString('
                                        <div class="rounded-lg overflow-hidden border border-gray-200 bg-white dark:bg-gray-900 dark:border-gray-700 shadow-sm w-64">
                                            <div class="flex items-center justify-center h-40 bg-gray-100 dark:bg-gray-800">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-16 h-16 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                                                </svg>
                                            </div>
                                            <div class="p-4">
                                                <h2 class="mb-1 text-sm font-bold text-gray-900 dark:text-white">No Profile Image</h2>
                                                <p class="text-xs text-gray-500 dark:text-gray-400">Upload an image using the field below.</p>
                                            </div>
                                        </div>
                                    ');
                                }

                                $base64 = base64_encode($record->poc_image);
                                $name = $record->employee?->full_name ?? 'Unknown';
                                $position = $record->poc_job_position ?? 'No Position';
                                $slug = $record->department?->cms_department_slug;
                                $url = $slug ? route('department.show', $slug) : '#';

                                return new HtmlString("
                                    <div class='rounded-lg overflow-hidden border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 shadow-sm w-64'>
                                        <div class='relative'>
                                            <img src=\"data:image/jpeg;base64,{$base64}\" 
                                                alt=\"{$name}\" class='w-full h-60 object-cover object-top'
                                            />
                                        </div>
                                        <div class='p-4'>
                                            <h2 class='mb-1 text-sm font-bold leading-none tracking-tight text-gray-900 dark:text-white'>{$name}</h2>
                                            <p class='mb-4 text-xs text-gray-500 dark:text-gray-400'>{$position}</p>
                                            <a href='{$url}' target='_blank'class='inline-flex items-center justify-center w-full h-9 px-4 py-2 text-xs font-medium text-white rounded-md bg-orange-400 hover:bg-gray-800 dark:bg-orange-600 dark:text-white dark:hover:bg-gray-100 transition-colors'>
                                                View Department Page
                                            </a>
                                        </div>
                                    </div>
                                ");
                            }),
                    ]),
            ]);
    }
}

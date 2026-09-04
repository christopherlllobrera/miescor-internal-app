<?php

namespace App\Filament\Resources\EmployeePortal\DepartmentModules\Schemas;

use App\Filament\Forms\Components\HeroiconOptions;
use App\Models\Department;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextArea;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Str;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class DepartmentModuleForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
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
                    ->columnSpanFull()
                    ->description('Provide a clear, concise description that will help other employee quickly understand
                                    the scope of this department. Think about its main goals and which teams or functions fall under it.')
                    ->schema([
                        Select::make('cms_department_name')
                            ->options(function () {
                                return Department::query()
                                    ->where('DeptNo', 'like', '%100')
                                    ->orderBy('DeptDesc')
                                    ->get()
                                    ->mapWithKeys(function ($dept) {
                                        $words = explode(' ', $dept->DeptDesc);

                                        $formatted = collect($words)->map(function ($word, $index) {
                                            $word = strtolower($word);

                                            // Keep small words lowercase (except if first word)
                                            $smallWords = ['and', 'or', 'of', 'the', 'in', 'on', 'at', 'to', 'for'];
                                            if ($index > 0 && in_array($word, $smallWords)) {
                                                return $word;
                                            }

                                            // If word is 2 or 3 letters (and not a small word), make it all uppercase (for acronyms)
                                            if (strlen($word) <= 3) {
                                                return strtoupper($word);
                                            }

                                            // Otherwise, capitalize first letter only
                                            return ucfirst($word);
                                        })->join(' ');

                                        return [$dept->DeptNo => $formatted];
                                    })
                                    ->toArray();
                            })
                            ->searchable()
                            ->unique(ignoreRecord: true)
                            ->label('Department Name')
                            ->live(onBlur: true)
                            ->disabledOn('edit')
                            ->afterStateUpdated(function ($state, callable $set) {
                                // Get the department description based on the selected DeptNo
                                $dept = Department::where('DeptNo', $state)->first();

                                if ($dept) {
                                    // Convert to sentence case (lowercase with first letter capitalized)
                                    $sentenceCase = ucfirst(strtolower($dept->DeptDesc));
                                    $set('cms_department_slug', Str::slug($sentenceCase));
                                }
                            })
                            ->required(),

                        TextInput::make('cms_department_slug')
                            ->label('Slug')
                            ->dehydrated()
                            ->readonly(),
                        Select::make('cms_icon')
                            ->label('Icon')
                            ->options(HeroiconOptions::all())
                            ->required()
                            ->searchable()
                            ->native(false)
                            ->allowHtml()
                            ->hint(function () {
                                return new HtmlString('<a href="https://heroicons.com/" target="_blank" class="text-primary-600 hover:text-primary-500 font-medium">Heroicon</a>');
                            })
                            ->getOptionLabelUsing(
                                fn ($value) => HeroiconOptions::all()[$value] ?? $value
                            ),
                        TextArea::make('cms_department_description')
                            ->label('Description')
                            ->columnspanFull()
                            ->required(),
                        FileUpload::make('cms_banner')
                            ->label('Banner Image')
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
                                    $record->updateQuietly(['cms_banner' => \Storage::disk('local')->get($state)]);
                                } elseif ($state instanceof TemporaryUploadedFile) {
                                    $record->updateQuietly(['cms_banner' => $state->get()]);
                                }
                            })
                            ->image()
                            ->imageEditor()
                            ->imageEditorViewportWidth('1920')
                            ->imageEditorViewportHeight('1080')
                            ->acceptedFileTypes([
                                'image/jpeg',
                                'image/png',
                                'image/jpg',
                            ])
                            // ->maxSize(5120)
                            ->multiple(false)
                            ->columnspanFull(),
                    ]),
                Section::make('View Banner')
                    ->visibleOn(['edit'])
                    ->collapsed()
                    ->columnSpanFull()
                    ->schema([
                        TextEntry::make('cms_banner_preview')
                            ->label('Preview Banner')
                            ->columnSpanFull()
                            ->state(function ($record): string {
                                if (! $record || ! $record->cms_banner) {
                                    return '
                                        <div class="relative h-64 bg-[#374151] rounded-lg overflow-hidden flex items-center justify-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-24 h-24 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909M3 3h18M3 21h18" />
                                            </svg>
                                            <div class="absolute bottom-0 w-full text-center pb-4">
                                                <p class="text-gray-400 text-sm">No banner image uploaded.</p>
                                            </div>
                                        </div>
                                    ';
                                }

                                $finfo = new \finfo(FILEINFO_MIME_TYPE);
                                $mimeType = $finfo->buffer($record->cms_banner) ?: 'image/jpeg';
                                $base64 = base64_encode($record->cms_banner);
                                $src = "data:{$mimeType};base64,{$base64}";

                                $name = e($record->display_name ?? $record->cms_department_name ?? 'Department');
                                $description = e($record->cms_department_description ?? '');

                                return "
                                    <div class='relative h-64 sm:h-72 md:h-96 bg-[#1f2937] rounded-lg overflow-hidden'>
                                        <img src='{$src}'
                                            alt='{$name} Cover'
                                            class='w-full h-full object-cover rounded-lg'
                                        />
                                        <div class='absolute inset-0 flex flex-col p-4 sm:p-8 md:p-10 bg-black/40 rounded-lg'>
                                            <h2 class='text-white font-bold pt-6 sm:pt-10 md:pt-8 text-2xl sm:text-3xl lg:text-4xl leading-tight'>
                                                {$name}
                                            </h2>
                                            <div class='mt-3 sm:mt-4 md:mt-5 max-h-32 overflow-auto sm:max-h-none sm:overflow-visible'>
                                                <p class='text-white text-sm sm:text-base md:text-lg lg:text-xl leading-tight'>
                                                    {$description}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                ";
                            })
                            ->html(),
                    ]),

            ]);
    }
}

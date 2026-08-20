<?php

namespace App\Filament\Clusters\NewsPage\Resources\PostResource\Schemas;

use App\Models\Employee;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class PostForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Main Content')
                    ->columns([
                        'default' => 1,
                        'sm' => 2,
                        'md' => 2,
                        'lg' => 2,
                        'xl' => 2,
                        '2xl' => 2,
                    ])
                    ->columnSpan([
                        'default' => 1,
                        'sm' => 2,
                        'md' => 2,
                        'lg' => 2,
                        'xl' => 2,
                        '2xl' => 2,
                    ])
                    ->schema([
                        TextInput::make('title')
                            ->live(onBlur: true)
                            ->required()
                            ->minLength(1)
                            ->maxLength(150)
                            ->afterStateUpdated(function (string $operation, $state, Set $set) {
                                if ($operation === 'edit') {
                                    return;
                                }
                                $set('slug', Str::slug($state));
                            }),
                        TextInput::make('slug')
                            ->required()->minLength(1)->live()
                            ->unique(ignoreRecord: true)
                            ->maxLength(150)
                            ->disabled()
                            ->dehydrated(),
                        RichEditor::make('body')
                            ->required()
                            ->fileAttachmentsDirectory('posts/images')
                            ->columnSpanFull(),
                    ]
                    ),
                Section::make('Meta')
                    ->columns([
                        'default' => 1,
                        'sm' => 2,
                        'md' => 2,
                        'lg' => 2,
                        'xl' => 2,
                        '2xl' => 2,
                    ])
                    ->columnSpan([
                        'default' => 1,
                        'sm' => 2,
                        'md' => 2,
                        'lg' => 2,
                        'xl' => 2,
                        '2xl' => 2,
                    ])
                    ->schema([
                        FileUpload::make('image')
                            ->label('Image')
                            ->image()
                            ->columnSpanFull()
                            ->disk('local')
                            ->directory('livewire-tmp')
                            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/jpg'])
                            // ->required()
                            ->dehydrated(true)
                            ->multiple(false)
                            ->storeFileNamesIn(null)
                            ->afterStateHydrated(function ($component, $state) {
                                if (is_string($state) && ! ctype_print($state)) {
                                    $component->state(null);
                                } elseif (is_string($state) && strlen($state) > 255) {
                                    $component->state(null);
                                }
                            })
                            ->dehydrateStateUsing(function ($state) {
                                if (! $state) {
                                    return null;
                                }

                                // $state is the temp path e.g. livewire-tmp/01KS4...jpg
                                $path = storage_path('app/'.$state);

                                if (! file_exists($path)) {
                                    return null;
                                }

                                return file_get_contents($path); // store raw binary into longblob
                            }),
                        DateTimePicker::make('published_at')
                            ->default(now())
                            ->nullable(),
                        Checkbox::make('featured')
                            ->columnSpanfull(),
                        Select::make('user_id')
                            ->label('Author')
                            ->options(
                                fn (): Collection => Employee::where('CompNo', 1101)
                                    ->whereNotNull('PostNo')
                                    ->get()
                                    ->mapWithKeys(fn ($employee) => [
                                        $employee->EmpNo => $employee->full_name,
                                    ])
                            )
                            ->searchable()
                            ->required(),
                        Select::make('categories')
                            ->multiple()
                            ->relationship('categories', 'title')
                            ->searchable(),
                    ]
                    ),
                Section::make('Image')
                    ->visibleOn(['edit'])
                    ->collapsible()
                    ->columnSpan(['lg' => 1])
                    ->schema([
                        TextEntry::make('image')
                            ->label('Post Preview')
                            ->columnSpanFull()
                            ->state(function ($record): string {
                                $title = e($record->title ?? 'Untitled');
                                $body = e(Str::limit(strip_tags($record->body ?? ''), 100));
                                $author = e($record->author?->name ?? 'Unknown');
                                $date = $record->published_at?->format('M d, Y') ?? 'Unpublished';
                                $url = $record->slug ? route('posts.show', $record->slug) : '#';
                                $binary = $record->getRawOriginal('image');

                                if (is_resource($binary)) {
                                    $binary = stream_get_contents($binary);
                                }

                                if (! $record || ! $binary) {
                                    return '
                                                <div class="rounded-lg overflow-hidden border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 shadow-sm w-[380px]">
                                                    <div class="flex items-center justify-center h-48 bg-gray-100 dark:bg-gray-800">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-16 h-16 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909M3 17.25V21h3.75M3 3h18v18H3V3z" />
                                                        </svg>
                                                    </div>
                                                    <div class="p-7">
                                                        <h2 class="mb-1 text-lg font-bold text-gray-900 dark:text-white">No Post Image</h2>
                                                        <p class="text-xs text-gray-500 dark:text-gray-400">Upload an image using the field above.</p>
                                                    </div>
                                                </div>
                                            ';
                                }

                                $base64 = base64_encode($binary);

                                if (class_exists(\finfo::class)) {
                                    $finfo = new \finfo(FILEINFO_MIME_TYPE);
                                    $mimeType = $finfo->buffer($binary) ?: 'image/jpeg';
                                } else {
                                    $magic = substr($binary, 0, 4);
                                    $mimeType = str_starts_with($magic, "\x89PNG") ? 'image/png' : 'image/jpeg';
                                }

                                return "
                                            <div class='rounded-lg overflow-hidden border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 shadow-sm w-[380px]'>
                                                <div class='relative'>
                                                    <img 
                                                        src='data:{$mimeType};base64,{$base64}' 
                                                        alt='{$title}' 
                                                        class='w-full h-48 object-cover'
                                                    />
                                                </div>
                                                <div class='p-7'>
                                                    <h2 class='mb-1 text-lg font-bold leading-none tracking-tight text-gray-900 dark:text-white'>{$title}</h2>
                                                    <p class='text-xs text-gray-500 dark:text-gray-400 mb-3'>{$author} &middot; {$date}</p>
                                                    <p class='mb-5 text-sm text-gray-500 dark:text-gray-400'>{$body}</p>
                                                    <a 
                                                        href='{$url}' 
                                                        target='_blank'
                                                        class='inline-flex items-center justify-center w-full h-10 px-4 py-2 text-sm font-medium text-white rounded-md bg-orange-400 hover:bg-gray-800 dark:bg-orange-600 dark:hover:bg-gray-100 dark:hover:text-gray-900 transition-colors'
                                                    >
                                                        View Post
                                                    </a>
                                                </div>
                                            </div>
                                        ";
                            })
                            ->html(),
                    ]),
            ]);

    }
}

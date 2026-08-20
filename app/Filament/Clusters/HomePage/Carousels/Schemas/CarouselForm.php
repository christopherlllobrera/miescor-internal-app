<?php

namespace App\Filament\Clusters\HomePage\Carousels\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\HtmlString;

class CarouselForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Carousel Details')
                    ->description('Create a carousel to display on the homepage')
                    ->schema([
                        TextInput::make('title')
                            ->label('Title')
                            ->required()
                            ->minLength(5)
                            ->maxLength(150)
                            ->live(debounce: 500)
                            ->validationMessages([
                                'required' => 'Title is required',
                                'min' => 'Title must be at least 1 character',
                                'max' => 'Title must be at most 150 characters',
                            ]),
                        TextInput::make('subtitle')
                            ->label('Subtitle')
                            ->required()->minLength(1)
                            ->maxLength(255)
                            ->live(debounce: 500),
                        Grid::make()
                            ->schema([
                                TextInput::make('button_text')
                                    ->label('Button Text')
                                    ->required()
                                    ->live(debounce: 500)
                                    ->validationMessages([
                                        'required' => 'Button Text is required',
                                    ]),
                                TextInput::make('button_link')
                                    ->label('Link')
                                    ->url()
                                    ->validationMessages([
                                        'url' => 'Please enter a valid URL, e.g., https://example.com',
                                        'required' => 'Link is required',
                                    ])
                                    ->helperText('e.g., example.com')
                                    ->required()
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(function ($state, callable $set) {
                                        if (blank($state)) {
                                            return;
                                        }
                                        if (! str_starts_with($state, 'http://') && ! str_starts_with($state, 'https://')) {
                                            $set('button_link', 'https://'.$state);
                                        }
                                    }),
                            ]),
                        TextInput::make('sort_order')
                            ->label('Sort Order')
                            ->unique(ignoreRecord: true)
                            ->numeric()
                            ->minValue(0)
                            ->helperText('Lower numbers appear first')
                            ->validationMessages([
                                'unique' => 'Sort Order must be unique',
                                'numeric' => 'Sort Order must be a number',
                                'min' => 'Sort Order must be at least 0',
                            ]),
                        Toggle::make('is_active')
                            ->label('Active')
                            ->default(true)
                            ->live(),
                        FileUpload::make('image')
                            ->label('Image')
                            ->validationMessages([
                                'required' => 'Image is required',
                                'image' => 'Image is required',
                                'mimes' => 'Image must be a valid image file',
                            ])
                            ->image()
                            ->live(debounce: 500)
                            ->disk('local')
                            ->directory('livewire-tmp')
                            ->acceptedFileTypes(['image/jpeg', 'image/png'])
                            ->dehydrated(true)
                            ->multiple(false)
                            ->required()
                            ->columnSpanFull()
                            ->afterStateHydrated(function ($component, $state) {
                                // Clear the binary blob so FileUpload doesn't try to load it as a path
                                if (is_string($state) && ! ctype_print($state)) {
                                    $component->state(null);
                                } elseif (is_string($state) && strlen($state) > 255) {
                                    // Likely binary/base64 data, not a filename
                                    $component->state(null);
                                }
                            }),
                    ])
                    ->columns([
                        'default' => 1,
                        'sm' => 2,
                    ])
                    ->columnSpan([
                        'default' => 1,
                        'sm' => 2,
                    ]),
                Section::make('View Carousel')
                    ->visibleOn(['edit'])
                   // ->collapsed()
                    ->columnSpanFull()
                    ->schema([
                        Placeholder::make('image_preview')
                            ->label('Slide Preview')
                            ->columnSpanFull()
                            ->content(function ($record): HtmlString {
                                if (! $record || ! $record->image) {
                                    return new HtmlString('<p class="text-gray-400">No image uploaded.</p>');
                                }

                                $base64 = base64_encode($record->image);
                                $title = e($record->title ?? '');
                                $subtitle = e($record->subtitle ?? '');
                                $btnText = e($record->button_text ?? '');
                                $btnLink = e($record->button_link ?? '#');

                                $subtitleHtml = $subtitle
                                    ? "<p class='mt-4 text-xl md:text-2xl lg:text-3xl text-white'>{$subtitle}</p>"
                                    : '';

                                $buttonHtml = ($btnText && $btnLink)
                                    ? "<a href='{$btnLink}' target='_blank' class='mt-6 inline-block bg-orange-600 hover:bg-orange-500 text-white px-6 py-3 rounded-xl'>{$btnText}</a>"
                                    : '';

                                return new HtmlString("
                                    <div class='relative w-full overflow-hidden rounded-xl' style='height: 500px;'>
                                        <img
                                            src='data:image/jpeg;base64,{$base64}'
                                            class='w-full h-full object-cover object-center'
                                            alt='{$title}'
                                        />
                                        <div class='absolute inset-0 flex flex-col items-center justify-center px-6 text-center' style='background: rgba(0,0,0,0.3);'>
                                            <div style='max-width: 64rem;'>
                                                <h1 class='text-5xl font-bold text-white'>{$title}</h1>
                                                {$subtitleHtml}
                                                {$buttonHtml}
                                            </div>
                                        </div>
                                    </div>
                                ");
                            }),
                    ]),
            ]);
    }
}

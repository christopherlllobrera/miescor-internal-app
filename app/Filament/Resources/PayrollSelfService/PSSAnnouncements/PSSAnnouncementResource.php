<?php

namespace App\Filament\Resources\PayrollSelfService\PSSAnnouncements;

use App\Filament\Resources\PayrollSelfService\PSSAnnouncements\Pages\ManagePSSAnnouncements;
use App\Models\PSSAnnouncement;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use UnitEnum;

class PSSAnnouncementResource extends Resource
{
    protected static ?string $model = PSSAnnouncement::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedMegaphone;

    protected static string|UnitEnum|null $navigationGroup = 'Payroll Self Service';

    protected static ?string $navigationLabel = 'Payroll Announcements';

    // protected static ?string $heading = 'PSS';

    protected static ?string $title = 'Payroll Announcements';

    protected static ?int $navigationSort = 4;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make()
                    ->columns(3)
                    ->columnSpanFull()
                    ->schema([
                        Textarea::make('announcement')
                            ->label('Announcement')
                            ->required()
                            ->columnSpanFull()
                            ->rows(3)
                            ->placeholder('Reminder: Payroll Deadline: '.now()->format('F d Y')),
                        DatePicker::make('date_published')
                            ->label('Date Published')
                            ->required()
                            ->default(now()),
                        DatePicker::make('ends_date')
                            ->label('Ends Date')
                            ->required()
                            ->default(now()->addDays(15)),
                        Select::make('type')
                            ->label('Type')
                            ->preload()->searchable()
                            ->options([
                                'Reminder' => 'Reminder',
                                'Lock Advisory' => 'Lock Advisory',
                            ])
                            ->required(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('announcement')
                    ->label('Announcement'),
                TextColumn::make('date_published')
                    ->label('Date Published')
                    ->date(),
                TextColumn::make('ends_date')
                    ->label('Ends Date')
                    ->date(),
                TextColumn::make('type')
                    ->label('Type'),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateHeading('No Payroll Announcements yet')
            ->emptyStateDescription('Once you create your first Payroll Announcement, it will appear here.');
    }

    public static function getPages(): array
    {
        return [
            'index' => ManagePSSAnnouncements::route('/'),
        ];
    }
}

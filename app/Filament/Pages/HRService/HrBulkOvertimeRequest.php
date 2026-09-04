<?php

namespace App\Filament\Pages\HRService;

use App\Models\Employee;
use App\Models\OvertimeRequest;
use BackedEnum;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use UnitEnum;

class HrBulkOvertimeRequest extends Page
{
    protected string $view = 'filament.pages.hr-bulk-overtime-request';

    protected static ?string $title = 'Overtime Request';

    protected static ?string $navigationLabel = 'Overtime';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedClock;

    protected static string|UnitEnum|null $navigationGroup = 'HR Coordinator Filing';

    protected static ?int $navigationSort = 2;

    /** @var array<string, mixed>|null */
    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill();
    }

    protected function getForms(): array
    {
        return ['form'];
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Bulk Overtime Requests')
                    ->description('Add multiple overtime requests at once. Each row will create a separate overtime request.')
                    ->columnSpanFull()
                    ->schema([
                        Repeater::make('bulk_requests')
                            ->columns(4)
                            ->label('Requests')
                            ->schema([
                                Select::make('empNo')
                                    ->label('Employee')
                                    ->options(fn (): array => Employee::all()
                                        ->mapWithKeys(fn (Employee $employee): array => [
                                            $employee->EmpNo => "{$employee->EmpLName}, {$employee->EmpFName}",
                                        ])
                                        ->toArray())
                                    ->searchable()
                                    ->preload()
                                    ->required(),
                                DatePicker::make('date')
                                    ->label('Date')
                                    ->required(),
                                TimePicker::make('ot_start')
                                    ->label('OT Start')
                                    ->required()
                                    ->default('17:00:00'),
                                TimePicker::make('ot_end')
                                    ->label('OT End')
                                    ->required()
                                    ->default('20:00:00'),
                                TextInput::make('number_of_hours')
                                    ->label('No. of Hours')
                                    ->numeric()
                                    ->required()
                                    ->default(3),
                                TextInput::make('reason')
                                    ->label('Reason')
                                    ->required(),
                                Select::make('approver_id')
                                    ->label('Approver')
                                    ->options(fn (): array => Employee::all()
                                        ->mapWithKeys(fn (Employee $employee): array => [
                                            $employee->EmpNo => "{$employee->EmpLName}, {$employee->EmpFName}",
                                        ])
                                        ->toArray())
                                    ->searchable()
                                    ->preload()
                                    ->required(),
                                FileUpload::make('attachment')
                                    ->label('Attachment')
                                    ->disk('public')
                                    ->directory('Overtime Attachment')
                                    ->required()
                                    ->columnSpanFull(),
                            ])
                            ->minItems(1)
                            ->addActionLabel('Add Row')
                            ->columnSpanFull(),
                    ]),
            ])
            ->statePath('data');
    }

    public function submit(): void
    {
        $state = $this->form->getState();
        $rows = $state['bulk_requests'] ?? [];

        $userId = auth()->id();

        foreach ($rows as $row) {
            $overtimeRequest = OvertimeRequest::create([
                'empNo' => $row['empNo'],
                'immediate_supervisor_id' => $row['approver_id'],
                'attachment' => $row['attachment'] ?? null,
                'status' => 'Pending',
                'created_by' => $userId,
                'updated_by' => $userId,
            ]);

            $overtimeRequest->items()->create([
                'date' => $row['date'],
                'ot_start' => $row['ot_start'],
                'ot_end' => $row['ot_end'],
                'number_of_hours' => $row['number_of_hours'],
                'reason' => $row['reason'],
                'created_by' => $userId,
                'updated_by' => $userId,
            ]);
        }

        Notification::make()
            ->success()
            ->title('Overtime Request Submitted')
            ->body(count($rows).' Overtime Request(s) created successfully.')
            ->send();

        $this->form->fill();
        $this->data = [];
    }
}

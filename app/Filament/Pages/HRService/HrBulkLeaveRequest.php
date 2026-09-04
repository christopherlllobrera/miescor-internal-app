<?php

namespace App\Filament\Pages\HRService;

use App\Filament\Resources\LeaveRequests\Schemas\LeaveRequestForm;
use App\Models\Employee;
use App\Models\LeaveRequest;
use BackedEnum;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use UnitEnum;

class HrBulkLeaveRequest extends Page
{
    protected string $view = 'filament.pages.hr-bulk-leave-request';

    protected static ?string $title = 'Leave Request';

    protected static ?string $navigationLabel = 'Leave';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCalendar;

    protected static string|UnitEnum|null $navigationGroup = 'HR Coordinator Filing';

    protected static ?int $navigationSort = 3;

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
                Section::make('Bulk Leave Requests')
                    ->description('Add multiple leave requests at once. Each row will create a separate leave request.')
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
                                Select::make('employee_group')
                                    ->label('Employee Group')
                                    ->options([
                                        'Regular' => 'Regular',
                                        'Probationary' => 'Probationary',
                                        'Project Hire' => 'Project Hire',
                                        'Fixed Term' => 'Fixed Term',
                                        'Regular Work Pool' => 'Regular Work Pool',
                                        'Service Agreement' => 'Service Agreement',
                                        'Meralco Seconded' => 'Meralco Seconded',
                                    ])
                                    ->live()
                                    ->afterStateUpdated(fn (Set $set) => $set('type', null))
                                    ->required(),
                                Select::make('type')
                                    ->label('Leave Type')
                                    ->options(fn (Get $get): array => LeaveRequestForm::getLeaveTypesByGroup($get('employee_group')))
                                    ->searchable()
                                    ->required(),
                                Select::make('duration')
                                    ->label('Duration')
                                    ->options([
                                        'full day' => 'Full Day',
                                        'half day AM' => 'Half Day AM',
                                        'half day PM' => 'Half Day PM',
                                    ])
                                    ->default('full day')
                                    ->required(),
                                DatePicker::make('date_start')
                                    ->label('Date Start')
                                    ->required(),
                                DatePicker::make('date_end')
                                    ->label('Date End')
                                    ->required(),
                                TextInput::make('days_total')
                                    ->label('Total Days')
                                    ->numeric()
                                    ->default(1)
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
                                TextInput::make('reason')
                                    ->label('Reason')
                                    ->required()
                                    ->columnSpanFull(),
                                FileUpload::make('attachment')
                                    ->label('Attachment')
                                    ->disk('public')
                                    ->directory('Leave Attachment')
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
            LeaveRequest::create([
                'empNo' => $row['empNo'],
                'employee_group' => $row['employee_group'] ?? null,
                'type' => $row['type'],
                'duration' => $row['duration'] ?? 'full day',
                'date_start' => $row['date_start'],
                'date_end' => $row['date_end'],
                'days_total' => $row['days_total'] ?? 1,
                'reason' => $row['reason'] ?? null,
                'immediate_supervisor_id' => $row['approver_id'],
                'attachment' => $row['attachment'] ?? null,
                'status' => 'Pending',
                'created_by' => $userId,
                'updated_by' => $userId,
            ]);
        }

        Notification::make()
            ->success()
            ->title('Leave Request Submitted')
            ->body(count($rows).' Leave Request(s) created successfully.')
            ->send();

        $this->form->fill();
        $this->data = [];
    }
}

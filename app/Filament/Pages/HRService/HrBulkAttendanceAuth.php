<?php

namespace App\Filament\Pages\HRService;

use App\Models\AttendanceAuth;
use App\Models\Employee;
use BackedEnum;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TimePicker;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use UnitEnum;

class HrBulkAttendanceAuth extends Page
{
    protected string $view = 'filament.pages.hr-bulk-attendance-auth';

    protected static ?string $title = 'Attendance Authorization Correction';

    protected static ?string $navigationLabel = 'AAF';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedClipboardDocumentList;

    protected static string|UnitEnum|null $navigationGroup = 'HR Coordinator Filing';

    protected static ?int $navigationSort = 1;

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
                Section::make('Bulk Attendance Authorization Requests')
                    ->description('Add multiple attendance authorization requests at once. Each row will create a separate attendance authorization correction.')
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
                                TimePicker::make('time_in')
                                    ->label('Time In')
                                    ->required()
                                    ->default('08:00:00'),
                                TimePicker::make('time_out')
                                    ->label('Time Out')
                                    ->required()
                                    ->default('17:00:00'),
                                TimePicker::make('request_time_in')
                                    ->label('Request Time In')
                                    ->required()
                                    ->default('08:00:00'),
                                TimePicker::make('request_time_out')
                                    ->label('Request Time Out')
                                    ->required()
                                    ->default('17:00:00'),
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
                                    ->directory('AAF Attachment')
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
            $attendanceAuth = AttendanceAuth::create([
                'empNo' => $row['empNo'],
                'immediate_supervisor_id' => $row['approver_id'],
                'attachment' => $row['attachment'] ?? null,
                'status' => 'Pending',
                'created_by' => $userId,
                'updated_by' => $userId,
            ]);

            $attendanceAuth->items()->create([
                'date' => $row['date'],
                'time_in' => $row['time_in'] ?? '00:00:00',
                'time_out' => $row['time_out'] ?? '00:00:00',
                'request_time_in' => $row['request_time_in'],
                'request_time_out' => $row['request_time_out'],
                'created_by' => $userId,
                'updated_by' => $userId,
            ]);
        }

        Notification::make()
            ->success()
            ->title('Attendance Authorization Correction Submitted')
            ->body(count($rows).' Attendance Authorization Correction created successfully.')
            ->send();

        $this->form->fill();
        $this->data = [];
    }
}

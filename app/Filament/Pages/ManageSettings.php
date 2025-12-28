<?php

namespace App\Filament\Pages;

use App\Settings\GeneralSettings;
use BezhanSalleh\FilamentShield\Traits\HasPageShield;
use Filament\Actions\Action;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Components\Utilities\Get;
use Illuminate\Support\Facades\Storage;

class ManageSettings extends Page
{
    use HasPageShield;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-cog-6-tooth';
    protected static string | \UnitEnum | null $navigationGroup = 'Settings';

    protected static ?string $navigationLabel = 'Common Settings';
    protected static ?int $navigationSort = 10;

    protected string $view = 'filament.pages.manage-settings';

    public ?array $data = [];
    public function mount(): void
    {
        $settings = app(GeneralSettings::class);
        $this->form->fill([
            'site_name' => $settings->site_name,
            'site_logo' => $settings->site_logo,
            'company_phone' => $settings->company_phone,
            'company_email' => $settings->company_email,
            'company_address' => $settings->company_address,
            'gratuity_percent' => $settings->gratuity_percent,
            'tax_percent' => $settings->tax_percent,
            'credit_card_fee' => $settings->credit_card_fee,
            'child_seat_fee' => $settings->child_seat_fee, //infant seat
            'regular_Seat_rules' => $settings->regular_Seat_rules,
            'booster_seat_fee' => $settings->booster_seat_fee,
            'stopover_fee' => $settings->stopover_fee,
            'luggage_fee' => $settings->luggage_fee,
            'luggage_rules' => $settings->luggage_rules,

            // Availability Settings
            'booking_status' => $settings->booking_status,
            'closing_message' => $settings->closing_message,
            'schedule_type' => $settings->schedule_type,
            'daily_start_time' => $settings->daily_start_time,
            'daily_end_time' => $settings->daily_end_time,
            'weekly_off_days' => $settings->weekly_off_days,
            'closed_start_date' => $settings->closed_start_date,
            'closed_end_date' => $settings->closed_end_date,
        ]);
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Tabs::make('Settings')
                    ->tabs([
                        //  COMPANY PROFILE
                        Tab::make('Company Profile')
                            ->icon('heroicon-m-building-office')
                            ->schema([
                                Grid::make(2)->schema([
                                    TextInput::make('site_name')
                                        ->label('Company Name')
                                        ->required(),

                                    TextInput::make('company_phone')
                                        ->label('Phone Number')
                                        ->tel(),

                                    TextInput::make('company_email')
                                        ->label('Email Address')
                                        ->email(),

                                    Textarea::make('company_address')
                                        ->label('Office Address')
                                        ->rows(2),
                                ]),

                                Section::make('Branding')
                                    ->schema([
                                        FileUpload::make('site_logo')
                                            ->label('Company Logo')
                                            ->disk('public')
                                            ->image()
                                            ->imageEditor()
                                            ->directory('settings')
                                            ->columnSpanFull(),
                                    ]),
                            ]),

                        // TAB 2: BOOKING RULES
                        Tab::make('Booking Rules')
                            ->icon('heroicon-m-calculator')
                            ->schema([
                                Grid::make(3)->schema([
                                    TextInput::make('gratuity_percent')
                                        ->label('Gratuity (Tip)')
                                        ->numeric()
                                        ->suffix('%'),

                                    TextInput::make('tax_percent')
                                        ->label('Tax')
                                        ->numeric()
                                        ->suffix('%'),

                                    TextInput::make('credit_card_fee')
                                        ->label('Credit Card Fee')
                                        ->numeric()
                                        ->suffix('%'),
                                ]),
                            ]),

                        // TAB 3: FIXED CHARGES
                        Tab::make('Fixed Charges')
                            ->icon('heroicon-m-banknotes')
                            ->schema([
                                Grid::make(2)->schema([
                                    TextInput::make('stopover_fee')
                                        ->label('Stop Over Charge')
                                        ->numeric()
                                        ->prefix('$'),

                                    TextInput::make('luggage_fee')
                                        ->label('Extra Luggage Fee')
                                        ->numeric()
                                        ->prefix('$'),

                                    TextInput::make('child_seat_fee')
                                        ->label('Infant Seat Charge')
                                        ->numeric()
                                        ->prefix('$'),

                                    TextInput::make('regular_Seat_rules')
                                        ->label('Regular Seat Charge')
                                        ->numeric()
                                        ->prefix('$'),

                                    TextInput::make('booster_seat_fee')
                                        ->label('Booster Seat Charge')
                                        ->numeric()
                                        ->prefix('$'),
                                ]),
                            ]),
                        // TAB 4: LUGGAGE RULES
                        Tab::make('Luggage Rules')
                            ->icon('heroicon-m-briefcase')
                            ->schema([
                                Repeater::make('luggage_rules')
                                    ->label('Passenger & Luggage Settings')
                                    ->schema([
                                        Grid::make(2)->schema([
                                            TextInput::make('passenger_count')
                                                ->label('Max Passengers')
                                                ->numeric()
                                                ->required()
                                                ->placeholder('e.g. 4'),

                                            TextInput::make('allowed_children') // NEW FIELD
                                                ->label('Allowed Children')
                                                ->numeric()
                                                ->default(0)
                                                ->placeholder('e.g. 2'),
                                        ]),

                                        // Row 2: Luggage Details
                                        Grid::make(2)->schema([
                                            TextInput::make('max_luggage')
                                                ->label('Allowed Luggage')
                                                ->numeric()
                                                ->required()
                                                ->placeholder('e.g. 3'),

                                            TextInput::make('free_luggage')
                                                ->label('Free Luggage')
                                                ->numeric()
                                                ->required()
                                                ->placeholder('e.g. 1'),
                                        ]),
                                        Toggle::make('is_active')
                                            ->label('Active')
                                            ->default(true)
                                            ->inline(false)
                                            ->onColor('success'),
                                    ])
                                    ->columns(2)
                                    ->addActionLabel('Add New Rule')
                                    ->defaultItems(1)
                                    ->grid(1)
                                    ->itemLabel(fn(array $state): ?string => 'Rule for ' . ($state['passenger_count'] ?? '?') . ' Passengers'),
                            ]),

                        Tab::make('Availability')
                            ->icon('heroicon-m-clock')
                            ->schema([
                                Section::make('Booking Status Control')
                                    ->schema([
                                        Radio::make('booking_status')
                                            ->label('Current Status')
                                            ->options([
                                                'open' => 'Always Open (24/7)',
                                                'closed' => 'Force Closed',
                                                'scheduled' => 'Scheduled (Auto)',
                                            ])
                                            ->default('open')
                                            ->live()
                                            ->required()
                                            ->columnSpanFull(),

                                        TextInput::make('closing_message')
                                            ->label('Message for Customers')
                                            ->placeholder('e.g. We are currently closed.')
                                            ->visible(fn(Get $get) => $get('booking_status') !== 'open')
                                            ->columnSpanFull(),

                                        // --- SCHEDULE LOGIC ---
                                        Group::make([
                                            Select::make('schedule_type')
                                                ->label('Closing Type')
                                                ->options([
                                                    'daily' => 'Daily Recurring (Time)',
                                                    'weekly' => 'Weekly Recurring (Day)',
                                                    'specific_date' => 'Specific Date Range',
                                                ])
                                                ->default('daily')
                                                ->live()
                                                ->required(),

                                            // 1. Daily Time
                                            Group::make([
                                                TimePicker::make('daily_start_time')->label('Close From')->seconds(false),
                                                TimePicker::make('daily_end_time')->label('Open At')->seconds(false),
                                            ])
                                                ->visible(fn(Get $get) => $get('schedule_type') === 'daily')
                                                ->columns(2),

                                            // 2. Weekly Days
                                            Group::make([
                                                CheckboxList::make('weekly_off_days')
                                                    ->label('Select Closed Days')
                                                    ->options([
                                                        'Monday' => 'Monday',
                                                        'Tuesday' => 'Tuesday',
                                                        'Wednesday' => 'Wednesday',
                                                        'Thursday' => 'Thursday',
                                                        'Friday' => 'Friday',
                                                        'Saturday' => 'Saturday',
                                                        'Sunday' => 'Sunday',
                                                    ])
                                                    ->columns(4),
                                            ])
                                                ->visible(fn(Get $get) => $get('schedule_type') === 'weekly'),

                                            // 3. Specific Date
                                            Group::make([
                                                DateTimePicker::make('closed_start_date')->label('Close From'),
                                                DateTimePicker::make('closed_end_date')->label('Open At'),
                                            ])
                                                ->visible(fn(Get $get) => $get('schedule_type') === 'specific_date')
                                                ->columns(2),

                                        ])
                                            ->visible(fn(Get $get) => $get('booking_status') === 'scheduled')
                                            ->columnSpanFull(),
                                    ]),
                            ]),
                    ])->columnSpanFull(),
            ])
            ->statePath('data');
    }

    // save button
    public function save(): void
    {
        $settings = app(GeneralSettings::class);
        $data = $this->form->getState();

        // --- Company Profile ---
        $settings->site_name = $data['site_name'] ?? $settings->site_name;

        // Logo Logic
        if (isset($data['site_logo']) && $settings->site_logo && $settings->site_logo !== $data['site_logo']) {
            Storage::disk('public')->delete($settings->site_logo);
        }
        $settings->site_logo = $data['site_logo'] ?? $settings->site_logo;

        $settings->company_phone = $data['company_phone'] ?? null;
        $settings->company_email = $data['company_email'] ?? null;
        $settings->company_address = $data['company_address'] ?? null;

        // --- Booking Rules ---
        $settings->gratuity_percent = $data['gratuity_percent'] ?? 0;
        $settings->tax_percent = $data['tax_percent'] ?? 0;
        $settings->credit_card_fee = $data['credit_card_fee'] ?? 0;

        // --- Fixed Charges ---
        $settings->child_seat_fee = $data['child_seat_fee'] ?? 0; //infant seat
        $settings->regular_Seat_rules = $data['regular_Seat_rules'] ?? 0;        $settings->booster_seat_fee = $data['booster_seat_fee'] ?? 0;
        $settings->stopover_fee = $data['stopover_fee'] ?? 0;
        $settings->luggage_fee = $data['luggage_fee'] ?? 0;

        // --- Luggage Rules ---
        $settings->luggage_rules = $data['luggage_rules'] ?? [];
        // --- Availability Settings ---
        $settings->booking_status = $data['booking_status'] ?? 'open';
        $settings->closing_message = $data['closing_message'] ?? null;
        $settings->schedule_type = $data['schedule_type'] ?? 'daily';

        $settings->daily_start_time = $data['daily_start_time'] ?? null;
        $settings->daily_end_time = $data['daily_end_time'] ?? null;
        $settings->weekly_off_days = $data['weekly_off_days'] ?? [];
        $settings->closed_start_date = $data['closed_start_date'] ?? null;
        $settings->closed_end_date = $data['closed_end_date'] ?? null;

        $settings->save();

        Notification::make()
            ->title('Settings updated successfully')
            ->success()
            ->send();
    }

    protected function getFormActions(): array
    {
        return [
            Action::make('save')
                ->label('Save Changes')
                ->submit('save'),
        ];
    }
}

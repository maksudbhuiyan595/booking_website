<?php

namespace App\Filament\Pages;

use App\Settings\GeneralSettings;
use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Illuminate\Support\Facades\Storage;

class ManageSettings extends Page
{
    // v4 Strict Type Fix
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
            'child_seat_fee' => $settings->child_seat_fee,
            'booster_seat_fee' => $settings->booster_seat_fee,
            'stopover_fee' => $settings->stopover_fee,
            'luggage_fee' => $settings->luggage_fee,
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

                                    TextInput::make('booster_seat_fee')
                                        ->label('Booster Seat Charge')
                                        ->numeric()
                                        ->prefix('$'),
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

        if ($settings->site_logo && $settings->site_logo !== $data['site_logo']) {
        Storage::disk('public')->delete($settings->site_logo);
    }

        $settings->site_name = $data['site_name'];
        $settings->site_logo = $data['site_logo'];
        $settings->company_phone = $data['company_phone'];
        $settings->company_email = $data['company_email'];
        $settings->company_address = $data['company_address'];

        $settings->gratuity_percent = $data['gratuity_percent'];
        $settings->tax_percent = $data['tax_percent'];
        $settings->credit_card_fee = $data['credit_card_fee'];

        $settings->child_seat_fee = $data['child_seat_fee'];
        $settings->booster_seat_fee = $data['booster_seat_fee'];
        $settings->stopover_fee = $data['stopover_fee'];
        $settings->luggage_fee = $data['luggage_fee'];

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

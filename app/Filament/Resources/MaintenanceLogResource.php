<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MaintenanceLogResource\Pages;
use App\Models\MaintenanceLog;
use App\Models\Device;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class MaintenanceLogResource extends Resource
{
    protected static ?string $model = MaintenanceLog::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-check';
    protected static ?string $navigationLabel = 'Karbantartási naplók';
    protected static ?string $pluralModelLabel = 'Karbantartási naplók';
    protected static ?string $modelLabel = 'Karbantartási napló';

    public static function getNavigationGroup(): string
    {
        return __('module_names.navigation_groups.maintenance');
    }

    public static function form(Form $form): Form
    {
        
        return $form
            ->schema([
                Forms\Components\Select::make('device_id')
                    ->label('Eszköz')
                    ->relationship('device', 'name') 
                    ->required()
                    ->searchable(),

                Forms\Components\TextInput::make('performed_by')
                    ->label('Karbantartó neve')
                    ->required()
                    ->maxLength(255),

                Forms\Components\Textarea::make('description')
                    ->label('Leírás')
                    ->nullable()
                    ->rows(3),

                Forms\Components\DatePicker::make('maintenance_date')
                    ->label('Karbantartás dátuma')
                    ->required(),

                Forms\Components\DatePicker::make('next_due_date')
                    ->label('Következő esedékes dátum')
                    ->nullable(),

                Forms\Components\Select::make('status')
                    ->label('Állapot')
                    ->options([
                        'completed' => 'Befejezve',
                        'pending' => 'Folyamatban',
                        'skipped' => 'Kihagyva',
                    ])
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('device.name')
                    ->label('Eszköz')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('performed_by')
                    ->label('Karbantartó'),

                Tables\Columns\TextColumn::make('maintenance_date')
                    ->label('Dátum')
                    ->date(),

                Tables\Columns\TextColumn::make('status')
                    ->label('Állapot')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'completed' => 'success',
                        'pending' => 'warning',
                        'skipped' => 'danger',
                    }),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Létrehozva')
                    ->dateTime('Y.m.d H:i')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('Állapot')
                    ->options([
                        'completed' => 'Befejezve',
                        'pending' => 'Folyamatban',
                        'skipped' => 'Kihagyva',
                    ]),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMaintenanceLogs::route('/'),
            'create' => Pages\CreateMaintenanceLog::route('/create'),
            'view' => Pages\ViewMaintenanceLog::route('/{record}'),
            'edit' => Pages\EditMaintenanceLog::route('/{record}/edit'),
        ];
    }
}

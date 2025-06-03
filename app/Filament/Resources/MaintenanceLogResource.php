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
use TangoDevIt\FilamentEmojiPicker\EmojiPickerAction;

class MaintenanceLogResource extends Resource
{
    protected static ?string $model = MaintenanceLog::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-check';

    public static function getNavigationGroup(): string
    {
        return __('module_names.navigation_groups.maintenance');
    }
    public static function getModelLabel(): string
    {
        return __('module_names.maintenance_logs.label');
    }
    public static function getPluralModelLabel(): string
    {
        return __('module_names.maintenance_logs.plural_label');
    }

    public static function form(Form $form): Form
    {
        
        return $form
            ->schema([
                Forms\Components\Select::make('device_id')
                    ->label(__('fields.device'))
                    ->relationship('device', 'name') 
                    ->required()
                    ->searchable(),

                Forms\Components\TextInput::make('performed_by')
                    ->label(__('fields.repairer'))
                    ->required()
                    ->suffixAction(EmojiPickerAction::make('emoji-title'))
                    ->maxLength(255),

                Forms\Components\Textarea::make('description')
                    ->label(__('fields.description'))
                    ->nullable()
                    ->hintAction(EmojiPickerAction::make('emoji-messagge'))
                    ->rows(3),

                Forms\Components\DatePicker::make('maintenance_date')
                    ->label(__('fields.finish_date'))
                    ->required(),

                Forms\Components\DatePicker::make('next_due_date')
                    ->label(__('fields.next_date'))
                    ->nullable(),

                Forms\Components\Select::make('status')
                    ->label(__('fields.status'))
                    ->options([
                        'completed' =>(__('fields.completed')),
                        'pending' => (__('fields.pending')),
                        'skipped' => (__('fields.skipped')),
                    ])
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('device.name')
                    ->label(__('fields.device'))
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('performed_by')
                    ->label(__('fields.repairer')),

                Tables\Columns\TextColumn::make('maintenance_date')
                    ->label(__('fields.finish_date'))
                    ->date(),

                Tables\Columns\TextColumn::make('status')
                    ->label(__('fields.status'))
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'completed' => 'success',
                        'pending' => 'warning',
                        'skipped' => 'danger',
                    }),

                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('fields.created_at'))
                    ->dateTime('Y.m.d H:i')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label(__('fields.status'))
                    ->options([
                        'completed' => (__('fields.completed')),
                        'pending' => (__('fields.pending')),
                        'skipped' => (__('fields.skipped')),
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

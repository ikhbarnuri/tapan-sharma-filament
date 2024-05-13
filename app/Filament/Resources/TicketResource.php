<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TicketResource\Pages;
use App\Filament\Resources\TicketResource\RelationManagers;
use App\Models\Role;
use App\Models\Ticket;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class TicketResource extends Resource
{
    protected static ?string $model = Ticket::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')
                    ->autofocus()
                    ->required(),
                Forms\Components\Textarea::make('description')
                    ->rows(3),
                Forms\Components\Select::make('status')
                    ->options(self::$model::STATUS)
                    ->required()
                    ->in(self::$model::STATUS),
                Forms\Components\Select::make('priority')
                    ->options(self::$model::PRIORITY)
                    ->required()
                    ->in(self::$model::PRIORITY),
                Forms\Components\Select::make('assigned_to')
                    ->options(
                        User::query()
                            ->whereHas('roles', function (Builder $query) {
                                $query->where('name', Role::ROLES['Agent']);
                            })
                            ->pluck('name', 'id')
                            ->toArray()
                    )
                    ->required(),
                Forms\Components\Textarea::make('comment')
                    ->rows(3),
            ])
            ->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn(Builder $query) => auth()
                ->user()
                ->hasRole(Role::ROLES['Admin'])
                ? $query
                : $query->where('assigned_to', auth()->id()))
            ->defaultSort('created_at', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->description(fn(Ticket $record): ?string => $record?->description ?? null)
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->badge(),
                Tables\Columns\TextColumn::make('priority')
                    ->badge(),
                Tables\Columns\TextColumn::make('assignedBy.name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('assignedTo.name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextInputColumn::make('comment'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options(self::$model::STATUS)
                    ->placeholder('Filter By Status'),
                Tables\Filters\SelectFilter::make('priority')
                    ->options(self::$model::PRIORITY)
                    ->placeholder('Filter By Priority'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
            RelationManagers\CategoriesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTickets::route('/'),
            'create' => Pages\CreateTicket::route('/create'),
            'edit' => Pages\EditTicket::route('/{record}/edit'),
        ];
    }
}

<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TextMessageResource\Pages;
use App\Filament\Resources\TextMessageResource\RelationManagers;
use App\Models\TextMessage;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn as TextColumnAlias;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TextMessageResource extends Resource
{
    protected static ?string $model = TextMessage::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->sortable(),
                Tables\Columns\TextColumn::make('sendBy.name')
                    ->searchable()
                    ->sortable()
                    ->default('-')
                    ->label('Message Sent By'),
                Tables\Columns\TextColumn::make('sendTo.name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('message')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->badge(),
                Tables\Columns\TextColumn::make('remarks')
                    ->default('-')
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->sortable()
                    ->date(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options(TextMessage::STATUS),
            ])
            ->actions([
                //
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
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTextMessages::route('/'),
            // 'create' => Pages\CreateTextMessage::route('/create'),
            // 'edit' => Pages\EditTextMessage::route('/{record}/edit'),
        ];
    }
}

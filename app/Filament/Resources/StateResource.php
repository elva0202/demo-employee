<?php

namespace App\Filament\Resources;

use App\Filament\Resources\StateResource\Pages;
use App\Filament\Resources\StateResource\RelationManagers;
use App\Models\State;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class StateResource extends Resource
{
    protected static ?string $model = State::class;

    //設置圖標（ICON)，$navigationIcon字串類型，通常用於指定圖標
    protected static ?string $navigationIcon = 'heroicon-o-building-library';
    //設置分類名稱為且為字串，？代表可以是null
    protected static ?string $navigationLabel = 'State';
    //設置模型名稱為員工國家且為字串，？代表可以是null
    protected static ?string $modelLabel = 'States';
    //設置歸屬折疊分類
    protected static ?string $navigationGroup = 'System Management';

    //設置排序
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //生成輸入匡對應country_id欄位(國家_id)
                Forms\Components\TextInput::make('country_id')
                    //必填
                    ->required()
                    //數字
                    ->numeric(),

                Forms\Components\TextInput::make('name')
                    //必填
                    ->required()
                    //最大長度
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
            ])
            ->filters([
                //
            ])
            ->actions([
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
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListStates::route('/'),
            'create' => Pages\CreateState::route('/create'),
            'edit' => Pages\EditState::route('/{record}/edit'),
        ];
    }
}

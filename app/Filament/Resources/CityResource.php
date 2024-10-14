<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CityResource\Pages;
use App\Filament\Resources\CityResource\RelationManagers;
use App\Filament\Resources\CityResource\RelationManagers\EmployeesRelationManager;
use App\Models\City;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;


class CityResource extends Resource
{
    protected static ?string $model = City::class;
    //設置圖標（ICON)，$navigationIcon字串類型，通常用於指定圖標
    protected static ?string $navigationIcon = 'heroicon-o-building-office-2';
    //設置分類名稱為且為字串，？代表可以是null
    protected static ?string $navigationLabel = 'City';
    //設置模型名稱為員工國家且為字串，？代表可以是null
    protected static ?string $modelLabel = 'City';
    //設置歸屬折疊分類
    protected static ?string $navigationGroup = 'System Management';

    ///設置排序
    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('City details')
                    ->schema([
                        Forms\Components\Select::make('state_id')
                            ->relationship(name: 'state', titleAttribute: 'name')    //關聯到country，使用name欄位作為標題
                            ->searchable()  //添加搜尋功能
                            ->preload()     //預載入選項（提前加載國家名）
                            ->required(),   //必填
                        Forms\Components\TextInput::make('name')
                            //必填
                            ->required()
                            //最大長度
                            ->maxLength(255),
                    ])
                //生成輸入匡對應狀態id

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('state.name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateActions([
                Tables\Actions\CreateAction::make(),
            ]);
    }
    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Section::make('City Info')
                    ->schema([
                        TextEntry::make('state.name')->label('State Name'),
                        TextEntry::make('name')->label('City name'),
                    ])->columns(2)
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
            'index' => Pages\ListCities::route('/'),
            'create' => Pages\CreateCity::route('/create'),
            'view' => Pages\ViewCity::route('/{record}'),
            'edit' => Pages\EditCity::route('/{record}/edit'),
        ];
    }
}

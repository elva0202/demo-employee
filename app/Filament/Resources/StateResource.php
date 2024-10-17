<?php

namespace App\Filament\Resources;

use App\Filament\Resources\StateResource\Pages;
use App\Filament\Resources\StateResource\RelationManagers;
use App\Filament\Resources\StateResource\RelationManagers\CitiesRelationManager;
use App\Filament\Resources\CountryResource\RelationManagers\EmployeesRelationManager;
use App\Models\State;
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
        $countries = \App\Models\Country::all();

        return $form
            ->schema([
                //生成輸入匡對應country_id欄位(國家_id)
                Forms\Components\Select::make('country_id')
                    ->relationship('country', 'name')  //關聯到country，使用name欄位作為標題
                    ->searchable()  //添加搜尋功能
                    ->preload()     //預載入選項（提前加載國家名）
                    ->required(),   //必填欄位
                // Forms\Components\Select::make('status')
                //     ->options([
                //         'active'=> 'Active',
                //         'inactive'=> 'Inactive',
                //     ])
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
                Tables\Columns\TextColumn::make('country.name')
                    ->sortable()
                    ->searchable(isIndividual: true, isGlobal: false),//查詢欄位
                Tables\Columns\TextColumn::make('name')
                    ->label('State name')
                    ->sortable() //排序
                    //->searchable(),
                    ->searchable(isIndividual: true, isGlobal: false),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true), //切換顯示或隱藏
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])->defaultSort('country.name', 'desc')
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
            ]);
    }
    
    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Section::make('State Info')
                    ->schema([
                        TextEntry::make('country.name')->label('Country Name'),
                        TextEntry::make('name')->label('State name'),
                    ])->columns(2)
            ]);
    }

    public static function getRelations(): array
    //
    {
        return [
            CitiesRelationManager::class,
            EmployeesRelationManager::class,
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

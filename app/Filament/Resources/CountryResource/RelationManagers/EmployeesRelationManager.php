<?php

namespace App\Filament\Resources\CountryResource\RelationManagers;

use App\Models\City;
use App\Models\State;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Collection;

class EmployeesRelationManager extends RelationManager
{
    protected static string $relationship = 'employees';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Relationships')
                    ->schema([
                        //在表單區塊中設置相關欄位
                        Forms\Components\Select::make(name: 'country_id')
                            ->relationship(name: 'country', titleAttribute: 'name')  //關聯到country，使用name欄位作為標題
                            ->searchable()  //添加搜尋功能
                            ->preload()     //預載入選項（提前加載國家名）
                            ->live()        //監聽
                            //使用afterStateUpdated 監聽country欄位點選叉叉(狀態更新）清除時連動State欄位的選項
                            ->afterStateUpdated(function ($set) {
                                $set('state_id', null);
                                $set('city_id', null);
                            })
                            ->required(),   //必填欄位
                        Forms\Components\Select::make('state_id')
                            ->options(fn($get) => State::query()
                                ->where('country_id', $get('country_id'))
                                ->pluck('name', 'id'))
                            ->searchable()
                            ->preload()
                            ->live()
                            ->afterStateUpdated(fn($set) => $set('city_id', null)),
                        // ->required(),
                        Forms\Components\Select::make('city_id')
                            ->options(fn($get) => City::query()
                                ->where('state_id', $get('state_id'))
                                ->pluck('name', 'id'))
                            ->searchable()
                            ->preload()
                            ->live()
                            ->required(),
                        Forms\Components\Select::make('department_id')
                            ->relationship(name: 'department', titleAttribute: 'name')
                            ->searchable()
                            ->preload()
                            ->required(),
                    ])->columns(2),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('first_name')
            ->columns([
                TTables\Columns\TextColumn::make('first_name')
                ->searchable()//可查詢
                ->sortable(),//用於排序（升序/降序)
            Tables\Columns\TextColumn::make('last_name')
                ->searchable(),//可查詢
            Tables\Columns\TextColumn::make('middle_name')
                ->searchable()//可查詢
                ->toggleable(isToggledHiddenByDefault: true),//切換顯示或隱藏欄位
            Tables\Columns\TextColumn::make('address')
                ->searchable()//可查詢
                ->toggleable(isToggledHiddenByDefault: true),//切換顯示或隱藏欄位
            Tables\Columns\TextColumn::make('zip_code')
                ->searchable(),//可查詢
            Tables\Columns\TextColumn::make('date_of_birth')
                ->date()//格式化日期
                ->sortable()//用於排序（升序/降序)
                ->toggleable(isToggledHiddenByDefault: true),//切換顯示或隱藏欄位
            Tables\Columns\TextColumn::make('date_hierd')
                ->date()//格式化日期
                ->sortable(),//用於排序（升序/降序)
            Tables\Columns\TextColumn::make('created_at')
                ->dateTime()//格式化日期與時間
                ->sortable()//用於排序（升序/降序)
                ->toggleable(isToggledHiddenByDefault: true),//切換顯示或隱藏欄位
            Tables\Columns\TextColumn::make('updated_at')
                ->dateTime()//格式化日期與時間
                ->sortable()//用於排序（升序/降序)
                ->toggleable(isToggledHiddenByDefault: true),//切換顯示或隱藏欄位
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])->emptyStateActions([
                Tables\Actions\CreateAction::make(),
            ]);
            ;
    }
}

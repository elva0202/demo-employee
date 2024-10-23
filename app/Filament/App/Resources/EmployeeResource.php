<?php

namespace App\Filament\App\Resources;

use App\Filament\App\Resources\EmployeeResource\Pages;
use App\Filament\App\Resources\EmployeeResource\RelationManagers;
use App\Models\City;
use App\Models\Employee;
use App\Models\State;
use Filament\Facades\Filament;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Collection;

class EmployeeResource extends Resource
{
    protected static ?string $model = Employee::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // //創建表單名稱User Name
                // Forms\Components\Section::make('Relationships')
                //     ->schema([
                //         //在表單區塊中設置相關欄位
                //         Forms\Components\Select::make(name: 'country_id')
                //             ->relationship(name: 'country', titleAttribute: 'name')  //關聯到country，使用name欄位作為標題
                //             ->searchable()  //添加搜尋功能
                //             ->preload()     //預載入選項（提前加載國家名）
                //             ->live()        //監聽
                //             //使用afterStateUpdated 監聽country欄位點選叉叉(狀態更新）清除時連動State欄位的選項
                //             ->afterStateUpdated(function ($set) {
                //                 $set('state_id', null);
                //                 $set('city_id', null);
                //             })
                //             ->required(),   //必填欄位
                //         Forms\Components\Select::make('state_id')
                //             ->options(fn($get) => State::query()
                //                 ->where('country_id', $get('country_id'))
                //                 ->pluck('name', 'id'))
                //             ->searchable()
                //             ->preload()
                //             ->live()
                //             ->afterStateUpdated(fn($set) => $set('city_id', null)),
                //         // ->required(),
                //         Forms\Components\Select::make('city_id')
                //             ->options(fn($get) => City::query()
                //                 ->where('state_id', $get('state_id'))
                //                 ->pluck('name', 'id'))
                //             ->searchable()
                //             ->preload()
                //             ->live()
                //             ->required(),
                //         Forms\Components\Select::make('department_id')
                //             ->relationship(name: 'department', titleAttribute: 'name', )
                //             ->searchable()
                //             ->preload()
                //             ->required(),
                //     ])->columns(2),
                // //創建表單名稱User Name
                // Forms\Components\Section::make('User Name')
                //     ->description('Put the user name details in.')
                //     ->schema([
                //         //在表單區塊中設置相關欄位
                //         Forms\Components\TextInput::make('first_name')
                //             ->required()
                //             ->maxLength(255),
                //         Forms\Components\TextInput::make('last_name')
                //             ->required()
                //             ->maxLength(255),
                //         Forms\Components\TextInput::make('middle_name')
                //             ->required()
                //             ->maxLength(255),
                //     ])->columns(3),//設定一行中有幾個欄位   
                // Forms\Components\Section::make('User address')
                //     ->schema([
                //         Forms\Components\TextInput::make('address')
                //             ->required()
                //             ->maxLength(255),
                //         Forms\Components\TextInput::make('zip_code')
                //             ->required()
                //             ->maxLength(255),
                //     ])->columns(2),
                // Forms\Components\Section::make('Dates')
                //     ->schema([
                //         Forms\Components\DatePicker::make('date_of_birth')
                //             ->native(false)
                //             ->displayFormat('d/m/Y')
                //             ->required(),
                //         Forms\Components\DatePicker::make('date_hired')
                //             ->native(false)
                //             ->displayFormat('d/m/Y')
                //             ->required(),
                //     ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                // Tables\Columns\TextColumn::make('country.name')
                //     ->sortable()
                //     ->searchable(),
                // Tables\Columns\TextColumn::make('first_name')
                //     ->searchable()
                //     ->sortable(),
                // Tables\Columns\TextColumn::make('last_name')
                //     ->searchable(),
                // Tables\Columns\TextColumn::make('middle_name')
                //     ->searchable()
                //     ->toggleable(isToggledHiddenByDefault: true),
                // Tables\Columns\TextColumn::make('address')
                //     ->searchable()
                //     ->toggleable(isToggledHiddenByDefault: true),
                // Tables\Columns\TextColumn::make('zip_code')
                //     ->searchable(),
                // Tables\Columns\TextColumn::make('date_of_birth')
                //     ->date()
                //     ->sortable()
                //     ->toggleable(isToggledHiddenByDefault: true),
                // Tables\Columns\TextColumn::make('date_hired')
                //     ->date()
                //     ->sortable(),
                // Tables\Columns\TextColumn::make('created_at')
                //     ->dateTime()
                //     ->sortable()
                //     ->toggleable(isToggledHiddenByDefault: true),
                // Tables\Columns\TextColumn::make('updated_at')
                //     ->dateTime()
                //     ->sortable()
                //     ->toggleable(isToggledHiddenByDefault: true),
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
            ]);
    }

    // public static function infolist(Infolist $infolist): Infolist
    // {
    //     return $infolist
    //         ->schema([
    //             Section::make('Relationships')
    //                 ->schema([
    //                     TextEntry::make('country.name'),
    //                     TextEntry::make('state.name'),
    //                     TextEntry::make('city.name'),
    //                     TextEntry::make('department.name'),
    //                 ])->columns(2),
    //             Section::make('Name')
    //                 ->schema([
    //                     TextEntry::make('first_name'),
    //                     TextEntry::make('middle_name'),
    //                     TextEntry::make('last_name'),
    //                 ])->columns(3),
    //             Section::make('Address')
    //                 ->schema([
    //                     TextEntry::make('address'),
    //                     TextEntry::make('zip_code'),
    //                 ])->columns(2)
    //         ]);
    // }


    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListEmployees::route('/'),
            'create' => Pages\CreateEmployee::route('/create'),
            'view' => Pages\ViewEmployee::route('/{record}'),
            'edit' => Pages\EditEmployee::route('/{record}/edit'),
        ];
    }
}

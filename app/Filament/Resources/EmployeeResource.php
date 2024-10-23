<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EmployeeResource\Pages;
use App\Filament\Resources\CountryResource\RelationManagers\EmployeesRelationManager;
use App\Filament\Resources\EmployeeResource\RelationManagers;
use App\Models\City;
use App\Models\Employee;
use App\Models\State;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;



class EmployeeResource extends Resource
{   //指向模型
    protected static ?string $model = Employee::class;
    //圖示
    protected static ?string $navigationIcon = 'heroicon-o-user-group';
    //導航分組名稱
    protected static ?string $navigationGroup = 'Employee Management';
    //主要標示
    protected static ?string $recordTitleAttribute = 'first_name';
    //查詢first_name回傳對應lastname
    public static function getGlobalSearchResultTitle(Model $record): string
    {
        return $record->last_name;
    }

    public static function getGloballySearchableAttributes(): array
    {   //根據三個欄位進行查詢顯示對應紀錄
        return ['first_name', 'last_name', 'middle_name'];
    }

    public static function getGlobalSearchResultDetails(Model $record): array
    {   //查詢時返回國家欄位
        return [
            'Country' => $record->country->name
        ];
    }

    public static function getGlobalSearchEloquentQuery(): Builder
    {   //
        return parent::getGlobalSearchEloquentQuery()->with(['country']);
    }

    public static function getNavigationBadge(): ?string
    {   //顯示總數
        return static::getModel()::count();
    }

    public static function getNavigationBadgeColor(): string|array|null
    {   //超過10則跳警告，
        return static::getModel()::count() > 10 ? 'warning' : 'success';
    }


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //創建表單名稱User Name
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
                //創建表單名稱User Name
                Forms\Components\Section::make('User Name')
                    ->description('Put the user name details in.')
                    ->schema([
                        //在表單區塊中設置相關欄位
                        Forms\Components\TextInput::make('first_name')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('last_name')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('middle_name')
                            ->required()
                            ->maxLength(255),
                    ])->columns(3),//設定一行中有幾個欄位   
                Forms\Components\Section::make('User address')
                    ->schema([
                        Forms\Components\TextInput::make('address')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('zip_code')
                            ->required()
                            ->maxLength(255),
                    ])->columns(2),
                Forms\Components\Section::make('Dates')
                    ->schema([
                        Forms\Components\DatePicker::make('date_of_birth')
                            ->native(false)
                            ->displayFormat('d/m/Y')
                            ->required(),
                        Forms\Components\DatePicker::make('date_hired')
                            ->native(false)
                            ->displayFormat('d/m/Y')
                            ->required(),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('country.name')
                    ->sortable()//用於排序（升序/降序)
                    ->searchable(),//查詢欄位
                Tables\Columns\TextColumn::make('country_id')
                    ->numeric()//數字格式
                    ->sortable(),//用於排序（升序/降序)
                Tables\Columns\TextColumn::make('state_id')
                    ->numeric()//數字格式
                    ->sortable(),//用於排序（升序/降序)
                Tables\Columns\TextColumn::make('city_id')
                    ->numeric()//數字格式
                    ->sortable(),//用於排序（升序/降序)
                Tables\Columns\TextColumn::make('department_id')
                    ->numeric()//數字格式
                    ->sortable(),//用於排序（升序/降序)
                Tables\Columns\TextColumn::make('first_name')
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
                Tables\Columns\TextColumn::make('date_hired')
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

            //定義篩選器
            ->filters([
                SelectFilter::make('Department')//篩選器名稱
                    ->relationship('department', 'name')//設定department關聯顯示name欄位
                    ->searchable()//添加查詢功能
                    ->preload()//預先加載篩選選項
                    ->label('Filter by Department')//自定義篩選器標籤名稱
                    ->indicator('Department'), //標示篩選條件
                Filter::make('created_at')
                    ->form([
                        DatePicker::make('created_from'),
                        DatePicker::make('created_until'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['created_from'],
                                fn(Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                            )
                            ->when(
                                $data['created_until'],
                                fn(Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
                            );
                    })
                    ->indicateUsing(function (array $data): array {
                        $indicators = [];
                        if ($data['created_from'] ?? null) {
                            $indicators['created_from'] = 'Created from ' . Carbon::parse($data['created_from'])->toFormattedDateString();
                        }
                        if ($data['created_until'] ?? null) {
                            $indicators['created_until'] = 'Created until ' . Carbon::parse($data['created_until'])->toFormattedDateString();
                        }

                        return $indicators;
                    }),
            ])

            //定義操作按鈕
            ->actions([
                //查看
                Tables\Actions\ViewAction::make(),
                //編輯
                Tables\Actions\EditAction::make(),
                //刪除
                Tables\Actions\DeleteAction::make()
                    ->successNotification(
                        Notification::make()
                            ->success() //設定通知為成功時
                            ->title('Employee created.')
                            ->body('The Employee created successfully.')
                    )
            ])

            //定義批量操作
            ->bulkActions([
                //批量操作
                Tables\Actions\BulkActionGroup::make([
                    //批量刪除
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Section::make('Relationships')
                    ->schema([
                        TextEntry::make('country.name'),
                        TextEntry::make('state.name'),
                        TextEntry::make('city.name'),
                        TextEntry::make('department.name'),
                    ])->columns(2),
                Section::make('Name')
                    ->schema([
                        TextEntry::make('first_name'),
                        TextEntry::make('middle_name'),
                        TextEntry::make('last_name'),
                    ])->columns(3),
                Section::make('Address')
                    ->schema([
                        TextEntry::make('address'),
                        TextEntry::make('zip_code'),
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
            'index' => Pages\ListEmployees::route('/'),
            'create' => Pages\CreateEmployee::route('/create'),
            'view' => Pages\ViewEmployee::route('/{record}'),
            'edit' => Pages\EditEmployee::route('/{record}/edit'),
        ];
    }
}

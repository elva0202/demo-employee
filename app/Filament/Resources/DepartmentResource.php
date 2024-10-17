<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DepartmentResource\Pages;
use App\Filament\Resources\DepartmentResource\RelationManagers;
use App\Models\Department;
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

class DepartmentResource extends Resource
{
    protected static ?string $model = Department::class;
    //設置圖標（ICON)，$navigationIcon字串類型，通常用於指定圖標
    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';
    //設置分類名稱為且為字串，？代表可以是null
    protected static ?string $navigationLabel = 'Department';
    //設置模型名稱為員工國家且為字串，？代表可以是null
    protected static ?string $modelLabel = 'Department';
    //設置歸屬折疊分類
    protected static ?string $navigationGroup = 'System Management';

    //設置排序
    protected static ?int $navigationSort = 4;

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
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),//用於排序（升序/降序)
                Tables\Columns\TextColumn::make('employees_count')->counts('employees')
                    ->sortable(),//用於排序（升序/降序)
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()//用於排序（升序/降序)
                    ->toggleable(isToggledHiddenByDefault: true),//切換顯示或隱藏欄位
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()//用於排序（升序/降序)
                    ->toggleable(isToggledHiddenByDefault: true),//切換顯示或隱藏欄位
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
                Section::make('Department Info')
                    ->schema([
                        TextEntry::make('name'),
                        TextEntry::make('employees_count')
                            ->state(function (Department $record): int {
                                return $record->employees()->count();
                            }),
                    ])->columns(2)
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
            'index' => Pages\ListDepartments::route('/'),
            'create' => Pages\CreateDepartment::route('/create'),
            //'view' => Pages\ViewDepartment::route('/{record}'),
            'edit' => Pages\EditDepartment::route('/{record}/edit'),
        ];
    }
}

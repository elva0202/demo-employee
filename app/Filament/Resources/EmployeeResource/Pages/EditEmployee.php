<?php

namespace App\Filament\Resources\EmployeeResource\Pages;

use App\Filament\Resources\EmployeeResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Filament\Notifications\Notification;


class EditEmployee extends EditRecord
{
    protected static string $resource = EmployeeResource::class;

    //設置頁面頂部按鈕
    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),//查看按鈕
            Actions\DeleteAction::make(),//刪除按鈕
        ];
    }

    protected function getSavedNotification(): ?Notification
    {//建立成功時觸發通知
        return Notification::make()
            ->success() //設定通知為成功時
            ->title('Employee created.')
            ->body('The Employee created successfully.'); 
    }
}

<?php

namespace App\Filament\Resources\EmployeeResource\Pages;

use App\Filament\Resources\EmployeeResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Filament\Notifications\Notification;

class CreateEmployee extends CreateRecord
{
    protected static string $resource = EmployeeResource::class;

    
    protected function getCreatedNotification(): ?Notification
    {//建立成功時觸發通知
        return Notification::make()
            ->success() //設定通知為成功時
            ->title('Employee created.')//通知標題創建員工
            ->body('The Employee created successfully.'); //通知內容員工創建成功
    }
}

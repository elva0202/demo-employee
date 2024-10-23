<?php

namespace App\Filament\App\Resources\EmployeeResource\Pages;

use App\Filament\App\Resources\EmployeeResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
//定義類別
class CreateEmployee extends CreateRecord
{ //新增員工資料介面EmployeeResource資源與CreateEmployee連結
    protected static string $resource = EmployeeResource::class;
}

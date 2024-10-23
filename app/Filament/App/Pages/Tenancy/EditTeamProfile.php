<?php

namespace App\Filament\App\Pages\Tenancy;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Pages\Tenancy\EditTenantProfile;
use Illuminate\Database\Eloquent\Model;


//建立編輯團隊資料頁面
class EditTeamProfile extends EditTenantProfile
{
      public static function getLabel(): string
      {       //標籤名稱
            return 'Team profile';
      }

      public function form(Form $form): Form
      {
            return $form
                  ->schema([
                        TextInput::make('name'),
                        TextInput::make('slug'),
                  ]);
      }
}
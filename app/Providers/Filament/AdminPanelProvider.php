<?php

namespace App\Providers\Filament;

use App\Http\Middleware\EnsureIsAdmin;
use App\Http\Middleware\VerifyIsAdmin;
use App\Models\Team;
use App\Filament\App\Pages\Tenancy\RegisterTeam;
use App\Filament\App\Pages\Tenancy\EditTeamProfile;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\MenuItem;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;


class AdminPanelProvider extends PanelProvider
{//組織版面結構樣式，路徑，頁面，資源，功能
    public function panel(Panel $panel): Panel
    {
        return $panel
            //->default()//預設ailament面板
            ->id('admin')//識別區分panel面板通常用於管理者
            ->path('admin')//路徑
            //->login()//登入頁面
            //->registration()//新用戶註冊
            ->colors([
                'danger' => Color::Red,//警示
                'gray' => Color::Slate, //次要
                'info' => Color::Blue,//通知
                'primary' => Color::Indigo,//主要背景色
                'success' => Color::Emerald,//成功
                'warning' => Color::Orange,//注意
            ])
            ->font('Edu Australia VIC WA NT Hand Dots')//版面字型
            ->favicon(asset('images/favicon.jpeg'))//瀏覽器ＩＣＯＮ
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                    //管理版面儀表板頁面登入後顯示設定畫面
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                Widgets\AccountWidget::class,
                Widgets\FilamentInfoWidget::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ]);
        // //處理身份驗證
        // ->authMiddleware([
        //     Authenticate::class,
        // ])
        // // //關聯
        // ->tenant(Team::class, ownershipRelationship: 'team')
        // //當用戶沒有團隊時跳轉到註冊頁面
        // ->tenantRegistration(RegisterTeam::class)
        // //編輯個人資料頁面
        // ->tenantProfile(EditTeamProfile::class);
    }
}

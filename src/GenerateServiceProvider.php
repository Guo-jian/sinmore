<?php

namespace Mquery\Sinmore;

use Illuminate\Support\ServiceProvider;

/**
 * Class GenerateServiceProvider.
 */
class GenerateServiceProvider extends ServiceProvider
{

    /**
     * Bootstrap any application services.
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/controller/Admin/Ad/AdController.php' => app_path('Http/Controllers/Admin/Ad/AdController.php'),
            __DIR__.'/controller/Admin/Admin/AdminController.php' => app_path('Http/Controllers/Admin/Admin/AdminController.php'),
            __DIR__.'/controller/Admin/Admin/LoginController.php' => app_path('Http/Controllers/Admin/Admin/LoginController.php'),
            __DIR__.'/controller/Admin/Banner/BannerController.php' => app_path('Http/Controllers/Admin/Banner/BannerController.php'),
            __DIR__.'/controller/Admin/Category/CategoryController.php' => app_path('Http/Controllers/Admin/Category/CategoryController.php'),
            __DIR__.'/controller/Admin/Contact/ContactController.php' => app_path('Http/Controllers/Admin/Contact/ContactController.php'),
            __DIR__.'/controller/Admin/Content/ContentController.php' => app_path('Http/Controllers/Admin/Content/ContentController.php'),
            __DIR__.'/controller/Admin/Group/GroupController.php' => app_path('Http/Controllers/Admin/Group/GroupController.php'),
            __DIR__.'/controller/Admin/Info/InfoController.php' => app_path('Http/Controllers/Admin/Info/InfoController.php'),
            __DIR__.'/controller/Admin/Label/LabelController.php' => app_path('Http/Controllers/Admin/Label/LabelController.php'),
            __DIR__.'/controller/Admin/Rule/RuleController.php' => app_path('Http/Controllers/Admin/Rule/RuleController.php'),
            __DIR__.'/controller/Admin/User/UserController.php' => app_path('Http/Controllers/Admin/User/UserController.php'),
            __DIR__.'/controller/Admin/User/FreezeController.php' => app_path('Http/Controllers/Admin/User/FreezeController.php'),
            __DIR__.'/controller/Admin/Version/AndriodController.php' => app_path('Http/Controllers/Admin/Version/AndriodController.php'),
            __DIR__.'/controller/Admin/Version/IosController.php' => app_path('Http/Controllers/Admin/Version/IosController.php'),
            __DIR__.'/controller/Api/Ad/AdController.php' => app_path('Http/Controllers/Api/Ad/AdController.php'),
            __DIR__.'/controller/Api/Contact/ContactController.php' => app_path('Http/Controllers/Api/Contact/ContactController.php'),
            __DIR__.'/controller/Api/Content/ContentController.php' => app_path('Http/Controllers/Api/Content/ContentController.php'),
            __DIR__.'/controller/Api/Info/InfoController.php' => app_path('Http/Controllers/Api/Info/InfoController.php'),
            __DIR__.'/controller/Api/User/UserController.php' => app_path('Http/Controllers/Api/User/UserController.php'),
            __DIR__.'/controller/Api/User/LoginController.php' => app_path('Http/Controllers/Api/User/LoginController.php'),
            __DIR__.'/controller/Api/Version/VersionController.php' => app_path('Http/Controllers/Api/Version/VersionController.php'),
            __DIR__.'/controller/Common/CommonController.php' => app_path('Http/Controllers/Common/CommonController.php'),
        ]);
        $this->publishes([
            __DIR__.'/model/Ad.php' => app_path('Models/Ad.php'),
            __DIR__.'/model/Admin.php' => app_path('Models/Admin.php'),
            __DIR__.'/model/AdType.php' => app_path('Models/AdType.php'),
            __DIR__.'/model/Andriod.php' => app_path('Models/Andriod.php'),
            __DIR__.'/model/Banner.php' => app_path('Models/Banner.php'),
            __DIR__.'/model/BannerType.php' => app_path('Models/BannerType.php'),
            __DIR__.'/model/Category.php' => app_path('Models/Category.php'),
            __DIR__.'/model/Code.php' => app_path('Models/Code.php'),
            __DIR__.'/model/Contact.php' => app_path('Models/Contact.php'),
            __DIR__.'/model/Content.php' => app_path('Models/Content.php'),
            __DIR__.'/model/Group.php' => app_path('Models/Group.php'),
            __DIR__.'/model/Info.php' => app_path('Models/Info.php'),
            __DIR__.'/model/Ios.php' => app_path('Models/Ios.php'),
            __DIR__.'/model/Label.php' => app_path('Models/Label.php'),
            __DIR__.'/model/Rule.php' => app_path('Models/Rule.php'),
            __DIR__.'/model/User.php' => app_path('Models/User.php'),
            __DIR__.'/model/UserFreeze.php' => app_path('Models/UserFreeze.php'),
        ]);
        $this->publishes([
            __DIR__.'/middleware/CheckAdmin.php' => app_path('Http/Middleware/CheckAdmin.php'),
            __DIR__.'/middleware/CheckUser.php' => app_path('Http/Middleware/CheckUser.php'),
        ]);
        $this->publishes([
            __DIR__.'/trait/CategoryTrait.php' => app_path('Traits/CategoryTrait.php'),
            __DIR__.'/trait/CodeTrait.php' => app_path('Traits/CodeTrait.php'),
            __DIR__.'/trait/MiniTrait.php' => app_path('Traits/Wechat/MiniTrait.php'),
            __DIR__.'/trait/OfficialAccountTrait.php' => app_path('Traits/Wechat/OfficialAccountTrait.php'),
        ]);
        $this->publishes([
            __DIR__.'/config/lang.php' => config_path('lang.php'),
            __DIR__.'/config/wechat.php' => config_path('wechat.php'),
            __DIR__.'/config/code.php' => config_path('code.php'),
        ]);
        $this->publishes([
            __DIR__.'/migration/create_admins_table.php' => database_path('migrations/2018_12_20_121212_create_admins_table.php'),
            __DIR__.'/migration/create_groups_table.php' => database_path('migrations/2018_12_20_121212_create_groups_table.php'),
            __DIR__.'/migration/create_rules_table.php' => database_path('migrations/2018_12_20_121212_create_rules_table.php'),
            __DIR__.'/migration/create_users_table.php' => database_path('migrations/2018_12_20_121212_create_users_table.php'),
            __DIR__.'/migration/create_user_freezes_table.php' => database_path('migrations/2018_12_20_121212_create_user_freezes_table.php'),
            __DIR__.'/migration/create_codes_table.php' => database_path('migrations/2018_12_20_121212_create_codes_table.php'),
            __DIR__.'/migration/create_banners_table.php' => database_path('migrations/2018_12_20_121212_create_banners_table.php'),
            __DIR__.'/migration/create_banner_types_table.php' => database_path('migrations/2018_12_20_121212_create_banner_types_table.php'),
            __DIR__.'/migration/create_infos_table.php' => database_path('migrations/2018_12_20_121212_create_infos_table.php'),
            __DIR__.'/migration/create_categories_table.php' => database_path('migrations/2018_12_20_121212_create_categories_table.php'),
            __DIR__.'/migration/create_ads_table.php' => database_path('migrations/2018_12_20_121212_create_ads_table.php'),
            __DIR__.'/migration/create_ad_types_table.php' => database_path('migrations/2018_12_20_121212_create_ad_types_table.php'),
            __DIR__.'/migration/create_labels_table.php' => database_path('migrations/2018_12_20_121212_create_labels_table.php'),
            __DIR__.'/migration/create_contents_table.php' => database_path('migrations/2018_12_20_121212_create_contents_table.php'),
            __DIR__.'/migration/create_contacts_table.php' => database_path('migrations/2018_12_20_121212_create_contacts_table.php'),
            __DIR__.'/migration/create_ios_table.php' => database_path('migrations/2018_12_20_121212_create_ios_table.php'),
            __DIR__.'/migration/create_andriods_table.php' => database_path('migrations/2018_12_20_121212_create_andriods_table.php'),
        ]);
        $this->publishes([
            __DIR__.'/seed/RulesTableSeeder.php' => database_path('seeds/RulesTableSeeder.php'),
            __DIR__.'/seed/AdminsTableSeeder.php' => database_path('seeds/AdminsTableSeeder.php'),
        ]);
        require_once __DIR__.'/route/admin.php';
        file_put_contents(base_path('routes/admin.php'),$admin);
        require_once __DIR__.'/route/common.php';
        file_put_contents(base_path('routes/common.php'),$common);
        require_once __DIR__.'/http/Controller.php';
        file_put_contents(app_path('Http/Controllers/Controller.php'),$controller);
        require_once __DIR__.'/http/Kernel.php';
        file_put_contents(app_path('Http/Kernel.php'),$kernel);
        require_once __DIR__.'/route/api.php';
        file_put_contents(base_path('routes/api.php'),$api);
        require_once __DIR__.'/config/app.php';
        file_put_contents(config_path('app.php'),$app);
        require_once __DIR__.'/config/filesystems.php';
        file_put_contents(config_path('filesystems.php'),$filesystems);
        require_once __DIR__.'/provider/RouteServiceProvider.php';
        file_put_contents(app_path('Providers/RouteServiceProvider.php'),$routeServiceProvider);
        require_once __DIR__.'/seed/DatabaseSeeder.php';
        file_put_contents(database_path('seeds/DatabaseSeeder.php'),$databaseSeeder);
        $env = 'APP_NAME='.env('APP_NAME')."\n";
        $env .= 'APP_ENV='.env('APP_ENV')."\n";
        $env .= 'APP_KEY='.env('APP_KEY')."\n";
        $env .= 'APP_ATTACH='.substr(env('APP_KEY'),8,7)."\n";
        $env .= 'APP_DEBUG=true'."\n";
        $env .= 'APP_LOG_LEVEL='.env('APP_LOG_LEVEL')."\n";
        $env .= 'APP_URL='.env('APP_URL')."\n";
        $env .= 'HTML_UTL='."\n";
        $env .= "\n".'DB_CONNECTION='.env('DB_CONNECTION')."\n";
        $env .= 'DB_HOST='.env('DB_HOST')."\n";
        $env .= 'DB_PORT='.env('DB_PORT')."\n";
        $env .= 'DB_DATABASE='.env('DB_DATABASE')."\n";
        $env .= 'DB_USERNAME='.env('DB_USERNAME')."\n";
        $env .= 'DB_PASSWORD='.env('DB_PASSWORD')."\n";
        $env .= "\n".'BROADCAST_DRIVER='.env('BROADCAST_DRIVER')."\n";
        $env .= 'CACHE_DRIVER='.env('CACHE_DRIVER')."\n";
        $env .= 'SESSION_DRIVER='.env('SESSION_DRIVER')."\n";
        $env .= 'SESSION_LIFETIME='.env('SESSION_LIFETIME')."\n";
        $env .= 'QUEUE_DRIVER='.env('QUEUE_DRIVER')."\n";
        $env .= "\n".'REDIS_HOST='.env('REDIS_HOST')."\n";
        $env .= 'REDIS_PASSWORD='.env('REDIS_PASSWORD')."\n";
        $env .= 'REDIS_PORT='.env('REDIS_PORT')."\n";
        $env .= "\n".'MAIL_DRIVER='.env('MAIL_DRIVER')."\n";
        $env .= 'MAIL_HOST='.env('MAIL_HOST')."\n";
        $env .= 'MAIL_PORT='.env('MAIL_PORT')."\n";
        $env .= 'MAIL_USERNAME='.env('MAIL_USERNAME')."\n";
        $env .= 'MAIL_PASSWORD='.env('MAIL_PASSWORD')."\n";
        $env .= 'MAIL_ENCRYPTION='.env('MAIL_ENCRYPTION')."\n";
        $env .= "\n".'PUSHER_APP_ID='.env('PUSHER_APP_ID')."\n";
        $env .= 'PUSHER_APP_KEY='.env('PUSHER_APP_KEY')."\n";
        $env .= 'PUSHER_APP_SECRET='.env('PUSHER_APP_SECRET')."\n";
        $env .= 'PUSHER_APP_CLUSTER='.env('PUSHER_APP_CLUSTER')."\n";
        $env .= "\n".'WECHAT_MINI_PROGRAM_APPID='.env('WECHAT_MINI_PROGRAM_APPID')."\n";
        $env .= 'WECHAT_MINI_PROGRAM_SECRET='.env('WECHAT_MINI_PROGRAM_SECRET')."\n";
        $env .= "\n".'WECHAT_OFFICIAL_ACCOUNT_APPID='.env('WECHAT_OFFICIAL_ACCOUNT_APPID')."\n";
        $env .= 'WECHAT_OFFICIAL_ACCOUNT_SECRET='.env('WECHAT_OFFICIAL_ACCOUNT_SECRET')."\n";
        $env .= 'WECHAT_OFFICIAL_ACCOUNT_OAUTH_SCOPES='.env('WECHAT_OFFICIAL_ACCOUNT_OAUTH_SCOPES')."\n";
        $env .= 'WECHAT_OFFICIAL_ACCOUNT_OAUTH_CALLBACK='.env('WECHAT_OFFICIAL_ACCOUNT_OAUTH_CALLBACK')."\n";
        file_put_contents(base_path('.env'),$env);
    }

    /**
     * Register any application services.
     */
    public function register()
    {
    }
}

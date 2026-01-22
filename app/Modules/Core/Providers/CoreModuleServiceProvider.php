<?php

namespace App\Modules\Core\Providers;

// use App\Modules\Core\Modules\ModuleManager;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use SplFileInfo;

class CoreModuleServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->register(FortifyServiceProvider::class);
        //        $this->app->register(NavigationServiceProvider::class);
        //        $this->app->singleton(ModuleManager::class, function () {
        //            $manager = new ModuleManager;
        //            $manager->discover();
        //
        //            return $manager;
        //        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->configureDefaults();
        //        $this->registerConfig();
        $this->loadMigrationsFrom(__DIR__.'/../Database/Migrations');
        $this->mapWebRoutes();
    }

    /**
     * Register config.
     */
    //    protected function registerConfig(): void
    //    {
    //        $configPath = __DIR__.'/../Config';
    //
    //        $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($configPath));
    //
    //        /** @var SplFileInfo $file */
    //        foreach ($iterator as $file) {
    //            if ($file->isFile() && $file->getExtension() === 'php') {
    //                $this->mergeConfigFrom($file->getPathname(), str_replace('.php', '', $file->getBasename()));
    //            }
    //        }
    //    }

    protected function configureDefaults(): void
    {
        Date::use(CarbonImmutable::class);

        DB::prohibitDestructiveCommands(
            app()->isProduction(),
        );

        Password::defaults(fn (): ?Password => app()->isProduction()
            ? Password::min(12)
                ->mixedCase()
                ->letters()
                ->numbers()
                ->symbols()
                ->uncompromised()
            : null
        );
    }

    /**
     * Define the "web" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     */
    protected function mapWebRoutes(): void
    {
        Route::middleware('web')->group(app_path('Modules/Core/Routes/web.php'));
    }
}

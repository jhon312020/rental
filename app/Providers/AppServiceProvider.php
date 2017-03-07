<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use App\Repositories\SettingsRepository;

use App\Settings;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        \View::composer('*', function($view) {
            $setting_repo = new SettingsRepository();
            $setting = $setting_repo->all()->lists('setting_value', 'setting_key')->toArray();
            //print_r($setting);
            $view->with('setting', $setting);
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
		if ($this->app->environment() == 'local') {
			$this->app->register(\VTalbot\RepositoryGenerator\RepositoryGeneratorServiceProvider::class);
		}
    }
}

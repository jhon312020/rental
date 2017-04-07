<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use App\Repositories\SettingsRepository;

use App\Repositories\MenusRepository;

use App\Settings;

use App\Menus;

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
            
            //print_r('bright');die;
            if(\Auth::User()) {
                $setting_repo = new SettingsRepository();
                $menu_repo = new MenusRepository();
                $setting = $setting_repo->all()->lists('setting_value', 'setting_key')->toArray();
                $roles = \Auth::User()->roles;
                if($roles->role_name == 'admin') {
                    $menus = $menu_repo->allActive()->toArray();
                } else {

                }
                //print_r($setting);die;
                $view->with(['setting' => $setting, 'menus' => $menus]);
            }
            
            
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register() {
  		if ($this->app->environment() == 'local') {
  			$this->app->register(\VTalbot\RepositoryGenerator\RepositoryGeneratorServiceProvider::class);
  		}
    }
}

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

        \Validator::extend('notexists', function ($attribute, $value, $parameters, $validator) {
            $table = $parameters[0];
            $field = $parameters[1];
            //echo $field;die;
            unset($parameters[0], $parameters[1]);
            $query = \DB::table( $table )->select(\DB::raw( 1 ))
            ->where(function($query) use( $parameters, $field, $value ) {
              $query->where($field , $value);
              foreach ($parameters as $key => $data) {
                if($key % 2 == 0) {
                  if($parameters[$key + 1] == 'NULL') {
                    $query->whereNull($data);
                  } else {
                    $query->where($data, $parameters[$key + 1]);
                  }
                  
                }
              }
            });
            
          /*echo $query->toSql();
          print_r($query->getBindings());*/
          //print_r($query->count());die;
          
          if($query->count() > 0) {
            return false;
          } else {
            return true;
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

<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use App\Repositories\SettingsRepository;

use App\Repositories\MenusRepository;

use App\Repositories\MenuPermissionsRepository;

use App\Settings;

use App\Menus;

use Illuminate\Http\Request;

class AppServiceProvider extends ServiceProvider
{
		/**
		 * Bootstrap any application services.
		 *
		 * @return void
		 */
		public function boot(Request $request)
		{
				\View::composer('*', function($view) use ($request) {
						if (!$request->route()) {
							return \View('errors.404');
						}
						$route_path = $request->route()->getPath();
						
						if(\Auth::User()) {
								$user = \Auth::User();
								$setting_repo = new SettingsRepository();
								$menu_repo = new MenusRepository();
								$menu_permission_repo = new MenuPermissionsRepository();
								$setting = $setting_repo->all()->lists('setting_value', 'setting_key')->toArray();
								$roles = \Auth::User()->roles;
								if($roles->role_name == 'admin') {
										$menus = $menu_repo->allActive()->toArray();
								} else {
									$menus = $menu_permission_repo->getMenusByRole(\Auth::User()->role_id)->toArray();
									//print_r($menus);die;
								}
								$default_avatar = asset('/img/default_avatar_male.jpg');
								if ($user->avatar) {
									$default_avatar = asset('images/'.$user->id.'/'.$user->avatar);
								}
								$active_menu = $menu_repo->findByMenuLink($route_path);
								//print_r($active_menu);die;
								//print_r($setting);die;
								$view->with([ 'setting' => $setting, 'menus' => $menus, 'default_avatar' => $default_avatar, 'active_menu' => $active_menu, 'roles' => $roles ]);
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

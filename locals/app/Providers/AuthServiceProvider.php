<?php

namespace App\Providers;

use Illuminate\Contracts\Auth\Access\Gate as GateContract;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use App\Repositories\SettingsRepository;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any application authentication / authorization services.
     *
     * @param  \Illuminate\Contracts\Auth\Access\Gate  $gate
     * @return void
     */
    public function boot(GateContract $gate)
    {
        \View::composer('*', function($view) {
            $setting_repo = new SettingsRepository();
            $setting = $setting_repo->all()->lists('setting_value', 'setting_key')->toArray();
            $view->with([ 'setting' => $setting ]);
        });
        $this->registerPolicies($gate);

        //
    }
}

<?php

namespace App\Http\Middleware;

use Closure;
use App\Repositories\MenuPermissionsRepository;
use App\Repositories\RolesRepository;
use Auth;

class Permission
{
	public function __construct(MenuPermissionsRepository $menu_permissions, RolesRepository $roles)
    {
		  $this->menu_permissions = $menu_permissions;
      $this->roles = $roles;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     * manager - admin123
     */
    public function handle($request, Closure $next)
    {
		  $route_path = $request->route()->getPath();
      //echo $route_path;die;
      $role_id = Auth::user()->role_id;
      $role = \Auth::User()->roles;
      if ($role->role_name != 'admin') {
        $menu_permissions = $this->menu_permissions->checkMenuPermissions($role_id, $route_path);
        if (!$menu_permissions) {
          if ($request->ajax() || $request->wantsJson()) {
            return response('Permission denied.', 403);
          } else {
            return view('errors.403');
          }
        }
      }
      return $next($request);
    }
}

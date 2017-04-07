<?php

namespace App\Repositories;

use App\MenuPermissions;


class MenuPermissionsRepository
{
    /**
     * Get all instance of MenuPermissions.
     *
     * @return \Illuminate\Database\Eloquent\Collection|\App\MenuPermissions[]
     */
    public function all()
    {
        return MenuPermissions::all();
    }

    /**
     * Find an instance of MenuPermissions with the given ID.
     *
     * @param  int  $id
     * @return \App\MenuPermissions
     */
    public function find($id)
    {
        return MenuPermissions::find($id);
    }

    /**
     * Create a new instance of MenuPermissions.
     *
     * @param  array  $attributes
     * @return \App\MenuPermissions
     */
    public function create(array $attributes = [])
    {
        return MenuPermissions::create($attributes);
    }

    /**
     * Update the MenuPermissions with the given attributes.
     *
     * @param  int    $id
     * @param  array  $attributes
     * @return bool|int
     */
    public function update($id, array $attributes = [])
    {
        return MenuPermissions::find($id)->update($attributes);
    }

    /**
     * Delete an entry with the given ID.
     *
     * @param  int  $id
     * @return bool|null
     * @throws \Exception
     */
    public function delete($id)
    {
        return MenuPermissions::find($id)->delete();
    }
    /**
     * Get all active instance of Menu permissions.
     *
     * @return \Illuminate\Database\Eloquent\Collection|\App\MenuPermissions[]
     */
    public function allActive()
    {
        return MenuPermissions::where(array('is_active' => 1))
                    ->get();
    }
}

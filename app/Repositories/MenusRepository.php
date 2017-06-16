<?php

namespace App\Repositories;

use App\Menus;


class MenusRepository
{
    /**
     * Get all instance of Menus.
     *
     * @return \Illuminate\Database\Eloquent\Collection|\App\Menus[]
     */
    public function all()
    {
        return Menus::all();
    }

    /**
     * Find an instance of Menus with the given ID.
     *
     * @param  int  $id
     * @return \App\Menus
     */
    public function find($id)
    {
        return Menus::find($id);
    }

    /**
     * Create a new instance of Menus.
     *
     * @param  array  $attributes
     * @return \App\Menus
     */
    public function create(array $attributes = [])
    {
        return Menus::create($attributes);
    }

    /**
     * Update the Menus with the given attributes.
     *
     * @param  int    $id
     * @param  array  $attributes
     * @return bool|int
     */
    public function update($id, array $attributes = [])
    {
        return Menus::find($id)->update($attributes);
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
        return Menus::find($id)->delete();
    }
    /**
     * Get all active instance of Menus.
     *
     * @return \Illuminate\Database\Eloquent\Collection|\App\Menus[]
     */
    public function allActive()
    {
        return Menus::where(array('is_active' => 1))
                    ->orderby('menu_order', 'asc')
                    ->get();
    }
    /**
     * Find menu using menu link.
     *
     * @return \Illuminate\Database\Eloquent\Collection|\App\Menus[]
     */
    public function findByMenuLink($link)
    {
        return Menus::where(array('menu_link' => $link))
                    ->first();
    }
}

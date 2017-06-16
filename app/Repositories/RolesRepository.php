<?php

namespace App\Repositories;

use App\Roles;


class RolesRepository
{
    /**
     * Get all instance of rooms.
     *
     * @return \Illuminate\Database\Eloquent\Collection|\App\roles[]
     */
    public function all()
    {
        return Roles::all();
    }

    /**
     * Find an instance of rooms with the given ID.
     *
     * @param  int  $id
     * @return \App\roles
     */
    public function find($id)
    {
        return Roles::find($id);
    }

    /**
     * Create a new instance of rooms.
     *
     * @param  array  $attributes
     * @return \App\roles
     */
    public function create(array $attributes = [])
    {
        return Roles::create($attributes);
    }

    /**
     * Update the rooms with the given attributes.
     *
     * @param  int    $id
     * @param  array  $attributes
     * @return bool|int
     */
    public function update($id, array $attributes = [])
    {
        return Roles::find($id)->update($attributes);
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
        return Roles::find($id)->delete();
    }
    /**
     * Get all active instance of Guests.
     *
     * @return \Illuminate\Database\Eloquent\Collection|\App\roles[]
     */
    public function allActive()
    {
        return Roles::where(array('is_active' => 1))->get();
    }

    /**
     * Get active room by id.
     *
     * @return \Illuminate\Database\Eloquent\Collection|\App\roles[]
     */
    public function getActiveRoomById($id)
    {
        return Roles::where(array('is_active' => 1, 'id' => $id))->first();
    }

}

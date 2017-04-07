<?php

namespace App\Repositories;

use App\Rooms;


class RoomsRepository
{
    /**
     * Get all instance of rooms.
     *
     * @return \Illuminate\Database\Eloquent\Collection|\App\rooms[]
     */
    public function all()
    {
        return rooms::all();
    }

    /**
     * Find an instance of rooms with the given ID.
     *
     * @param  int  $id
     * @return \App\rooms
     */
    public function find($id)
    {
        return rooms::find($id);
    }

    /**
     * Create a new instance of rooms.
     *
     * @param  array  $attributes
     * @return \App\rooms
     */
    public function create(array $attributes = [])
    {
        return rooms::create($attributes);
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
        return rooms::find($id)->update($attributes);
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
        return rooms::find($id)->delete();
    }
    /**
     * Get all active instance of Guests.
     *
     * @return \Illuminate\Database\Eloquent\Collection|\App\Rooms[]
     */
    public function allActive()
    {
        return Rooms::where(array('is_active' => 1))->get();
    }

    /**
     * Get active room by id.
     *
     * @return \Illuminate\Database\Eloquent\Collection|\App\Rooms[]
     */
    public function getActiveRoomById($id)
    {
        return Rooms::where(array('is_active' => 1, 'id' => $id))->first();
    }
}

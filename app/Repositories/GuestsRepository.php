<?php

namespace App\Repositories;

use App\Guests;


class GuestsRepository
{
    /**
     * Get all instance of Guests.
     *
     * @return \Illuminate\Database\Eloquent\Collection|\App\Guests[]
     */
    public function all()
    {
        return Guests::all();
    }

    /**
     * Find an instance of Guests with the given ID.
     *
     * @param  int  $id
     * @return \App\Guests
     */
    public function find($id)
    {
        return Guests::find($id);
    }

    /**
     * Create a new instance of Guests.
     *
     * @param  array  $attributes
     * @return \App\Guests
     */
    public function create(array $attributes = [])
    {
        return Guests::create($attributes);
    }

    /**
     * Update the Guests with the given attributes.
     *
     * @param  int    $id
     * @param  array  $attributes
     * @return bool|int
     */
    public function update($id, array $attributes = [])
    {
        return Guests::find($id)->update($attributes);
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
        return Guests::find($id)->delete();
    }
	/**
     * Get all active instance of Guests.
     *
     * @return \Illuminate\Database\Eloquent\Collection|\App\Guests[]
     */
    public function allActive()
    {
        return Guests::where(array('is_active' => 1))->get();
    }
}

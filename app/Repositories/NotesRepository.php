<?php

namespace App\Repositories;

use App\Notes;


class NotesRepository
{
    /**
     * Get all instance of Notes.
     *
     * @return \Illuminate\Database\Eloquent\Collection|\App\Notes[]
     */
    public function all()
    {
        return Notes::all();
    }

    /**
     * Find an instance of Notes with the given ID.
     *
     * @param  int  $id
     * @return \App\Notes
     */
    public function find($id)
    {
        return Notes::find($id);
    }

    /**
     * Create a new instance of Notes.
     *
     * @param  array  $attributes
     * @return \App\Notes
     */
    public function create(array $attributes = [])
    {
        return Notes::create($attributes);
    }

    /**
     * Update the Notes with the given attributes.
     *
     * @param  int    $id
     * @param  array  $attributes
     * @return bool|int
     */
    public function update($id, array $attributes = [])
    {
        return Notes::find($id)->update($attributes);
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
        return Notes::find($id)->delete();
    }
    /**
     * Get all active instance of Guests.
     *
     * @return \Illuminate\Database\Eloquent\Collection|\App\Notes[]
     */
    public function allActive()
    {
        return Notes::where(array('is_active' => 1))->get();
    }

}

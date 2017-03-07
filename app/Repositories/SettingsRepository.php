<?php

namespace App\Repositories;

use App\Settings;


class SettingsRepository
{
    /**
     * Get all instance of Settings.
     *
     * @return \Illuminate\Database\Eloquent\Collection|\App\Settings[]
     */
    public function all()
    {
        return Settings::all();
    }

    /**
     * Find an instance of Settings with the given ID.
     *
     * @param  int  $id
     * @return \App\Settings
     */
    public function find($id)
    {
        return Settings::find($id);
    }

    /**
     * Create a new instance of Settings.
     *
     * @param  array  $attributes
     * @return \App\Settings
     */
    public function create(array $attributes = [])
    {
        return Settings::create($attributes);
    }

    /**
     * Update the Settings with the given attributes.
     *
     * @param  int    $id
     * @param  array  $attributes
     * @return bool|int
     */
    public function update($id, array $attributes = [])
    {
        return Settings::find($id)->update($attributes);
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
        return Settings::find($id)->delete();
    }

}

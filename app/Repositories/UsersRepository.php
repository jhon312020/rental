<?php

namespace App\Repositories;

use App\Users;


class UsersRepository
{
    /**
     * Get all instance of Users.
     *
     * @return \Illuminate\Database\Eloquent\Collection|\App\Users[]
     */
    public function all()
    {
        return Users::all();
    }

    /**
     * Find an instance of Users with the given ID.
     *
     * @param  int  $id
     * @return \App\Users
     */
    public function find($id)
    {
        return Users::find($id);
    }

    /**
     * Create a new instance of Users.
     *
     * @param  array  $attributes
     * @return \App\Users
     */
    public function create(array $attributes = [])
    {
        return Users::create($attributes);
    }

    /**
     * Update the Users with the given attributes.
     *
     * @param  int    $id
     * @param  array  $attributes
     * @return bool|int
     */
    public function update($id, array $attributes = [])
    {
        return Users::find($id)->update($attributes);
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
        return Users::find($id)->delete();
    }

}

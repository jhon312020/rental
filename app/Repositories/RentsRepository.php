<?php

namespace App\Repositories;

use App\Rents;


class RentsRepository
{
    /**
     * Get all instance of Rents.
     *
     * @return \Illuminate\Database\Eloquent\Collection|\App\Rents[]
     */
    public function all()
    {
        return Rents::all();
    }

    /**
     * Find an instance of Rents with the given ID.
     *
     * @param  int  $id
     * @return \App\Rents
     */
    public function find($id)
    {
        return Rents::find($id);
    }

    /**
     * Create a new instance of Rents.
     *
     * @param  array  $attributes
     * @return \App\Rents
     */
    public function create(array $attributes = [])
    {
        return Rents::create($attributes);
    }

    /**
     * Update the Rents with the given attributes.
     *
     * @param  int    $id
     * @param  array  $attributes
     * @return bool|int
     */
    public function update($id, array $attributes = [])
    {
        return Rents::find($id)->update($attributes);
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
        return Rents::find($id)->delete();
    }
    /**
     * Get all active instance of Guests.
     *
     * @return \Illuminate\Database\Eloquent\Collection|\App\Rents[]
     */
    public function allActive()
    {
        return Rents::select('rents.id', 'rents.room_id', 'rents.guest_id', 'rents.checkin_date', 'rents.checkout_date', 'rents.user_id', 'users.name as entry_by', 'rooms.room_no', 'incomes.amount as advance')
                    ->join('users', 'users.id', '=', 'rents.user_id', 'left')
                    ->join('rooms', 'rooms.id', '=', 'rents.room_id', 'left')
                    ->join('incomes', 'incomes.rent_id', '=', 'rents.id', 'left')
                    ->where(array('rents.is_active' => 1))
                    ->get();
    }
}

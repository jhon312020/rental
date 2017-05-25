<?php

namespace App\Repositories;

use App\ExpenseTypes;


class ExpenseTypesRepository
{
    /**
     * Get all instance of ExpenseTypes.
     *
     * @return \Illuminate\Database\Eloquent\Collection|\App\ExpenseTypes[]
     */
    public function all()
    {
        return ExpenseTypes::all();
    }

    /**
     * Find an instance of ExpenseTypes with the given ID.
     *
     * @param  int  $id
     * @return \App\ExpenseTypes
     */
    public function find($id)
    {
        return ExpenseTypes::find($id);
    }

    /**
     * Create a new instance of ExpenseTypes.
     *
     * @param  array  $attributes
     * @return \App\ExpenseTypes
     */
    public function create(array $attributes = [])
    {
        return ExpenseTypes::create($attributes);
    }

    /**
     * Update the ExpenseTypes with the given attributes.
     *
     * @param  int    $id
     * @param  array  $attributes
     * @return bool|int
     */
    public function update($id, array $attributes = [])
    {
        return ExpenseTypes::find($id)->update($attributes);
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
        return ExpenseTypes::find($id)->delete();
    }
    /**
     * Get all active instance of Guests.
     *
     * @return \Illuminate\Database\Eloquent\Collection|\App\ExpenseTypes[]
     */
    public function allActive()
    {
        return ExpenseTypes::where(array('is_active' => 1))->get();
    }
}

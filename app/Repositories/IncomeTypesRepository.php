<?php

namespace App\Repositories;

use App\IncomeTypes;


class IncomeTypesRepository
{
    /**
     * Get all instance of IncomeTypes.
     *
     * @return \Illuminate\Database\Eloquent\Collection|\App\IncomeTypes[]
     */
    public function all()
    {
        return IncomeTypes::all();
    }

    /**
     * Find an instance of IncomeTypes with the given ID.
     *
     * @param  int  $id
     * @return \App\IncomeTypes
     */
    public function find($id)
    {
        return IncomeTypes::find($id);
    }

    /**
     * Create a new instance of IncomeTypes.
     *
     * @param  array  $attributes
     * @return \App\IncomeTypes
     */
    public function create(array $attributes = [])
    {
        return IncomeTypes::create($attributes);
    }

    /**
     * Update the IncomeTypes with the given attributes.
     *
     * @param  int    $id
     * @param  array  $attributes
     * @return bool|int
     */
    public function update($id, array $attributes = [])
    {
        return IncomeTypes::find($id)->update($attributes);
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
        return IncomeTypes::find($id)->delete();
    }
    /**
     * Get all active instance of Guests.
     *
     * @return \Illuminate\Database\Eloquent\Collection|\App\IncomeTypes[]
     */
    public function allActive()
    {
        return IncomeTypes::where(array('is_active' => 1))->get();
    }
    /**
     * Get all active instance of Guests.
     *
     * @return \Illuminate\Database\Eloquent\Collection|\App\IncomeTypes[]
     */
    public function allActiveEdit()
    {
        return IncomeTypes::where(array('is_active' => 1, 'is_edit' => 1))->get();
    }
}

<?php

namespace App\Repositories;

use App\RentIncomes;


class RentIncomesRepository
{
    /**
     * Get all instance of RentIncomes.
     *
     * @return \Illuminate\Database\Eloquent\Collection|\App\RentIncomes[]
     */
    public function all()
    {
        return RentIncomes::all();
    }

    /**
     * Find an instance of RentIncomes with the given ID.
     *
     * @param  int  $id
     * @return \App\RentIncomes
     */
    public function find($id)
    {
        return RentIncomes::find($id);
    }

    /**
     * Create a new instance of RentIncomes.
     *
     * @param  array  $attributes
     * @return \App\RentIncomes
     */
    public function create(array $attributes = [])
    {
        return RentIncomes::create($attributes);
    }

    /**
     * Update the RentIncomes with the given attributes.
     *
     * @param  int    $id
     * @param  array  $attributes
     * @return bool|int
     */
    public function update($id, array $attributes = [])
    {
        return RentIncomes::find($id)->update($attributes);
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
        return RentIncomes::find($id)->delete();
    }

}

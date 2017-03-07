<?php

namespace App\Repositories;

use App\Incomes;


class IncomesRepository
{
    /**
     * Get all instance of Incomes.
     *
     * @return \Illuminate\Database\Eloquent\Collection|\App\Incomes[]
     */
    public function all()
    {
        return Incomes::all();
    }

    /**
     * Find an instance of Incomes with the given ID.
     *
     * @param  int  $id
     * @return \App\Incomes
     */
    public function find($id)
    {
        return Incomes::find($id);
    }

    /**
     * Create a new instance of Incomes.
     *
     * @param  array  $attributes
     * @return \App\Incomes
     */
    public function create(array $attributes = [])
    {
        return Incomes::create($attributes);
    }

    /**
     * Update the Incomes with the given attributes.
     *
     * @param  int    $id
     * @param  array  $attributes
     * @return bool|int
     */
    public function update($id, array $attributes = [])
    {
        return Incomes::find($id)->update($attributes);
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
        return Incomes::find($id)->delete();
    }
    /**
     * Get all active instance of Incomes.
     *
     * @return \Illuminate\Database\Eloquent\Collection|\App\Incomes[]
     */
    public function allActive()
    {
        return Incomes::select('incomes.id', 'incomes.amount', 'incomes.income_type as income_type_id', 'incomes.user_id', 'incomes.date_of_income', 'incomes.notes', 'users.name as entry_by', 'income_types.type_of_income as income_type')
                    ->join('users', 'users.id', '=', 'incomes.user_id')
                    ->join('income_types', 'income_types.id', '=', 'incomes.income_type')
                    ->where(array('incomes.is_active' => 1))
                    ->get();
    }
}

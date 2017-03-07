<?php

namespace App\Repositories;

use App\Expenses;


class ExpensesRepository
{
    /**
     * Get all instance of Expenses.
     *
     * @return \Illuminate\Database\Eloquent\Collection|\App\Expenses[]
     */
    public function all()
    {
        return Expenses::all();
    }

    /**
     * Find an instance of Expenses with the given ID.
     *
     * @param  int  $id
     * @return \App\Expenses
     */
    public function find($id)
    {
        return Expenses::find($id);
    }

    /**
     * Create a new instance of Expenses.
     *
     * @param  array  $attributes
     * @return \App\Expenses
     */
    public function create(array $attributes = [])
    {
        return Expenses::create($attributes);
    }

    /**
     * Update the Expenses with the given attributes.
     *
     * @param  int    $id
     * @param  array  $attributes
     * @return bool|int
     */
    public function update($id, array $attributes = [])
    {
        return Expenses::find($id)->update($attributes);
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
        return Expenses::find($id)->delete();
    }
    /**
     * Get all active instance of expenses.
     *
     * @return \Illuminate\Database\Eloquent\Collection|\App\Expenses[]
     */
    public function allActive()
    {
        return Expenses::select('expenses.id', 'expenses.amount', 'expenses.expense_type as income_type_id', 'expenses.user_id', 'expenses.date_of_expense', 'expenses.notes', 'users.name as entry_by', 'expense_types.type_of_expense as expense_type')
                    ->join('users', 'users.id', '=', 'expenses.user_id')
                    ->join('expense_types', 'expense_types.id', '=', 'expenses.expense_type')
                    ->where(array('expenses.is_active' => 1))
                    ->get();
    }
}

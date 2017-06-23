<?php

namespace App\Repositories;

use App\Expenses;

use Helper;

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
		return Expenses::select('expenses.id', 'expenses.amount', 'expenses.expense_type as expense_type_id', 'expenses.user_id', 'expenses.date_of_expense', 'expenses.notes', 'users.name as entry_by', 'expense_types.type_of_expense as expense_type', 'guests.name as guest', 'rooms.room_no')
					->leftjoin('users', 'users.id', '=', 'expenses.user_id')
					->leftjoin('rents', 'expenses.rent_id', '=', 'rents.id')
					->leftjoin('rooms', 'rents.room_id', '=', 'rooms.id')
					->leftjoin('guests', 'rents.guest_id', '=', 'guests.id')
					->leftjoin('expense_types', 'expense_types.id', '=', 'expenses.expense_type')
					->where(array('expenses.is_active' => 1))
					->get();
	}

	/**
	 * Get all active monthly expenses.
	 *
	 * @return \Illuminate\Database\Eloquent\Collection|\App\Incomes[]
	 */
	public function getMonthlyExpensesReport ($month, $year)
	{
		return Expenses::select('expenses.id', 'expenses.amount', 'expenses.expense_type as expense_type_id', 'expenses.user_id', 'expenses.date_of_income', 'expenses.notes', 'users.name as entry_by', 'expense_types.type_of_income as expense_type')
					->leftjoin('users', 'users.id', '=', 'expenses.user_id')
					->leftjoin('expense_types', 'expense_types.id', '=', 'expenses.expense_type')
					->where(array('expenses.is_active' => 1))
					->whereRaw('MONTH(tbl_expenses.date_of_income) = ? AND YEAR(tbl_expenses.date_of_income) = ? ', [$month, $year])
					->get();
	}

	/**
	 * Get all active monthly expenses.
	 *
	 * @return \Illuminate\Database\Eloquent\Collection|\App\Incomes[]
	 */
	public function getExpensesReportBetweenDates ($start_date, $end_date)
	{
		return Expenses::select('expenses.id', 'expenses.amount', 'expenses.expense_type as expense_type_id', 'expenses.user_id', 'expenses.date_of_expense', 'expenses.notes', 'users.name as entry_by', 'expense_types.type_of_expense as expense_type', 'guests.name as guest', 'rooms.room_no')
					->leftjoin('users', 'users.id', '=', 'expenses.user_id')
					->leftjoin('rents', 'expenses.rent_id', '=', 'rents.id')
					->leftjoin('rooms', 'rents.room_id', '=', 'rooms.id')
					->leftjoin('guests', 'rents.guest_id', '=', 'guests.id')
					->leftjoin('expense_types', 'expense_types.id', '=', 'expenses.expense_type')
					->where(array('expenses.is_active' => 1))
					->whereRaw('DATE(tbl_expenses.date_of_expense) >= ? AND DATE(tbl_expenses.date_of_expense) <= ? ', [$start_date, $end_date])
					->get();
	}

	/**
	 * Get all active monthly expenses.
	 *
	 * @return \Illuminate\Database\Eloquent\Collection|\App\Incomes[]
	 */
	public function getExpensesReportBetweenDatesGroup ($start_date, $end_date)
	{
		return Expenses::select(\DB::raw('SUM(amount) as amount'), 'expenses.date_of_expense')
					->where(array('expenses.is_active' => 1))
					->whereRaw('DATE(tbl_expenses.date_of_expense) >= ? AND DATE(tbl_expenses.date_of_expense) <= ? ', [$start_date, $end_date])
					->groupBy('date_of_expense')
					->get();
	}

	/**
	 * Get all active monthly Incomes by datewise.
	 *
	 * @return \Illuminate\Database\Eloquent\Collection|\App\Incomes[]
	 */
	public function getMonthlyExpensesByDateReport ($date)
	{
		return \DB::select(\DB::raw("select DATE_FORMAT(a.Date, '%d/%m/%Y') as date_of_expense, IF(bb.amount > 0, bb.amount, 0) as amount
				from (
					select last_day('$date') - INTERVAL (a.a + (10 * b.a) + (100 * c.a)) DAY as Date
					from (select 0 as a union all select 1 union all select 2 union all select 3 union all select 4 union all select 5 union all select 6 union all select 7 union all select 8 union all select 9) as a
					cross join (select 0 as a union all select 1 union all select 2 union all select 3 union all select 4 union all select 5 union all select 6 union all select 7 union all select 8 union all select 9) as b
					cross join (select 0 as a union all select 1 union all select 2 union all select 3 union all select 4 union all select 5 union all select 6 union all select 7 union all select 8 union all select 9) as c
				) a LEFT JOIN (SELECT date_of_expense, sum(amount) as amount FROM tbl_expenses where is_active = 1 group by date_of_expense) bb on a.Date = bb.date_of_expense
				where a.Date between '$date' and last_day('$date') order by a.Date"));
	}

	/**
	 * Get all active yearly Incomes by monthwise.
	 *
	 * @return \Illuminate\Database\Eloquent\Collection|\App\Incomes[]
	 */
	public function getYearlyExpensesByMonthReport ($year)
	{
		return \DB::select(\DB::raw("select 

				IF(SUM(IF(month = 1, amount, 0)) is NULL, 0, SUM(IF(month = 1, amount, 0))) AS 'January',
				IF(SUM(IF(month = 2, amount, 0)) is NULL, 0, SUM(IF(month = 2, amount, 0))) AS 'Feburary',
				IF(SUM(IF(month = 3, amount, 0)) is NULL, 0, SUM(IF(month = 3, amount, 0))) AS 'March',
				IF(SUM(IF(month = 4, amount, 0)) is NULL, 0, SUM(IF(month = 4, amount, 0))) AS 'April',
				IF(SUM(IF(month = 5, amount, 0)) is NULL, 0, SUM(IF(month = 5, amount, 0))) AS 'May',
				IF(SUM(IF(month = 6, amount, 0)) is NULL, 0, SUM(IF(month = 6, amount, 0))) AS 'June',
				IF(SUM(IF(month = 7, amount, 0)) is NULL, 0, SUM(IF(month = 7, amount, 0))) AS 'July',
				IF(SUM(IF(month = 8, amount, 0)) is NULL, 0, SUM(IF(month = 8, amount, 0))) AS 'August',
				IF(SUM(IF(month = 9, amount, 0)) is NULL, 0, SUM(IF(month = 9, amount, 0))) AS 'September',
				IF(SUM(IF(month = 10, amount, 0)) is NULL, 0, SUM(IF(month = 10, amount, 0))) AS 'October',
				IF(SUM(IF(month = 11, amount, 0)) is NULL, 0, SUM(IF(month = 11, amount, 0))) AS 'November',
				IF(SUM(IF(month = 12, amount, 0)) is NULL, 0, SUM(IF(month = 12, amount, 0))) AS 'December'
				FROM (
					SELECT id, MONTH(date_of_expense) AS month, SUM(amount) AS amount
					FROM tbl_expenses
					 WHERE YEAR(date_of_expense) = '$year' AND is_active = 1 
					GROUP BY MONTH
				) AS SubTable1"));
	}

	/**
	 * Get total monthly Expenses by datewise.
	 *
	 * @return \Illuminate\Database\Eloquent\Collection|\App\Expenses[]
	 */
	public function getTotalMonthlyExpenses ($month, $year)
	{
		return Expenses::select(\DB::Raw('IF(SUM(amount) > 0, SUM(amount), 0) as amount'))
					->where([ 'is_active' => 1 ])
					->whereRaw('MONTH(tbl_expenses.date_of_expense) = ? AND YEAR(tbl_expenses.date_of_expense) = ? ', [$month, $year])
					->first();
	}

	/**
	 * Get total monthly Expenses by datewise.
	 *
	 * @return \Illuminate\Database\Eloquent\Collection|\App\Expenses[]
	 */
	public function getTotalYearlyExpenses ($year)
	{
		return Expenses::select(\DB::Raw('IF(SUM(amount) > 0, SUM(amount), 0) as amount'))
					->where([ 'is_active' => 1 ])
					->whereRaw('YEAR(tbl_expenses.date_of_expense) = ? ', [$year])
					->first();
	}

	/**
	 * Get total Expenses between dates.
	 *
	 * @return \Illuminate\Database\Eloquent\Collection|\App\Expenses[]
	 */
	public function getTotalExpensesByDates ($start_date, $end_date)
	{
		return Expenses::select(\DB::Raw('IF(SUM(amount) > 0, SUM(amount), 0) as amount'))
					->where([ 'is_active' => 1 ])
					->whereRaw('DATE(tbl_expenses.date_of_expense) >= ? AND DATE(tbl_expenses.date_of_expense) <= ?', [$start_date, $end_date])
					->first();
	}

	/**
	 * Get total Expenses.
	 *
	 * @return \Illuminate\Database\Eloquent\Collection|\App\Expenses[]
	 */
	public function getOverallTotalExpenses ()
	{
		return Expenses::select(\DB::Raw('IF(SUM(amount) > 0, SUM(amount), 0) as amount'))
					->where([ 'is_active' => 1 ])
					->first();
	}
}

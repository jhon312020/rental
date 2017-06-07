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
        return Incomes::select('incomes.id', 'incomes.amount', 'incomes.income_type as income_type_id', 'incomes.user_id', 'incomes.date_of_income', 'incomes.notes', 'users.name as entry_by', 'income_types.type_of_income as income_type', 'guests.name as guest', 'rooms.room_no')
                    ->leftjoin('users', 'users.id', '=', 'incomes.user_id')
                    ->leftjoin('rents', 'incomes.rent_id', '=', 'rents.id')
                    ->leftjoin('rooms', 'rents.room_id', '=', 'rooms.id')
                    ->leftjoin('guests', 'rents.guest_id', '=', 'guests.id')
                    ->leftjoin('income_types', 'income_types.id', '=', 'incomes.income_type')
                    ->where(array('incomes.is_active' => 1))
                    ->where('incomes.amount', '>', 0)
                    ->get();
    }

    /**
     * Get all active monthly Incomes.
     *
     * @return \Illuminate\Database\Eloquent\Collection|\App\Incomes[]
     */
    public function getMonthlyIncomesReport ($month, $year)
    {
        return Incomes::select('incomes.id', 'incomes.amount', 'incomes.income_type as income_type_id', 'incomes.user_id', 'incomes.date_of_income', 'incomes.notes', 'users.name as entry_by', 'income_types.type_of_income as income_type', 'guests.name as rent_from')
                    ->join('users', 'users.id', '=', 'incomes.user_id')
                    ->join('rents', 'rents.id', '=', 'incomes.rent_id')
                    ->join('guests', 'guests.id', '=', 'rents.guest_id')
                    ->join('income_types', 'income_types.id', '=', 'incomes.income_type')
                    ->where(array('incomes.is_active' => 1))
                    ->whereRaw('MONTH(tbl_incomes.date_of_income) = ? AND YEAR(tbl_incomes.date_of_income) = ? ', [$month, $year])
                    ->get();
    }

    /**
     * Get all active monthly Incomes.
     *
     * @return \Illuminate\Database\Eloquent\Collection|\App\Incomes[]
     */
    public function getIncomesReportBetweenDates ($start_date, $end_date)
    {
        return Incomes::select('incomes.id', 'incomes.amount', 'incomes.income_type as income_type_id', 'incomes.user_id', 'incomes.date_of_income', 'incomes.notes', 'users.name as entry_by', 'income_types.type_of_income as income_type', 'guests.name as rent_from', 'rooms.room_no')
                    ->join('users', 'users.id', '=', 'incomes.user_id')
                    ->join('rents', 'rents.id', '=', 'incomes.rent_id')
                    ->join('guests', 'guests.id', '=', 'rents.guest_id')
                    ->leftjoin('rooms', 'rents.room_id', '=', 'rooms.id')
                    ->join('income_types', 'income_types.id', '=', 'incomes.income_type')
                    ->where(array('incomes.is_active' => 1))
                    ->where('amount', '>', 0)
                    ->whereRaw('DATE(tbl_incomes.date_of_income) >= ? AND DATE(tbl_incomes.date_of_income) <= ? ', [$start_date, $end_date])
                    ->get();
    }

    /**
     * Get all active monthly Incomes.
     *
     * @return \Illuminate\Database\Eloquent\Collection|\App\Incomes[]
     */
    public function getIncomesReportBetweenDatesGroup ($start_date, $end_date)
    {
        return Incomes::select(\DB::raw('SUM(amount) as amount'), 'incomes.date_of_income')
                    ->where(array('incomes.is_active' => 1))
                    ->whereRaw('DATE(tbl_incomes.date_of_income) >= ? AND DATE(tbl_incomes.date_of_income) <= ? ', [$start_date, $end_date])
                    ->groupBy('date_of_income')
                    ->get();
    }

    /**
     * Get all active monthly Incomes by datewise.
     *
     * @return \Illuminate\Database\Eloquent\Collection|\App\Incomes[]
     */
    public function getMonthlyIncomesByDateReport ($date)
    {
        return \DB::select(\DB::raw("select a.Date as date_of_income, IF(bb.amount > 0, bb.amount, 0) as amount
                from (
                    select last_day('$date') - INTERVAL (a.a + (10 * b.a) + (100 * c.a)) DAY as Date
                    from (select 0 as a union all select 1 union all select 2 union all select 3 union all select 4 union all select 5 union all select 6 union all select 7 union all select 8 union all select 9) as a
                    cross join (select 0 as a union all select 1 union all select 2 union all select 3 union all select 4 union all select 5 union all select 6 union all select 7 union all select 8 union all select 9) as b
                    cross join (select 0 as a union all select 1 union all select 2 union all select 3 union all select 4 union all select 5 union all select 6 union all select 7 union all select 8 union all select 9) as c
                ) a LEFT JOIN (SELECT date_of_income, sum(amount) as amount FROM tbl_incomes where is_active = 1 group by date_of_income) bb on a.Date = bb.date_of_income
                where a.Date between '$date' and last_day('$date') order by a.Date"));
    }

    /**
     * Get total monthly Incomes by datewise.
     *
     * @return \Illuminate\Database\Eloquent\Collection|\App\Incomes[]
     */
    public function getTotalMonthlyIncomes ($month, $year)
    {
        return Incomes::select(\DB::Raw('IF(SUM(amount) > 0, SUM(amount), 0) as amount'))
                    ->where([ 'is_active' => 1 ])
                    ->whereRaw('MONTH(tbl_incomes.date_of_income) = ? AND YEAR(tbl_incomes.date_of_income) = ? ', [$month, $year])
                    ->first();
    }

    /**
     * Get total monthly Incomes by datewise.
     *
     * @return \Illuminate\Database\Eloquent\Collection|\App\Incomes[]
     */
    public function getTotalYearlyIncomes ($year)
    {
        return Incomes::select(\DB::Raw('IF(SUM(amount) > 0, SUM(amount), 0) as amount'))
                    ->where([ 'is_active' => 1 ])
                    ->whereRaw('YEAR(tbl_incomes.date_of_income) = ? ', [$year])
                    ->first();
    }

    /**
     * Get total Incomes between dates.
     *
     * @return \Illuminate\Database\Eloquent\Collection|\App\Incomes[]
     */
    public function getTotalIncomesByDates ($start_date, $end_date)
    {
        return Incomes::select(\DB::Raw('IF(SUM(amount) > 0, SUM(amount), 0) as amount'))
                    ->where([ 'is_active' => 1 ])
                    ->whereRaw('DATE(tbl_incomes.date_of_income) >= ? AND DATE(tbl_incomes.date_of_income) <= ?', [$start_date, $end_date])
                    ->first();
    }

    /**
     * Get all active yearly Incomes by monthwise.
     *
     * @return \Illuminate\Database\Eloquent\Collection|\App\Incomes[]
     */
    public function getYearlyIncomesByMonthReport ($year)
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
                    SELECT id, MONTH(date_of_income) AS month, SUM(amount) AS amount
                    FROM tbl_incomes
                     WHERE YEAR(date_of_income) = '$year'
                    GROUP BY MONTH
                ) AS SubTable1"));
    }

    /**
     * Get total pending rents.
     *
     * @return \Illuminate\Database\Eloquent\Collection|\App\Incomes[]
     */
    public function getTotalPendingRents ($month, $year)
    {
        return Incomes::select(\DB::Raw('IF(SUM(amount) > 0, SUM(amount), 0) as amount'))
                    ->where([ 'is_active' => 1, 'income_type' => \DB::raw(\Config::get('constants.RENT')), 'rent_amount_received' => 0 ])
                    ->whereRaw('MONTH(tbl_incomes.date_of_income) = ? AND YEAR(tbl_incomes.date_of_income) = ? ', [$month, $year])
                    ->first();
    }

    /**
     * Get total pending guests.
     *
     * @return \Illuminate\Database\Eloquent\Collection|\App\Incomes[]
     */
    public function getTotalPendingGuests ($month, $year)
    {
        return Incomes::select(\DB::Raw('IF(COUNT(id) > 0, COUNT(id), 0) as total'))
                    ->where([ 'is_active' => 1, 'income_type' => \DB::raw(\Config::get('constants.RENT')), 'rent_amount_received' => 0 ])
                    ->whereRaw('MONTH(tbl_incomes.date_of_income) = ? AND YEAR(tbl_incomes.date_of_income) = ? ', [$month, $year])
                    ->first();
    }

    /**
     * Get last 5 rent amount paid
     *
     * @return \Illuminate\Database\Eloquent\Collection|\App\Incomes[]
     */
    public function getLastPaidRentUsingRentId ($data)
    {
        return Incomes::select('rent_id', 'amount', 'date_of_income')
                    ->where([ 'is_active' => 1, 'income_type' => \DB::raw(\Config::get('constants.RENT')), 'rent_id' => $data['rent_id'] ])
                    ->skip(0)->take(5)->get();
    }
    
}

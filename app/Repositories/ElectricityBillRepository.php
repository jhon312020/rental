<?php

namespace App\Repositories;

use App\ElectricityBill;


class ElectricityBillRepository
{
    /**
     * Get all instance of ElectricityBill.
     *
     * @return \Illuminate\Database\Eloquent\Collection|\App\ElectricityBill[]
     */
    public function all()
    {
        return ElectricityBill::all();
    }

    /**
     * Find an instance of ElectricityBill with the given ID.
     *
     * @param  int  $id
     * @return \App\ElectricityBill
     */
    public function find($id)
    {
        return ElectricityBill::find($id);
    }

    /**
     * Create a new instance of ElectricityBill.
     *
     * @param  array  $attributes
     * @return \App\ElectricityBill
     */
    public function create(array $attributes = [])
    {
        return ElectricityBill::create($attributes);
    }

    /**
     * Update the ElectricityBill with the given attributes.
     *
     * @param  int    $id
     * @param  array  $attributes
     * @return bool|int
     */
    public function update ($id, array $attributes = [])
    {
        return ElectricityBill::find($id)->update($attributes);
    }

    /**
     * Delete an entry with the given ID.
     *
     * @param  int  $id
     * @return bool|null
     * @throws \Exception
     */
    public function delete ($id)
    {
        return ElectricityBill::find($id)->delete();
    }

    /**
     * Get all electricity billing of specific month and year.
     *
     * @return \Illuminate\Database\Eloquent\Collection|\App\ElectricityBill[]
     */
    public function activeRoomsElectricityBill ($month, $year, $room_id = 0)
    {

        $query = ElectricityBill::select('electricity_bills.id', 'electricity_bills.room_id', 'rooms.room_no', 'electricity_bills.units_used', 'electricity_bills.amount')
                    ->join('rooms', 'rooms.id', '=', 'electricity_bills.room_id', 'left')
                    ->where(array('electricity_bills.is_active' => 1))
                    ->whereRaw('MONTH(tbl_electricity_bills.billing_month_year) = ? and YEAR(tbl_electricity_bills.billing_month_year) = ?', [$month, $year]);

        if($room_id != 0) {
          $query->where('electricity_bills.room_id', $room_id);
        }
        return $query->get();
    }

    /**
     * Get all deleted electricity billing of specific month and year.
     *
     * @return \Illuminate\Database\Eloquent\Collection|\App\ElectricityBill[]
     */
    public function inActiveRoomsElectricityBill ($month, $year, $room_id = 0)
    {

        $query = ElectricityBill::select('electricity_bills.id', 'electricity_bills.room_id', 'rooms.room_no', 'electricity_bills.units_used', 'electricity_bills.amount')
                    ->join('rooms', 'rooms.id', '=', 'electricity_bills.room_id', 'left')
                    ->where(array('electricity_bills.is_active' => 0))
                    ->whereRaw('MONTH(tbl_electricity_bills.billing_month_year) = ? and YEAR(tbl_electricity_bills.billing_month_year) = ?', [$month, $year]);

        if($room_id != 0) {
          $query->where('electricity_bills.room_id', $room_id);
        }
        return $query->get();
    }

    /**
     * Get all active yearly electricity bills by monthwise.
     *
     * @return \Illuminate\Database\Eloquent\Collection|\App\ElectricityBills[]
     */
    public function getElectricityReportMonthwise ($year)
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
                    SELECT id, MONTH(billing_month_year) AS month, SUM(amount) AS amount
                    FROM tbl_electricity_bills
                     WHERE YEAR(billing_month_year) = '$year' AND is_active = 1
                    GROUP BY MONTH
                ) AS SubTable1"));
    }

    /**
     * Get all active yearly ElectricityBills bills by monthwise.
     *
     * @return \Illuminate\Database\Eloquent\Collection|\App\ElectricityBills[]
     */
    public function getElectricityReportYearwise ($start_year, $end_year)
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
                    SELECT id, YEAR(billing_month_year) AS year, SUM(amount) AS amount
                    FROM tbl_electricity_bills
                     WHERE YEAR(billing_month_year) >= '$start_year' AND YEAR(billing_month_year) <= '$end_year' AND is_active = 1
                    GROUP BY year
                ) AS SubTable1"));
    }

    /**
     * Get total monthly ElectricityBills by between month.
     *
     * @return \Illuminate\Database\Eloquent\Collection|\App\Incomes[]
     */
    public function getElectrictyBillsBetweenMonths ($start_month, $end_month)
    {
        return ElectricityBill::select('electricity_bills.billing_month_year', 'electricity_bills.amount', 'rooms.room_no')
                    ->join('rooms', 'rooms.id', '=', 'electricity_bills.room_id')
                    ->where([ 'electricity_bills.is_active' => 1 ])
                    ->whereRaw('MONTH(tbl_electricity_bills.billing_month_year) >= ? AND MONTH(tbl_electricity_bills.billing_month_year) <= ?', [$start_month, $end_month])
                    ->get();
    }

    /**
     * Get total monthly ElectricityBills by monthwise.
     *
     * @return \Illuminate\Database\Eloquent\Collection|\App\ElectricityBills[]
     */
    public function getTotalElectricityMonthwise ($year)
    {
        return ElectricityBill::select(\DB::Raw('IF(SUM(amount) > 0, SUM(amount), 0) as amount'))
                    ->where([ 'is_active' => 1 ])
                    ->whereRaw('YEAR(tbl_electricity_bills.billing_month_year) = ?', [$year])
                    ->first();
    }

    /**
     * Get total Electricity between years.
     *
     * @return \Illuminate\Database\Eloquent\Collection|\App\ElectricityBills[]
     */
    public function getTotalElectricityYearwise ($start_year, $end_year)
    {
        return ElectricityBill::select(\DB::Raw('IF(SUM(amount) > 0, SUM(amount), 0) as amount'))
                    ->where([ 'is_active' => 1 ])
                    ->whereRaw('YEAR(tbl_electricity_bills.billing_month_year) >= ? AND YEAR(tbl_electricity_bills.billing_month_year)', [$start_year, $end_year])
                    ->first();
    }

    /**
     * Get total Electricity between months.
     *
     * @return \Illuminate\Database\Eloquent\Collection|\App\ElectricityBills[]
     */
    public function getTotalElectrictyBetweenMonths ($start_month, $end_month)
    {
        return ElectricityBill::select(\DB::Raw('IF(SUM(amount) > 0, SUM(amount), 0) as amount'))
                    ->where([ 'is_active' => 1 ])
                    ->whereRaw('MONTH(tbl_electricity_bills.billing_month_year) >= ? AND MONTH(tbl_electricity_bills.billing_month_year) <= ?', [$start_month, $end_month])
                    ->first();
    }

}

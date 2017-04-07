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
    public function activeRoomsElectricityBill ($month, $year)
    {

        return ElectricityBill::select('electricity_bills.id', 'electricity_bills.room_id', 'rooms.room_no', 'electricity_bills.units_used', 'electricity_bills.amount')
                    ->join('rooms', 'rooms.id', '=', 'electricity_bills.room_id', 'left')
                    ->where(array('electricity_bills.is_active' => 1))
                    ->whereRaw('MONTH(tbl_electricity_bills.billing_month_year) = ? and YEAR(tbl_electricity_bills.billing_month_year) = ?', [$month, $year])
                    ->get();
    }

    /**
     * Get all deleted electricity billing of specific month and year.
     *
     * @return \Illuminate\Database\Eloquent\Collection|\App\ElectricityBill[]
     */
    public function inActiveRoomsElectricityBill ($month, $year)
    {

        return ElectricityBill::select('electricity_bills.id', 'electricity_bills.room_id', 'rooms.room_no', 'electricity_bills.units_used', 'electricity_bills.amount')
                    ->join('rooms', 'rooms.id', '=', 'electricity_bills.room_id', 'left')
                    ->where(array('electricity_bills.is_active' => 0))
                    ->whereRaw('MONTH(tbl_electricity_bills.billing_month_year) = ? and YEAR(tbl_electricity_bills.billing_month_year) = ?', [$month, $year])
                    ->get();
    }

}

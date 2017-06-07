<?php

namespace App\Repositories;

use App\RentIncomes;

use App\Rents;


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

    /**
     * Check if the all user got the message.
     *
     * @param  int  $month
     * @param  int  $year
     * @return bool|null
     * @throws \Exception
     */
    public function checkMessageSend($month, $year)
    {
        $query = RentIncomes::select('rent_incomes.id as rent_income_id')
                  ->leftjoin('messages', 'messages.rent_income_id', '=', 'rent_incomes.id')
                  ->whereRaw("Month(tbl_rent_incomes.date_of_rent) = ? and Year(tbl_rent_incomes.date_of_rent) = ?", [ $month, $year ])
                  ->where([ "messages.rent_income_id" => null ]);
        $result = $query->get();
        //print_r($result->toArray());die;
        if ($result) {
          return [  "isSend" => 0, "rent_income_id" => $query->pluck('rent_income_id')->toArray() ];
        }

        return [  "isSend" => 1 ];
    }
    

    /**
     * Get rent ids using mobile nos
     *
     * @param  array  $mobile_nos
     * @return bool|null
     * @throws \Exception
     */
    public function getRentIncomeIdsUsingMobileno ($mobile_nos, $month, $year)
    {
        $query = RentIncomes::select('rent_incomes.id')
                  ->leftjoin('rents', 'rents.id', '=', 'rent_incomes.rent_id')
                  ->leftjoin('guests', 'guests.id', '=', 'rents.guest_id')
                  ->whereRaw("Month(tbl_rent_incomes.date_of_rent) = ? and Year(tbl_rent_incomes.date_of_rent) = ?", [ $month, $year ])
                  ->where('guests.mobile_no', $mobile_nos);
        
        return [  "rent_income_id" => $query->pluck('id')->toArray() ];
    }   
    /**
     * Get user mobile no using rent ids
     *
     * @param  array  $rent_ids
     * @return bool|null
     * @throws \Exception
     */
    public function getMobileNos($rent_incomes_ids)
    {
        $query = RentIncomes::select('guests.mobile_no as number', \DB::raw("'Your payment amount is 2000. Please paid quickly.' as text"))
                  ->leftjoin('rents', 'rents.id', '=', 'rent_incomes.rent_id')
                  ->leftjoin('guests', 'guests.id', '=', 'rents.guest_id')
                  ->whereIn('rent_incomes.id', $rent_incomes_ids);
        
        return [  "messages" => $query->get()->toArray() ];
    }
}

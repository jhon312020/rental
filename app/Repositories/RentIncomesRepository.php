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
        $query = RentIncomes::select('guests.mobile_no as number',
            \DB::raw('CONCAT(CONCAT(CONCAT(CONCAT("Your rent amount is ", 
                  IF((
                    IF(tbl_rt.amount is not null, tbl_rt.amount, 0) - IF(tbl_incomes.amount is not null, SUM(tbl_incomes.amount), 0)) < 0, 
                        IF((IF(tbl_rt.amount is not null, tbl_rt.amount, 0) + tbl_rent_incomes.amount + tbl_rent_incomes.electricity_amount - IF(tbl_incomes.amount is not null, SUM(tbl_incomes.amount), 0)) > 0,
                          IF(tbl_rt.amount is not null, tbl_rt.amount, 0) + tbl_rent_incomes.amount + tbl_rent_incomes.electricity_amount - IF(tbl_incomes.amount is not null, SUM(tbl_incomes.amount), 0),
                           0),
                        tbl_rent_incomes.amount + tbl_rent_incomes.electricity_amount)), ". Total pending amount is "), 
                          IF((SUM(tbl_rt.amount) - IF(tbl_incomes.amount is not null, SUM(tbl_incomes.amount), 0)) > tbl_rent_incomes.amount, 
                        tbl_rent_incomes.amount, 
                      IF((SUM(tbl_rt.amount) - IF(tbl_incomes.amount is not null, SUM(tbl_incomes.amount), 0)) <= 0,
                        0, (SUM(tbl_rt.amount) - IF(tbl_incomes.amount is not null, SUM(tbl_incomes.amount), 0)))), ". Please paid as quickly.")
                          )
                        as text')
         //\DB::raw("'Your payment amount is 2000. Please paid quickly.' as text")
         )
                  ->leftjoin('rents', 'rents.id', '=', 'rent_incomes.rent_id')
                  ->leftjoin(\DB::raw("(SELECT SUM(amount + electricity_amount) as amount, rent_id from tbl_rent_incomes where is_active = 1 group by rent_id) tbl_rt"), "rt.rent_id", "=", "rents.id")
                  ->leftjoin(\DB::raw("(SELECT SUM(amount) as amount, rent_id from tbl_incomes where income_type = '".\Config::get('constants.RENT')."' AND is_active = 1 group by rent_id) tbl_incomes"), "incomes.rent_id", "=", "rents.id")
                  ->leftjoin('guests', 'guests.id', '=', 'rents.guest_id')
                  ->whereIn('rent_incomes.id', $rent_incomes_ids)
                  ->groupBy('rt.rent_id', 'incomes.rent_id');
        
        return [  "messages" => $query->get()->toArray() ];
    }
    /**
     * Check if the rent income exists for the particular rent.
     *
     * @param  array  $rent_ids
     * @return bool|null
     * @throws \Exception
     */
    public function checkRentIncomeExists($rent_ids)
    {
    	$chcek_incharge = Rents::select("id", "room_id")
    											->where([ "is_active" => 1, "incharge_set" => 1, "is_incharge" => 0 ])
    											->whereIn('id', $rent_ids);
    											//->get()->toArray();
    	$is_incharge = false;
    	if ($chcek_incharge->count()) {
    		$rents = $chcek_incharge->get()->toArray();
    		$room_ids = array_column($rents, 'room_id');
    		$rent_incharge_ids = Rents::select('id')
  														->where([ "is_active" => 1, "incharge_set" => 1, "is_incharge" => 1 ])
  														->whereIn('room_id', $room_ids)
  														->get()
  														->toArray();
    		$rent_ids = array_merge($rent_ids, array_column($rent_incharge_ids, "id"));
    		$is_incharge = true;
    	}
    	$rent_incomes =  RentIncomes::select('rent_id')
				    						->where([ "is_active" => 1])
				    						->whereIn('rent_id', $rent_ids)
				    						->groupBy('rent_id');
			if($rent_incomes->count()) {
				$rents = $rent_incomes->get()->toArray();
				return [ "exists" => true, "rent_ids" => array_column($rents, 'rent_id'), "is_incharge" => $is_incharge ];
			}

			return [ "exists" => false ];
    }
}

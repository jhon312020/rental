<?php

namespace App\Repositories;

use App\Rents;

use App\ElectricityBill;

use App\Incomes;

use App\RentIncomes;

use App\Rooms;

use App\Guests;


class RentsRepository
{
	/**
	 * Get all instance of Rents.
	 *
	 * @return \Illuminate\Database\Eloquent\Collection|\App\Rents[]
	 */
	public function all()
	{
		return Rents::all();
	}

	/**
	 * Find an instance of Rents with the given ID.
	 *
	 * @param  int  $id
	 * @return \App\Rents
	 */
	public function find($id)
	{
		return Rents::find($id);
	}

	/**
	 * Create a new instance of Rents.
	 *
	 * @param  array  $attributes
	 * @return \App\Rents
	 */
	public function create(array $attributes = [])
	{
		return Rents::create($attributes);
	}

	/**
	 * Update the Rents with the given attributes.
	 *
	 * @param  int    $id
	 * @param  array  $attributes
	 * @return bool|int
	 */
	public function update($id, array $attributes = [])
	{
		return Rents::find($id)->update($attributes);
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
		return Rents::find($id)->delete();
	}
	/**
	 * Get all active instance of Rents.
	 *
	 * @return \Illuminate\Database\Eloquent\Collection|\App\Rents[]
	 */
	public function allActive ()
	{
		return Rooms::select('rents.id', 'rents.room_id', 'rents.guest_id', 'rents.checkin_date', 'rents.checkout_date', 'rents.user_id', 'rooms.room_no', \DB::raw('SUM(tbl_incomes.amount) as advance'), 'rooms.max_persons_allowed', \DB::raw('COUNT(tbl_rents.room_id) as no_of_person_stayed'), \DB::raw('(tbl_rooms.max_persons_allowed - COUNT(tbl_rents.room_id)) as vacant', 'rooms.room_no'))
					->leftjoin('rents', function($join)
					 {
						 $join->on('rooms.id', '=', 'rents.room_id')
							->on('rents.is_active', '=', \DB::raw('1'))
							->on('rents.checkout_date', 'is', \DB::raw('null'));
					 })
					->leftjoin('incomes', function($join)
					 {
						 $join->on('incomes.rent_id', '=', 'rents.id')
							->on('incomes.income_type', '=', \DB::raw(\Config::get('constants.ADVANCE')));

					 })
					->where(array('rooms.is_active' => 1))
					->groupBy('rents.room_id', 'rooms.id')
					->get();
	}
	/**
	 * Get all settled instance of Rents.
	 *
	 * @return \Illuminate\Database\Eloquent\Collection|\App\Rents[]
	 */
	public function allSettled ()
	{
		return Rooms::select('rents.id', 'rents.room_id', 'rents.guest_id', 'rents.checkin_date', 'rents.checkout_date', 'rents.user_id', 'rooms.room_no', \DB::raw('SUM(tbl_incomes.amount) as advance'), 'rooms.max_persons_allowed', \DB::raw('COUNT(tbl_rents.room_id) as no_of_person_stayed'), 'rooms.room_no', 'ris.rent_amount')
					->leftjoin('rents', function($join)
					 {
						 $join->on('rooms.id', '=', 'rents.room_id')
							->on('rents.is_active', '=', \DB::raw('1'))
							->on('rents.checkout_date', 'is not', \DB::raw('null'));
					 })
					->leftjoin('incomes', function($join)
					 {
						 $join->on('incomes.rent_id', '=', 'rents.id')
							->on('incomes.income_type', '=', \DB::raw(\Config::get('constants.ADVANCE')));

					 })
					->leftjoin(\DB::raw('(select SUM(tbl_rent_incomes.amount) + SUM(tbl_rent_incomes.electricity_amount) as rent_amount, tbl_rents.room_id from tbl_rent_incomes left join tbl_rents on tbl_rents.id = tbl_rent_incomes.rent_id and tbl_rents.checkout_date is not null and tbl_rents.is_active = 1 where tbl_rent_incomes.is_active = 1 group by tbl_rents.room_id) as tbl_ris'), function($join)
					 {
						 $join->on('ris.room_id', '=', 'rents.room_id');

					 })
					->where(array('rooms.is_active' => 1))
					->whereNotNull('rents.id')
					->groupBy('rents.room_id', 'rooms.id')
					->get();
	}
	/**
	 * Get all active users of Rents.
	 *
	 * @return \Illuminate\Database\Eloquent\Collection|\App\Rents[]
	 */
	public function activeUsers ($month)
	{

		return Rents::select('rents.id', 'rents.room_id', 'rents.guest_id', 'rents.checkin_date', 'rents.checkout_date', 'rents.user_id', 'users.name as entry_by', 'rooms.room_no', 'incomes.amount as rental_amount', 'incomes.id as income_id', 'incomes.rent_amount_received as is_amount_received', 'guests.name', 'guests.email', 'guests.mobile_no')
					->join('users', 'users.id', '=', 'rents.user_id', 'left')
					->join('guests', 'guests.id', '=', 'rents.guest_id', 'left')
					->join('rooms', 'rooms.id', '=', 'rents.room_id', 'left')
					->leftjoin('incomes', function($join) use($month)
					 {
						 $join->on('incomes.rent_id', '=', 'rents.id')
							->on('incomes.income_type', '=', \DB::raw(\Config::get('constants.RENT')))
							->on(\DB::raw('MONTH(tbl_incomes.date_of_income)'), '=', \DB::raw($month));

					 })
					->where(array('rents.is_active' => 1, 'rents.checkout_date' => null))
					->get();
	}

	/**
	 * Get all active users of Rents.
	 *
	 * @return \Illuminate\Database\Eloquent\Collection|\App\Rents[]
	 */
	public function getGuestDetailsForRoom ($data)
	{
		$room_id = $data['room_id'];
		return Rents::select('rents.id', 'rents.room_id', 'rents.guest_id', 'rents.checkin_date', 'rents.checkout_date', 'rents.user_id', 'users.name as entry_by', 'rooms.room_no', 'incomes.amount as advance', 'incomes.id as income_id', 'incomes.rent_amount_received as is_amount_received', 'guests.name', 'guests.email', 'guests.mobile_no', 'rents.id as rent_id', 'guests.id as guest_id', 'rents.is_incharge', 'rents.rent_amount', 'rents.incharge_set'
			//, 'rts.rent_id', 'rts.rent_income_id', 'rent_incomes.id as rent_income_id1'
			,\DB::raw('IF(tbl_rts.rent_id is not null and tbl_rts.rent_income_id is not null, 0, 
									IF(
										tbl_rent_incomes.id is not null, 0, 1
									)
								) as is_remove')
				
				)
					->join('users', 'users.id', '=', 'rents.user_id', 'left')
					->join('guests', 'guests.id', '=', 'rents.guest_id', 'left')
					->join('rooms', 'rooms.id', '=', 'rents.room_id', 'left')
					->leftjoin('rent_incomes', function($join) 
					 {
					 	$join->on('rent_incomes.rent_id', '=', 'rents.id')
							->on('rent_incomes.is_active', '=', \DB::raw("'1'"));
					 })
					->leftjoin(\DB::raw("(SELECT tbl_rent_incomes.id as rent_income_id, tbl_rents.id as rent_id, tbl_rents.room_id as rts_room_id from tbl_rents left join tbl_rent_incomes on tbl_rent_incomes.rent_id = tbl_rents.id where tbl_rents.is_active = 1 and tbl_rents.checkout_date is null and tbl_rents.room_id = $room_id and tbl_rents.is_incharge = 1 and tbl_rents.incharge_set = 1 and tbl_rent_incomes.is_active = 1 group by tbl_rent_incomes.rent_id) tbl_rts"), 'rts.rts_room_id', '=', 'rents.room_id')
					->leftjoin('incomes', function($join) 
					 {
						 $join->on('incomes.rent_id', '=', 'rents.id')
							->on('incomes.income_type', '=', \DB::raw(\Config::get('constants.ADVANCE')));

					 })
					->where(array('rents.is_active' => 1, 'rents.checkout_date' => null, 'rents.room_id' => $data['room_id']))
					->orderBy('rents.id', 'desc')
					->groupBy('rent_incomes.rent_id', 'rents.id')
					->get();
	}

	/**
	 * Get all settled users of Rents.
	 *
	 * @return \Illuminate\Database\Eloquent\Collection|\App\Rents[]
	 */
	public function getSettledGuestDetailsForRoom ($data)
	{
		$room_id = $data['room_id'];
		return Rents::select('rents.id', 'rents.room_id', 'rents.guest_id', 'rents.checkin_date', 'rents.checkout_date', 'rents.user_id', 'users.name as entry_by', 'rooms.room_no', 'incomes.amount as advance', 'incomes.id as income_id', 'incomes.rent_amount_received as is_amount_received', 'guests.name', 'guests.email', 'guests.mobile_no', 'rents.id as rent_id', 'guests.id as guest_id', 'rents.is_incharge', 'rents.rent_amount', 'rents.incharge_set'
			//, 'rts.rent_id', 'rts.rent_income_id', 'rent_incomes.id as rent_income_id1'
			,\DB::raw('IF(tbl_rts.rent_id is not null and tbl_rts.rent_income_id is not null, 0, 
									IF(
										tbl_rent_incomes.id is not null, 0, 1
									)
								) as is_remove')
				
				)
					->join('users', 'users.id', '=', 'rents.user_id', 'left')
					->join('guests', 'guests.id', '=', 'rents.guest_id', 'left')
					->join('rooms', 'rooms.id', '=', 'rents.room_id', 'left')
					->leftjoin('rent_incomes', function($join) 
					 {
					 	$join->on('rent_incomes.rent_id', '=', 'rents.id')
							->on('rent_incomes.is_active', '=', \DB::raw("'1'"));
					 })
					->leftjoin(\DB::raw("(SELECT tbl_rent_incomes.id as rent_income_id, tbl_rents.id as rent_id, tbl_rents.room_id as rts_room_id from tbl_rents left join tbl_rent_incomes on tbl_rent_incomes.rent_id = tbl_rents.id where tbl_rents.is_active = 1 and tbl_rents.checkout_date is null and tbl_rents.room_id = $room_id and tbl_rents.is_incharge = 1 and tbl_rents.incharge_set = 1 and tbl_rent_incomes.is_active = 1 group by tbl_rent_incomes.rent_id) tbl_rts"), 'rts.rts_room_id', '=', 'rents.room_id')
					->leftjoin('incomes', function($join) 
					 {
						 $join->on('incomes.rent_id', '=', 'rents.id')
							->on('incomes.income_type', '=', \DB::raw(\Config::get('constants.ADVANCE')));

					 })
					->where(array('rents.is_active' => 1, 'rents.room_id' => $data['room_id']))
					->whereNotNull('rents.checkout_date')
					->orderBy('rents.id', 'desc')
					->groupBy('rent_incomes.rent_id', 'rents.id')
					->get();
	}

	/**
	 * Get all active users of Rents.
	 *
	 * @return \Illuminate\Database\Eloquent\Collection|\App\Rents[]
	 */
	public function getAllGuestDetails ()
	{

		return Rents::select('rents.id', 'rents.room_id', 'rents.guest_id', 'rents.checkin_date', 'rents.checkout_date', 'rents.user_id', 'users.name as entry_by', 'rooms.room_no', 'incomes.amount as advance', 'incomes.id as income_id', 'incomes.rent_amount_received as is_amount_received', 'guests.name', 'guests.email', 'guests.mobile_no', 'rents.id as rent_id', 'guests.id as guest_id', 'rents.is_incharge', 'rents.incharge_set')
					->join('users', 'users.id', '=', 'rents.user_id', 'left')
					->join('guests', 'guests.id', '=', 'rents.guest_id', 'left')
					->join('rooms', 'rooms.id', '=', 'rents.room_id', 'left')
					->leftjoin('incomes', function($join) 
					 {
						 $join->on('incomes.rent_id', '=', 'rents.id')
							->on('incomes.income_type', '=', \DB::raw(\Config::get('constants.ADVANCE')));

					 })
					->where(array( 'rents.is_active' => 1, 'rents.checkout_date' => null ))
					->orderBy('rents.id', 'desc')
					->get();
	}

	/**
	 * Get all rent incomes for monthly.
	 *
	 * @return \Illuminate\Database\Eloquent\Collection|\App\Rents[]
	 */
	public function activeRentsIncome ($month, $year, $room_id = 0)
	{
	  $last_day = date('t', strtotime(date($year.'-'.$month.'-01')));

	  if($month == date('m') && $year == date('Y')) {
		$last_day = date('d');
	  }

	  $date = $year.'-'.$month.'-'.$last_day;

	  $first_date = $year.'-'.$month.'-01';

	  $next_month_date = date('Y-m-d', strtotime($date, strtotime("+1 month")));

		$query = RentIncomes::select('guests.name', 'rents.id as rent_id', 'guests.email', 'guests.mobile_no', 'guests.city', 'rent_incomes.id', 'rent_incomes.amount', 'rooms.room_no', 'guests.id as guest_id', \DB::raw('DATE_FORMAT(tbl_rents.checkin_date, "%d/%m/%Y") as checkin_date'), 'rent_incomes.electricity_amount', 
		  \DB::raw('
			  IF(tbl_rents.checkout_date is null, 
				IF(MONTH(tbl_rents.checkin_date) = "'.$month.'" AND YEAR(tbl_rents.checkin_date) = "'.$year.'", 
					DATEDIFF(DATE("'.$date.'"), tbl_rents.checkin_date), DATEDIFF(DATE("'.$date.'"), DATE("'.$first_date.'"))),
				IF(MONTH(tbl_rents.checkout_date) = "'.$month.'" AND YEAR(tbl_rents.checkout_date) = "'.$year.'", 
					IF(MONTH(tbl_rents.checkin_date) = "'.$month.'" AND YEAR(tbl_rents.checkin_date) = "'.$year.'", 
						DATEDIFF(tbl_rents.checkout_date, tbl_rents.checkin_date), DATEDIFF(tbl_rents.checkout_date, DATE("'.$first_date.'"))),

					IF(MONTH(tbl_rents.checkin_date) = "'.$month.'" AND YEAR(tbl_rents.checkin_date) = "'.$year.'", 
					  DATEDIFF(DATE("'.$date.'"), tbl_rents.checkin_date),
					  DATEDIFF(DATE("'.$date.'"), DATE("'.$first_date.'"))))) + 1 

					as no_of_days_stayed'), 
				  \DB::raw('
					  SUM(tbl_rt_tot.amount) - IF(tbl_incomes.amount is not null, SUM(tbl_incomes.amount), 0) as total_pending_amount'),
				  
					\DB::raw('IF((IF(tbl_rt.amount is not null, tbl_rt.amount, 0) - IF(tbl_incomes.amount is not null, SUM(tbl_incomes.amount), 0)) < 0, 
								IF((IF(tbl_rt.amount is not null, tbl_rt.amount, 0) + tbl_rent_incomes.amount + tbl_rent_incomes.electricity_amount - IF(tbl_incomes.amount is not null, SUM(tbl_incomes.amount), 0)) > 0,
								  IF(tbl_rt.amount is not null, tbl_rt.amount, 0) + tbl_rent_incomes.amount + tbl_rent_incomes.electricity_amount - IF(tbl_incomes.amount is not null, SUM(tbl_incomes.amount), 0),
								   0),
								tbl_rent_incomes.amount + tbl_rent_incomes.electricity_amount)
								as pending_amount'))
					->join('rents', 'rents.id', '=', 'rent_incomes.rent_id', 'left')
					
					->leftjoin(\DB::raw("(SELECT SUM(amount + electricity_amount) as amount, rent_id from tbl_rent_incomes where is_active = 1 and DATE(tbl_rent_incomes.date_of_rent) <= '".$first_date."' group by rent_id) tbl_rt"), "rt.rent_id", "=", "rents.id")

					->leftjoin(\DB::raw("(SELECT SUM(amount + electricity_amount) as amount, rent_id from tbl_rent_incomes where is_active = 1 and DATE(tbl_rent_incomes.date_of_rent) >= '".$next_month_date."' group by rent_id) tbl_rt1"), "rt1.rent_id", "=", "rents.id")

					->leftjoin(\DB::raw("(SELECT SUM(amount) as amount, rent_id from tbl_incomes where income_type = '".\Config::get('constants.RENT')."' AND is_active = 1 group by rent_id) tbl_incomes"), "incomes.rent_id", "=", "rents.id")

					->leftjoin(\DB::raw("(SELECT SUM(amount + electricity_amount) as amount, rent_id from tbl_rent_incomes where is_active = 1 group by rent_id) tbl_rt_tot"), "rt_tot.rent_id", "=", "rents.id")

					->join('guests', 'guests.id', '=', 'rents.guest_id', 'left')
					->join('rooms', 'rooms.id', '=', 'rents.room_id', 'left')
					->where(array('rent_incomes.is_active' => 1))
					->whereRaw('MONTH(tbl_rent_incomes.date_of_rent) = ? and YEAR(tbl_rent_incomes.date_of_rent) = ?', [$month, $year])
					->groupBy('rt.rent_id', 'rt1.rent_id', 'incomes.rent_id', 'rt_tot.rent_id');
					//echo $query->toSql();die;
		if($room_id != 0) {
		  $query->where('rents.room_id', $room_id);
		}
		//echo $query->get();die;
		/*echo "<pre>";
		print_r($query->get()->toArray());die;*/
		return $query->get();
	}

	/**
	 * Get all deleted rent incomes for monthly.
	 *
	 * @return \Illuminate\Database\Eloquent\Collection|\App\Rents[]
	 */
	public function inActiveRentsIncome ($month, $year, $room_id = 0)
	{
		$last_day = date('t', strtotime(date($year.'-'.$month.'-01')));

		if($month == date('m') && $year == date('Y')) {
		  $last_day = date('d');
		}

		$date = $year.'-'.$month.'-'.$last_day;

		$first_date = $year.'-'.$month.'-01';

		$next_month_date = date('Y-m-d', strtotime($date, strtotime("+1 month")));

		 $query = RentIncomes::select('guests.name', 'rents.id as rent_id', 'guests.email', 'guests.mobile_no', 'guests.city', 'rent_incomes.id', 'rent_incomes.amount', 'rooms.room_no', 'guests.id as guest_id', \DB::raw('DATE_FORMAT(tbl_rents.checkin_date, "%d/%m/%Y") as checkin_date'), 'rent_incomes.electricity_amount', 
		  \DB::raw('
			  IF(tbl_rents.checkout_date is null, 
				IF(MONTH(tbl_rents.checkin_date) = "'.$month.'" AND YEAR(tbl_rents.checkin_date) = "'.$year.'", 
					DATEDIFF(DATE("'.$date.'"), tbl_rents.checkin_date), DATEDIFF(DATE("'.$date.'"), DATE("'.$first_date.'"))),
				IF(MONTH(tbl_rents.checkout_date) = "'.$month.'" AND YEAR(tbl_rents.checkout_date) = "'.$year.'", 
					IF(MONTH(tbl_rents.checkin_date) = "'.$month.'" AND YEAR(tbl_rents.checkin_date) = "'.$year.'", 
						DATEDIFF(tbl_rents.checkout_date, tbl_rents.checkin_date), DATEDIFF(tbl_rents.checkout_date, DATE("'.$first_date.'"))),

					IF(MONTH(tbl_rents.checkin_date) = "'.$month.'" AND YEAR(tbl_rents.checkin_date) = "'.$year.'", 
					  DATEDIFF(DATE("'.$date.'"), tbl_rents.checkin_date),
					  DATEDIFF(DATE("'.$date.'"), DATE("'.$first_date.'"))))) + 1 

					as no_of_days_stayed'), 
				  \DB::raw('
					  SUM(tbl_rt_tot.amount) - IF(tbl_incomes.amount is not null, SUM(tbl_incomes.amount), 0) as total_pending_amount'),
				  
					\DB::raw('IF((IF(tbl_rt.amount is not null, tbl_rt.amount, 0) - IF(tbl_incomes.amount is not null, SUM(tbl_incomes.amount), 0)) < 0, 
								IF((IF(tbl_rt.amount is not null, tbl_rt.amount, 0) + tbl_rent_incomes.amount + tbl_rent_incomes.electricity_amount - IF(tbl_incomes.amount is not null, SUM(tbl_incomes.amount), 0)) > 0,
								  IF(tbl_rt.amount is not null, tbl_rt.amount, 0) + tbl_rent_incomes.amount + tbl_rent_incomes.electricity_amount - IF(tbl_incomes.amount is not null, SUM(tbl_incomes.amount), 0),
								   0),
								tbl_rent_incomes.amount + tbl_rent_incomes.electricity_amount)
								as pending_amount'))
					->join('rents', 'rents.id', '=', 'rent_incomes.rent_id', 'left')
					
					->leftjoin(\DB::raw("(SELECT SUM(amount + electricity_amount) as amount, rent_id from tbl_rent_incomes where is_active = 1 and DATE(tbl_rent_incomes.date_of_rent) <= '".$first_date."' group by rent_id) tbl_rt"), "rt.rent_id", "=", "rents.id")

					->leftjoin(\DB::raw("(SELECT SUM(amount + electricity_amount) as amount, rent_id from tbl_rent_incomes where is_active = 1 and DATE(tbl_rent_incomes.date_of_rent) >= '".$next_month_date."' group by rent_id) tbl_rt1"), "rt1.rent_id", "=", "rents.id")

					->leftjoin(\DB::raw("(SELECT SUM(amount) as amount, rent_id from tbl_incomes where income_type = '".\Config::get('constants.RENT')."' AND is_active = 1 group by rent_id) tbl_incomes"), "incomes.rent_id", "=", "rents.id")

					->leftjoin(\DB::raw("(SELECT SUM(amount + electricity_amount) as amount, rent_id from tbl_rent_incomes where is_active = 1 group by rent_id) tbl_rt_tot"), "rt_tot.rent_id", "=", "rents.id")

					->join('guests', 'guests.id', '=', 'rents.guest_id', 'left')
					->join('rooms', 'rooms.id', '=', 'rents.room_id', 'left')
					->where(array('rent_incomes.is_active' => 0))
					->whereRaw('MONTH(tbl_rent_incomes.date_of_rent) = ? and YEAR(tbl_rent_incomes.date_of_rent) = ?', [$month, $year])
					->groupBy('rt.rent_id', 'rt1.rent_id', 'incomes.rent_id', 'rt_tot.rent_id');
					//echo $query->toSql();die;
		if($room_id != 0) {
		  $query->where('rents.room_id', $room_id);
		}
		return $query->get();
	}

	/**
	 * Get all filled rooms.
	 *
	 * @return \Illuminate\Database\Eloquent\Collection|\App\Rents[]
	 */
	public function getVacantRooms ($start_date, $end_date)
	{
		$month = date('m', strtotime($start_date));
		$year = date('Y', strtotime($start_date));
		$current_month_start_date = date('Y-m-01');
		$current_month_end_date = date('Y-m-d');
		$income_type = \Config::get('constants.RENT');
		return \DB::select(\DB::raw("
			  SELECT 
				rooms.room_no, rooms.max_persons_allowed, rooms.total_rent_amount
				, COUNT(rent.id) as no_of_person_stayed
				, rent_current.rent_count as no_of_person_stayed_current
				, IF(rent_month.monthly_rent_amount > 0, rent_month.monthly_rent_amount, 0) as monthly_rent_amount
				, IF(income_month.monthly_income_amount > 0, income_month.monthly_income_amount, 0) as monthly_income_amount  
				, IF(rent_total.total_rent_amount > 0, rent_total.total_rent_amount, 0) as total_rent_amount
				, IF(income_total.total_income_amount > 0, income_total.total_income_amount, 0) as total_income_amount  
				FROM tbl_rooms rooms 

				left join tbl_rents rent 
					on rent.room_id = rooms.id and ('$start_date' >= DATE(rent.checkin_date) or rent.checkin_date between '$start_date' and '$end_date') and IF(rent.checkout_date is null, true, '$end_date' <= rent.checkout_date) and rent.is_active = 1

				left join (select COUNT(id) as rent_count, room_id from tbl_rents where tbl_rents.is_active = 1 and 
					  ('$current_month_start_date' >= DATE(tbl_rents.checkin_date) or tbl_rents.checkin_date between '$current_month_start_date' and '$current_month_end_date') and IF(tbl_rents.checkout_date is null, true, '$current_month_end_date' <= tbl_rents.checkout_date) group by room_id) rent_current
					 on rent_current.room_id = rooms.id

				left join (select tbl_rents.room_id
				  , IF(SUM(tbl_rent_incomes.amount + tbl_rent_incomes.electricity_amount) > 0, SUM(tbl_rent_incomes.amount + tbl_rent_incomes.electricity_amount), 0) as monthly_rent_amount
				  from tbl_rent_incomes
				  left join tbl_rents on tbl_rent_incomes.rent_id = tbl_rents.id
				  where tbl_rent_incomes.is_active = 1 and DATE(tbl_rent_incomes.date_of_rent) >= '$start_date' AND DATE(tbl_rent_incomes.date_of_rent) <= '$end_date'
				  group by tbl_rents.room_id) rent_month 
					on rent_month.room_id = rooms.id 

				left join (select tbl_rents.room_id
				  , IF(SUM(tbl_incomes.amount) > 0, SUM(tbl_incomes.amount), 0) as monthly_income_amount
				  from tbl_incomes
				  left join tbl_rents on tbl_incomes.rent_id = tbl_rents.id
				  where tbl_incomes.is_active = 1 and tbl_incomes.income_type = '$income_type' and tbl_incomes.date_of_income >= '$start_date' and tbl_incomes.date_of_income <= '$end_date'
				  group by tbl_rents.room_id) income_month 
					on income_month.room_id = rooms.id 

				left join (select tbl_rents.room_id
				  , IF(SUM(tbl_rent_incomes.amount + tbl_rent_incomes.electricity_amount) > 0, SUM(tbl_rent_incomes.amount + tbl_rent_incomes.electricity_amount), 0) as total_rent_amount
				  from tbl_rent_incomes
				  left join tbl_rents on tbl_rent_incomes.rent_id = tbl_rents.id
				  where tbl_rent_incomes.is_active = 1
				  group by tbl_rents.room_id) rent_total 
					on rent_total.room_id = rooms.id 

				left join (select tbl_rents.room_id
				  , IF(SUM(tbl_incomes.amount) > 0, SUM(tbl_incomes.amount), 0) as total_income_amount
				  from tbl_incomes
				  left join tbl_rents on tbl_incomes.rent_id = tbl_rents.id
				  where tbl_incomes.is_active = 1 and tbl_incomes.income_type = '$income_type' 
				  group by tbl_rents.room_id) income_total 
					on income_total.room_id = rooms.id 
				where rooms.is_active = 1 
				group by rent.room_id, rooms.id
				having no_of_person_stayed < max_persons_allowed"));
	}

	/**
	 * Get all non filled rooms.
	 *
	 * @return \Illuminate\Database\Eloquent\Collection|\App\Rents[]
	 */
	public function getNonVacantRooms ($start_date, $end_date)
	{
	  $month = date('m', strtotime($start_date));
	  $year = date('Y', strtotime($start_date));
	   $current_month_start_date = date('Y-m-01');
		$current_month_end_date = date('Y-m-d');
		$income_type = \Config::get('constants.RENT');
		return \DB::select(\DB::raw("
			  SELECT 
				rooms.room_no, rooms.max_persons_allowed, rooms.total_rent_amount
				, COUNT(rent.id) as no_of_person_stayed
				, rent_current.rent_count as no_of_person_stayed_current
				, IF(rent_month.monthly_rent_amount > 0, rent_month.monthly_rent_amount, 0) as monthly_rent_amount
				, IF(income_month.monthly_income_amount > 0, income_month.monthly_income_amount, 0) as monthly_income_amount  
				, IF(rent_total.total_rent_amount > 0, rent_total.total_rent_amount, 0) as total_rent_amount
				, IF(income_total.total_income_amount > 0, income_total.total_income_amount, 0) as total_income_amount  
				FROM tbl_rooms rooms 

				left join tbl_rents rent 
					on rent.room_id = rooms.id and ('$start_date' >= DATE(rent.checkin_date) or rent.checkin_date between '$start_date' and '$end_date') and IF(rent.checkout_date is null, true, '$end_date' <= rent.checkout_date) and rent.is_active = 1

				left join (select COUNT(id) as rent_count, room_id from tbl_rents where tbl_rents.is_active = 1 and 
					  ('$current_month_start_date' >= DATE(tbl_rents.checkin_date) or tbl_rents.checkin_date between '$current_month_start_date' and '$current_month_end_date') and IF(tbl_rents.checkout_date is null, true, '$current_month_end_date' <= tbl_rents.checkout_date) group by room_id) rent_current
					 on rent_current.room_id = rooms.id

				left join (select tbl_rents.room_id
				  , IF(SUM(tbl_rent_incomes.amount + tbl_rent_incomes.electricity_amount) > 0, SUM(tbl_rent_incomes.amount + tbl_rent_incomes.electricity_amount), 0) as monthly_rent_amount
				  from tbl_rent_incomes
				  left join tbl_rents on tbl_rent_incomes.rent_id = tbl_rents.id
				  where tbl_rent_incomes.is_active = 1 and DATE(tbl_rent_incomes.date_of_rent) >= '$start_date' AND DATE(tbl_rent_incomes.date_of_rent) <= '$end_date'
				  group by tbl_rents.room_id) rent_month 
					on rent_month.room_id = rooms.id 

				left join (select tbl_rents.room_id
				  , IF(SUM(tbl_incomes.amount) > 0, SUM(tbl_incomes.amount), 0) as monthly_income_amount
				  from tbl_incomes
				  left join tbl_rents on tbl_incomes.rent_id = tbl_rents.id
				  where tbl_incomes.is_active = 1 and tbl_incomes.income_type = '$income_type' and tbl_incomes.date_of_income >= '$start_date' and tbl_incomes.date_of_income <= '$end_date'
				  group by tbl_rents.room_id) income_month 
					on income_month.room_id = rooms.id 

				left join (select tbl_rents.room_id
				  , IF(SUM(tbl_rent_incomes.amount + tbl_rent_incomes.electricity_amount) > 0, SUM(tbl_rent_incomes.amount + tbl_rent_incomes.electricity_amount), 0) as total_rent_amount
				  from tbl_rent_incomes
				  left join tbl_rents on tbl_rent_incomes.rent_id = tbl_rents.id
				  where tbl_rent_incomes.is_active = 1
				  group by tbl_rents.room_id) rent_total 
					on rent_total.room_id = rooms.id 

				left join (select tbl_rents.room_id
				  , IF(SUM(tbl_incomes.amount) > 0, SUM(tbl_incomes.amount), 0) as total_income_amount
				  from tbl_incomes
				  left join tbl_rents on tbl_incomes.rent_id = tbl_rents.id
				  where tbl_incomes.is_active = 1 and tbl_incomes.income_type = '$income_type' 
				  group by tbl_rents.room_id) income_total 
					on income_total.room_id = rooms.id 
				where rooms.is_active = 1 
				group by rent.room_id, rooms.id
				having no_of_person_stayed >= max_persons_allowed"));
	}

	/**
	 * Get all filled and non filled rooms.
	 *
	 * @return \Illuminate\Database\Eloquent\Collection|\App\Rents[]
	 */
	public function getAllRooms ($start_date, $end_date)
	{
	  $month = date('m', strtotime($start_date));
	  $year = date('Y', strtotime($start_date));
	  $current_month_start_date = date('Y-m-01');
		$current_month_end_date = date('Y-m-d');
		$income_type = \Config::get('constants.RENT');
		return \DB::select(\DB::raw("
			  SELECT 
				rooms.room_no, rooms.max_persons_allowed, rooms.total_rent_amount
				, COUNT(rent.id) as no_of_person_stayed
				, rent_current.rent_count as no_of_person_stayed_current
				, IF(rent_month.monthly_rent_amount > 0, rent_month.monthly_rent_amount, 0) as monthly_rent_amount
				, IF(income_month.monthly_income_amount > 0, income_month.monthly_income_amount, 0) as monthly_income_amount  
				, IF(rent_total.total_rent_amount > 0, rent_total.total_rent_amount, 0) as total_rent_amount
				, IF(income_total.total_income_amount > 0, income_total.total_income_amount, 0) as total_income_amount  
				FROM tbl_rooms rooms 

				left join tbl_rents rent 
					on rent.room_id = rooms.id and ('$start_date' >= DATE(rent.checkin_date) or rent.checkin_date between '$start_date' and '$end_date') and IF(rent.checkout_date is null, true, '$end_date' <= rent.checkout_date) and rent.is_active = 1

				left join (select COUNT(id) as rent_count, room_id from tbl_rents where tbl_rents.is_active = 1 and 
					  ('$current_month_start_date' >= DATE(tbl_rents.checkin_date) or tbl_rents.checkin_date between '$current_month_start_date' and '$current_month_end_date') and IF(tbl_rents.checkout_date is null, true, '$current_month_end_date' <= tbl_rents.checkout_date) group by room_id) rent_current
					 on rent_current.room_id = rooms.id

				left join (select tbl_rents.room_id
				  , IF(SUM(tbl_rent_incomes.amount + tbl_rent_incomes.electricity_amount) > 0, SUM(tbl_rent_incomes.amount + tbl_rent_incomes.electricity_amount), 0) as monthly_rent_amount
				  from tbl_rent_incomes
				  left join tbl_rents on tbl_rent_incomes.rent_id = tbl_rents.id
				  where tbl_rent_incomes.is_active = 1 and DATE(tbl_rent_incomes.date_of_rent) >= '$start_date' AND DATE(tbl_rent_incomes.date_of_rent) <= '$end_date'
				  group by tbl_rents.room_id) rent_month 
					on rent_month.room_id = rooms.id 

				left join (select tbl_rents.room_id
				  , IF(SUM(tbl_incomes.amount) > 0, SUM(tbl_incomes.amount), 0) as monthly_income_amount
				  from tbl_incomes
				  left join tbl_rents on tbl_incomes.rent_id = tbl_rents.id
				  where tbl_incomes.is_active = 1 and tbl_incomes.income_type = '$income_type' and tbl_incomes.date_of_income >= '$start_date' and tbl_incomes.date_of_income <= '$end_date'
				  group by tbl_rents.room_id) income_month 
					on income_month.room_id = rooms.id 

				left join (select tbl_rents.room_id
				  , IF(SUM(tbl_rent_incomes.amount + tbl_rent_incomes.electricity_amount) > 0, SUM(tbl_rent_incomes.amount + tbl_rent_incomes.electricity_amount), 0) as total_rent_amount
				  from tbl_rent_incomes
				  left join tbl_rents on tbl_rent_incomes.rent_id = tbl_rents.id
				  where tbl_rent_incomes.is_active = 1
				  group by tbl_rents.room_id) rent_total 
					on rent_total.room_id = rooms.id 

				left join (select tbl_rents.room_id
				  , IF(SUM(tbl_incomes.amount) > 0, SUM(tbl_incomes.amount), 0) as total_income_amount
				  from tbl_incomes
				  left join tbl_rents on tbl_incomes.rent_id = tbl_rents.id
				  where tbl_incomes.is_active = 1 and tbl_incomes.income_type = '$income_type' 
				  group by tbl_rents.room_id) income_total 
					on income_total.room_id = rooms.id 
				where rooms.is_active = 1 
				group by rent.room_id, rooms.id"));
	}

	/**
	 * Get all filled rooms.
	 *
	 * @return \Illuminate\Database\Eloquent\Collection|\App\Rents[]
	 */
	public function getGuestsRentPaid ($month, $year)
	{
	  $last_day = date('t', strtotime(date($year.'-'.$month.'-01')));

	  if($month == date('m') && $year == date('Y')) {
		$last_day = date('d');
	  }

	  $date = $year.'-'.$month.'-'.$last_day;

	  $first_date = $year.'-'.$month.'-01';

	  $next_month_date = date('Y-m-d', strtotime($date, strtotime("+1 month")));

		return RentIncomes::select('guests.name', 'rents.id as rent_id', 'guests.email', 'guests.mobile_no', 'guests.city', 'rent_incomes.id', 'rent_incomes.amount', 'rooms.room_no', 'guests.id as guest_id', \DB::raw('DATE_FORMAT(tbl_rents.checkin_date, "%d/%m/%Y") as checkin_date'), 'rent_incomes.electricity_amount', 
		  \DB::raw('
			  IF(tbl_rents.checkout_date is null, 
				IF(MONTH(tbl_rents.checkin_date) = "'.$month.'" AND YEAR(tbl_rents.checkin_date) = "'.$year.'", 
					DATEDIFF(DATE("'.$date.'"), tbl_rents.checkin_date), DATEDIFF(DATE("'.$date.'"), DATE("'.$first_date.'"))),
				IF(MONTH(tbl_rents.checkout_date) = "'.$month.'" AND YEAR(tbl_rents.checkout_date) = "'.$year.'", 
					IF(MONTH(tbl_rents.checkin_date) = "'.$month.'" AND YEAR(tbl_rents.checkin_date) = "'.$year.'", 
						DATEDIFF(tbl_rents.checkout_date, tbl_rents.checkin_date), DATEDIFF(tbl_rents.checkout_date, DATE("'.$first_date.'"))),

					IF(MONTH(tbl_rents.checkin_date) = "'.$month.'" AND YEAR(tbl_rents.checkin_date) = "'.$year.'", 
					  DATEDIFF(DATE("'.$date.'"), tbl_rents.checkin_date),
					  DATEDIFF(DATE("'.$date.'"), DATE("'.$first_date.'"))))) + 1 

					as no_of_days_stayed'), 
				  \DB::raw('
					  SUM(tbl_rt_tot.amount) - IF(tbl_incomes.amount is not null, SUM(tbl_incomes.amount), 0) as total_pending_amount'),
				  
					\DB::raw('IF((IF(tbl_rt.amount is not null, tbl_rt.amount, 0) - IF(tbl_incomes.amount is not null, SUM(tbl_incomes.amount), 0)) < 0, 
								IF((IF(tbl_rt.amount is not null, tbl_rt.amount, 0) + tbl_rent_incomes.amount + tbl_rent_incomes.electricity_amount - IF(tbl_incomes.amount is not null, SUM(tbl_incomes.amount), 0)) > 0,
								  IF(tbl_rt.amount is not null, tbl_rt.amount, 0) + tbl_rent_incomes.amount + tbl_rent_incomes.electricity_amount - IF(tbl_incomes.amount is not null, SUM(tbl_incomes.amount), 0),
								   0),
								tbl_rent_incomes.amount + tbl_rent_incomes.electricity_amount)
								as pending_amount'))
					->join('rents', 'rents.id', '=', 'rent_incomes.rent_id', 'left')
					
					->leftjoin(\DB::raw("(SELECT SUM(amount + electricity_amount) as amount, rent_id from tbl_rent_incomes where is_active = 1 and DATE(tbl_rent_incomes.date_of_rent) <= '".$first_date."' group by rent_id) tbl_rt"), "rt.rent_id", "=", "rents.id")

					->leftjoin(\DB::raw("(SELECT SUM(amount + electricity_amount) as amount, rent_id from tbl_rent_incomes where is_active = 1 and DATE(tbl_rent_incomes.date_of_rent) >= '".$next_month_date."' group by rent_id) tbl_rt1"), "rt1.rent_id", "=", "rents.id")

					->leftjoin(\DB::raw("(SELECT SUM(amount) as amount, rent_id from tbl_incomes where income_type = '".\Config::get('constants.RENT')."' AND is_active = 1 group by rent_id) tbl_incomes"), "incomes.rent_id", "=", "rents.id")

					->leftjoin(\DB::raw("(SELECT SUM(amount + electricity_amount) as amount, rent_id from tbl_rent_incomes where is_active = 1 group by rent_id) tbl_rt_tot"), "rt_tot.rent_id", "=", "rents.id")

					->join('guests', 'guests.id', '=', 'rents.guest_id', 'left')
					->join('rooms', 'rooms.id', '=', 'rents.room_id', 'left')
					->where(array('rent_incomes.is_active' => 1))
					->whereRaw('MONTH(tbl_rent_incomes.date_of_rent) = ? and YEAR(tbl_rent_incomes.date_of_rent) = ?', [$month, $year])
					->having('pending_amount', '=', 0)
					->groupBy('rt.rent_id', 'rt1.rent_id', 'incomes.rent_id', 'rt_tot.rent_id')->get();
	}

	/**
	 * Get all non filled rooms.
	 *
	 * @return \Illuminate\Database\Eloquent\Collection|\App\Rents[]
	 */
	public function getGuestsRentsUnPaid ($month, $year)
	{
	  $last_day = date('t', strtotime(date($year.'-'.$month.'-01')));

	  if($month == date('m') && $year == date('Y')) {
		$last_day = date('d');
	  }

	  $date = $year.'-'.$month.'-'.$last_day;

	  $first_date = $year.'-'.$month.'-01';

	  $next_month_date = date('Y-m-d', strtotime($date, strtotime("+1 month")));

		return RentIncomes::select('guests.name', 'rents.id as rent_id', 'guests.email', 'guests.mobile_no', 'guests.city', 'rent_incomes.id', 'rent_incomes.amount', 'rooms.room_no', 'guests.id as guest_id', \DB::raw('DATE_FORMAT(tbl_rents.checkin_date, "%d/%m/%Y") as checkin_date'), 'rent_incomes.electricity_amount', 
		  \DB::raw('
			  IF(tbl_rents.checkout_date is null, 
				IF(MONTH(tbl_rents.checkin_date) = "'.$month.'" AND YEAR(tbl_rents.checkin_date) = "'.$year.'", 
					DATEDIFF(DATE("'.$date.'"), tbl_rents.checkin_date), DATEDIFF(DATE("'.$date.'"), DATE("'.$first_date.'"))),
				IF(MONTH(tbl_rents.checkout_date) = "'.$month.'" AND YEAR(tbl_rents.checkout_date) = "'.$year.'", 
					IF(MONTH(tbl_rents.checkin_date) = "'.$month.'" AND YEAR(tbl_rents.checkin_date) = "'.$year.'", 
						DATEDIFF(tbl_rents.checkout_date, tbl_rents.checkin_date), DATEDIFF(tbl_rents.checkout_date, DATE("'.$first_date.'"))),

					IF(MONTH(tbl_rents.checkin_date) = "'.$month.'" AND YEAR(tbl_rents.checkin_date) = "'.$year.'", 
					  DATEDIFF(DATE("'.$date.'"), tbl_rents.checkin_date),
					  DATEDIFF(DATE("'.$date.'"), DATE("'.$first_date.'"))))) + 1 

					as no_of_days_stayed'), 
				  \DB::raw('
					  SUM(tbl_rt_tot.amount) - IF(tbl_incomes.amount is not null, SUM(tbl_incomes.amount), 0) as total_pending_amount'),
				  
					\DB::raw('IF((IF(tbl_rt.amount is not null, tbl_rt.amount, 0) - IF(tbl_incomes.amount is not null, SUM(tbl_incomes.amount), 0)) < 0, 
								IF((IF(tbl_rt.amount is not null, tbl_rt.amount, 0) + tbl_rent_incomes.amount + tbl_rent_incomes.electricity_amount - IF(tbl_incomes.amount is not null, SUM(tbl_incomes.amount), 0)) > 0,
								  IF(tbl_rt.amount is not null, tbl_rt.amount, 0) + tbl_rent_incomes.amount + tbl_rent_incomes.electricity_amount - IF(tbl_incomes.amount is not null, SUM(tbl_incomes.amount), 0),
								   0),
								tbl_rent_incomes.amount + tbl_rent_incomes.electricity_amount)
								as pending_amount'))
					->join('rents', 'rents.id', '=', 'rent_incomes.rent_id', 'left')
					
					->leftjoin(\DB::raw("(SELECT SUM(amount + electricity_amount) as amount, rent_id from tbl_rent_incomes where is_active = 1 and DATE(tbl_rent_incomes.date_of_rent) <= '".$first_date."' group by rent_id) tbl_rt"), "rt.rent_id", "=", "rents.id")

					->leftjoin(\DB::raw("(SELECT SUM(amount + electricity_amount) as amount, rent_id from tbl_rent_incomes where is_active = 1 and DATE(tbl_rent_incomes.date_of_rent) >= '".$next_month_date."' group by rent_id) tbl_rt1"), "rt1.rent_id", "=", "rents.id")

					->leftjoin(\DB::raw("(SELECT SUM(amount) as amount, rent_id from tbl_incomes where income_type = '".\Config::get('constants.RENT')."' AND is_active = 1 group by rent_id) tbl_incomes"), "incomes.rent_id", "=", "rents.id")

					->leftjoin(\DB::raw("(SELECT SUM(amount + electricity_amount) as amount, rent_id from tbl_rent_incomes where is_active = 1 group by rent_id) tbl_rt_tot"), "rt_tot.rent_id", "=", "rents.id")

					->join('guests', 'guests.id', '=', 'rents.guest_id', 'left')
					->join('rooms', 'rooms.id', '=', 'rents.room_id', 'left')
					->where(array('rent_incomes.is_active' => 1))
					->whereRaw('MONTH(tbl_rent_incomes.date_of_rent) = ? and YEAR(tbl_rent_incomes.date_of_rent) = ?', [$month, $year])
					->having('pending_amount', '>', 0)
					->groupBy('rt.rent_id', 'rt1.rent_id', 'incomes.rent_id', 'rt_tot.rent_id')->get();
	}
	/**
	 * Get pending amount.
	 *
	 * @return \Illuminate\Database\Eloquent\Collection|\App\Rents[]
	 */
	public function getPendingAmountUsingRentId ($rent_id, $month, $year) {
	  $last_day = date('t', strtotime(date($year.'-'.$month.'-01')));

	  if($month == date('m') && $year == date('Y')) {
		$last_day = date('d');
	  }

	  $date = $year.'-'.$month.'-'.$last_day;

	  $first_date = $year.'-'.$month.'-01';

	  $next_month_date = date('Y-m-t', strtotime($date));

		$query = RentIncomes::select(\DB::raw('IF((IF(tbl_rt.amount is not null, tbl_rt.amount, 0) - IF(tbl_incomes.amount is not null, SUM(tbl_incomes.amount), 0)) < 0, 
								IF((IF(tbl_rt.amount is not null, tbl_rt.amount, 0) + tbl_rent_incomes.amount + tbl_rent_incomes.electricity_amount - IF(tbl_incomes.amount is not null, SUM(tbl_incomes.amount), 0)) > 0,
								  IF(tbl_rt.amount is not null, tbl_rt.amount, 0) + tbl_rent_incomes.amount + tbl_rent_incomes.electricity_amount - IF(tbl_incomes.amount is not null, SUM(tbl_incomes.amount), 0),
								   0),
								tbl_rent_incomes.amount + tbl_rent_incomes.electricity_amount)
								as pending_amount'), 'rt1.amount as prev_amount',
							\DB::raw('
					  SUM(tbl_rt_tot.amount) - IF(tbl_incomes.amount is not null, SUM(tbl_incomes.amount), 0) as total_pending_amount')
					)
					
					->leftjoin(\DB::raw("(SELECT SUM(amount + electricity_amount) as amount, rent_id from tbl_rent_incomes where is_active = 1 and DATE(tbl_rent_incomes.date_of_rent) <= '".$first_date."' group by rent_id) tbl_rt"), "rt.rent_id", "=", "rent_incomes.rent_id")

					->leftjoin(\DB::raw("(SELECT SUM(amount) as amount, rent_id from tbl_incomes where income_type = '".\Config::get('constants.RENT')."' AND is_active = 1 group by rent_id) tbl_incomes"), "incomes.rent_id", "=", "rent_incomes.rent_id")

					->leftjoin(\DB::raw("(SELECT SUM(amount + electricity_amount) as amount, rent_id from tbl_rent_incomes where is_active = 1 and DATE(tbl_rent_incomes.date_of_rent) >= '".$next_month_date."' group by rent_id) tbl_rt1"), "rt1.rent_id", "=", "rent_incomes.rent_id")

					->leftjoin(\DB::raw("(SELECT SUM(amount + electricity_amount) as amount, rent_id from tbl_rent_incomes where is_active = 1 group by rent_id) tbl_rt_tot"), "rt_tot.rent_id", "=", "rent_incomes.rent_id")

					->where(array('rent_incomes.is_active' => 1))
					->whereRaw('MONTH(tbl_rent_incomes.date_of_rent) = ? and YEAR(tbl_rent_incomes.date_of_rent) = ?', [$month, $year])
					->groupBy('rt.rent_id', 'rt1.rent_id' , 'incomes.rent_id', 'rt_tot.rent_id')
					->where('rent_incomes.rent_id', $rent_id);
		return $query->first();
	}

	/**
	 * Get pending amount.
	 *
	 * @return \Illuminate\Database\Eloquent\Collection|\App\Rents[]
	 */
	public function getUserDetailsUsingRent ($rent_id) {
	  return Rents::select('guests.mobile_no', 'guests.id', 'guests.name', 'rooms.room_no', 'rents.checkin_date')
				  ->leftjoin('guests', 'guests.id', '=', 'rents.guest_id')
				  ->leftjoin('rooms', 'rooms.id', '=', 'rents.room_id')
				  ->where('rents.id', $rent_id)
				  ->first();
	}

	/**
	 * Get All active rents.
	 *
	 * @return \Illuminate\Database\Eloquent\Collection|\App\Rents[]
	 */
	public function getAllActiveRents () {
	  return Rents::select('guests.mobile_no', 'rents.id', 'guests.name', 'rooms.room_no', 'rents.checkin_date', 'incomes.amount as advance', 'rents.is_incharge', 'rents.incharge_set', 'rent_incomes.amount as old_rent')
				  ->leftjoin('guests', 'guests.id', '=', 'rents.guest_id')
				  ->leftjoin('rooms', 'rooms.id', '=', 'rents.room_id')
				  ->leftjoin('incomes', function ($join) {
						$join->on('incomes.rent_id', '=', 'rents.id')
							->on('incomes.income_type', '=', \DB::raw(\Config::get('constants.ADVANCE')));
				  })
				  ->leftjoin('rent_incomes', function ($join) {
						$join->on('rent_incomes.rent_id', '=', 'rents.id')
							->on('rent_incomes.income_type', '=', \DB::raw(\Config::get('constants.OLD_RENT')));
				  })
				  ->where([ 'rents.is_active' => 1, 'rents.checkout_date' => null ])
				  ->where(function ($query) {
				  	$query->orwhere('rents.incharge_set', 0)
				  			->orwhere([ 'rents.incharge_set' => 1, 'rents.is_incharge' => 1 ]);
				  })
				  ->get();
	}

	/**
	 * Check and get the incharge details
	 *
	 * @return \Illuminate\Database\Eloquent\Collection|\App\Rents[]
	 */
	public function checkInchargeAndGetDetails ($rent_id) {
	  $incharge = Rents::where([ 'id' => $rent_id, 'is_incharge' => 1 ])
				  ->first();

	  if (isset($incharge->id)) {
		$query = Rents::select('guests.mobile_no', 'rents.id', 'guests.name', 'rooms.room_no', 'rents.checkin_date')
				  ->leftjoin('guests', 'guests.id', '=', 'rents.guest_id')
				  ->leftjoin('rooms', 'rooms.id', '=', 'rents.room_id')
				  ->where([ 'rents.room_id' => $incharge->room_id, 'rents.checkout_date' => null, 'rents.is_incharge' => 0 ])
				  ->where('rents.id', '<>', $rent_id);
				  
		if ($query->count()) {
		  $list_incharge = $query->get()->toArray();
		  return [ 'is_incharge' => true, 'list_incharge' => $list_incharge ];
		} else {
		  return [ 'is_incharge' => false ];
		}
	  } else {
			return [ 'is_incharge' => false ];
	  }
	}

	/**
	 * Get and update the settlement amount.
	 *
	 * @return \Illuminate\Database\Eloquent\Collection|\App\Rents[]
	 */
	public function getSettlementAmount ($data) {
		$checkout_date = date('Y-m-d', strtotime(str_replace('/', '-', $data['checkout_date'])));
		$month = date('m', strtotime($checkout_date));
		$year = date('Y', strtotime($checkout_date));
		$rent_id = $data['rent_id'];
		$first_date = date('Y-m-01', strtotime($checkout_date));
		$last_date = $checkout_date;
		$rent_details = Rents::where([ "id" => $rent_id ])->first();

		if ($rent_details->incharge_set && !$rent_details->is_incharge) {
			$incharge_details = Rents::where([ "room_id" => $rent_details->room_id, "is_active" => 1, "checkout_date" => null, "is_incharge" => 1 ])->first();
			$incharge_rent_id = $incharge_details->id;
		}

		$query = RentIncomes::select(
					\DB::raw('
					  IF(tbl_rt_tot.amount is not null, SUM(tbl_rt_tot.amount), 0) - 
					  IF(tbl_incomes.amount is not null, SUM(tbl_incomes.amount), 0) as total_pending_amount')
					,\DB::raw('IF(tbl_rt_tot.amount is not null, SUM(tbl_rt_tot.amount), 0) as total_rent_amount')
					,\DB::raw('IF(tbl_incomes.amount is not null, SUM(tbl_incomes.amount), 0) as total_income_amount')
					)
					
					->leftjoin(\DB::raw("(SELECT SUM(amount) as amount, rent_id from tbl_incomes where income_type = '".\Config::get('constants.RENT')."' AND is_active = 1 group by rent_id) tbl_incomes"), "incomes.rent_id", "=", "rent_incomes.rent_id")

					->leftjoin(\DB::raw("(SELECT SUM(amount + electricity_amount) as amount, rent_id from tbl_rent_incomes where is_active = 1 group by rent_id) tbl_rt_tot"), "rt_tot.rent_id", "=", "rent_incomes.rent_id")

					->where(array('rent_incomes.is_active' => 1))
					->groupBy('incomes.rent_id', 'rt_tot.rent_id');

		$total_balance = 0;
		$total_rent_amount = 0;
		$total_income_amount = 0;
		$query->where('rent_incomes.rent_id', $rent_id);
		$guest_details = [];
		$rent_balance =  $query->first();
		if (isset($rent_balance->total_pending_amount)) {
			$total_balance = $rent_balance->total_pending_amount;
			$total_rent_amount = $rent_balance->total_rent_amount;
			$total_income_amount = $rent_balance->total_income_amount;
		} else if (isset($incharge_rent_id)) {
			$guest_details = Guests::find($incharge_details->guest_id);
			/*$main_query = RentIncomes::select(\DB::raw('
					  SUM(tbl_rt_tot.amount) - IF(tbl_incomes.amount is not null, SUM(tbl_incomes.amount), 0) as total_pending_amount')
					)
					
					->leftjoin(\DB::raw("(SELECT SUM(amount) as amount, rent_id from tbl_incomes where income_type = '".\Config::get('constants.RENT')."' AND is_active = 1 group by rent_id) tbl_incomes"), "incomes.rent_id", "=", "rent_incomes.rent_id")

					->leftjoin(\DB::raw("(SELECT SUM(amount + electricity_amount) as amount, rent_id from tbl_rent_incomes where is_active = 1 group by rent_id) tbl_rt_tot"), "rt_tot.rent_id", "=", "rent_incomes.rent_id")

					->where(array('rent_incomes.is_active' => 1))
					->groupBy('incomes.rent_id', 'rt_tot.rent_id')
					->where('rent_incomes.rent_id', $incharge_rent_id);
			$rent_balance =  $main_query->first();
			if (isset($rent_balance->total_pending_amount)) {
				$rent_id = $incharge_rent_id;
				$total_balance = $rent_balance->total_pending_amount;
			}*/
		}
		

		$last_rent_query = RentIncomes::select('id')
												->where([ "rent_id" => $rent_id ])
												->whereRaw('MONTH(date_of_rent) = ? AND YEAR(date_of_rent) = ?', [ $month, $year ]);

		$is_last_rent = $last_rent_query->count();
		//if ($is_last_rent) {
			$rents_query = Rents::select('rents.id as rent_id', 
											\DB::raw("
												trim(
															IF( IF(MONTH(tbl_rents.checkin_date) = '".$month."' AND YEAR(tbl_rents.checkin_date) = '".$year."', 
																	DATEDIFF(DATE('".$last_date."'), tbl_rents.checkin_date), 
															
																	DATEDIFF(DATE('".$last_date."'), DATE('".$first_date."'))) + 1 > 15, 

																			IF(tbl_rents.incharge_set = 1, tbl_rooms.total_rent_amount, tbl_rents.rent_amount), 
																			IF(tbl_rents.incharge_set = 1, tbl_rooms.total_rent_amount/2, tbl_rents.rent_amount / 2)) 
																	)
															
											as rent_amount")
											,\DB::raw("
															IF(MONTH(tbl_rents.checkin_date) = '".$month."' AND YEAR(tbl_rents.checkin_date) = '".$year."', 
																	DATEDIFF(DATE('".$last_date."'), tbl_rents.checkin_date), 
															
																	DATEDIFF(DATE('".$last_date."'), DATE('".$first_date."'))) as no_of_days_stayed")
											,\DB::raw("(IF(tbl_rents.incharge_set = 1, tbl_eb.eb_amount, tbl_eb.single_eb_amount)) as electricity_amount")
											//,'ris.type_rent',
											//'rent_incomes.id',
											,\DB::raw("IF(tbl_rents.incharge_set = 1, 'group', 'individual') as type_of_rent")
											//\DB::raw('CASE WHEN tbl_rents.incharge_set = "1" THEN "group" ELSE "individual" END as type_of_rent')
											)

										->leftjoin('rooms', 'rooms.id', '=', 'rents.room_id')

										->leftJoin(\DB::raw("(select eb.room_id, count(rents.id) as total_person, eb.amount as eb_amount, ROUND(eb.amount / IF(count(rents.id) > 0, count(rents.id), 1)) as single_eb_amount  
												from tbl_electricity_bills eb 
												left join tbl_rents rents on rents.room_id = eb.room_id and (rents.is_active = 1) and (rents.checkout_date is null and $month >= MONTH(rents.checkin_date) and $year >= YEAR(rents.checkin_date)) or (rents.checkout_date is not null and $month <= MONTH(rents.checkout_date) and $year <= YEAR(rents.checkout_date) and $month >= MONTH(rents.checkin_date) and $year >= YEAR(rents.checkin_date)) 
												where MONTH(eb.billing_month_year) = $month AND YEAR(eb.billing_month_year) = $year group by rents.room_id) tbl_eb"), "eb.room_id", "=", "rents.room_id")

										//->whereRaw("(tbl_ris.type_of_room_rent IS NULL OR tbl_ris.type_of_room_rent LIKE 'individual')")

										//->where('ris.type_of_room_rent', '!=', \DB::raw("'group'"))
										//->whereRaw("(IF(tbl_ris.type_of_room_rent is not null, tbl_ris.type_of_room_rent LIKE 'individual' AND tbl_rents.incharge_set = '0', 1))")

										->where([ 'rents.is_active' => 1, 'rents.id' => $rent_id ])

										//->whereRaw("(IF(tbl_ris.type_of_room_rent is not null and tbl_ris.type_of_room_rent = 'individual', 1, tbl_rents.is_incharge = 1))")

										->whereRaw('((tbl_rents.checkout_date is null and ? >= MONTH(tbl_rents.checkin_date) and ? >= YEAR(tbl_rents.checkin_date)) or (tbl_rents.checkout_date is not null and ? <= MONTH(tbl_rents.checkout_date) and ? <= YEAR(tbl_rents.checkout_date)) and ? >= MONTH(tbl_rents.checkin_date) and ? >= YEAR(tbl_rents.checkin_date))', [ $month, $year, $month, $year, $month, $year ]);

			$last_month_rent_details = $rents_query->first();
			$last_month_rent_incomes = RentIncomes::select('rent_incomes.*')
																		->where([ "rent_incomes.rent_id" => $rent_id ])
																		->whereRaw('MONTH(tbl_rent_incomes.date_of_rent) = ? AND YEAR(tbl_rent_incomes.date_of_rent) = ?', [ $month, $year ])
																		->first();

			if ($last_month_rent_incomes) {
				$total_balance = $total_balance - $last_month_rent_incomes->amount - $last_month_rent_incomes->electricity_amount;
				$total_rent_amount = $total_rent_amount - $last_month_rent_incomes->amount - $last_month_rent_incomes->electricity_amount;
			}
			
			$pending_amount = $total_balance + $last_month_rent_details->rent_amount + $last_month_rent_details->electricity_amount;
			$total_rent_amount = $total_rent_amount + $last_month_rent_details->rent_amount + $last_month_rent_details->electricity_amount;

			return [ "last_month_rent_details" => $last_month_rent_details, "pending_amount" => $pending_amount, 'last_month_rent_incomes' => $last_month_rent_incomes, "guest_details" => $guest_details, "total_rent_amount" => $total_rent_amount, "total_income_amount" => $total_income_amount  ];

		//}
	}
	/**
	 * Get guest details for the rent.
	 *
	 * @return \Illuminate\Database\Eloquent\Collection|\App\Rents[]
	 */
	public function getGuetDetailsUsingRent ($rent_id) {
		return Rents::select("guests.*")
							->leftjoin('guests', 'guests.id', '=', 'rents.guest_id')
							->where([ "rents.id" => $rent_id ])	
							->first();
	}	
}
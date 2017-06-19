<?php

namespace App\Repositories;

use App\Incomes;

use App\RentIncomes;

use App\Rents;


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
					->leftjoin('users', 'users.id', '=', 'incomes.user_id')
					->leftjoin('rents', 'rents.id', '=', 'incomes.rent_id')
					->leftjoin('guests', 'guests.id', '=', 'rents.guest_id')
					->leftjoin('income_types', 'income_types.id', '=', 'incomes.income_type')
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
		//echo $start_date;die;
		return Incomes::select('incomes.id', 'incomes.amount', 'incomes.income_type as income_type_id', 'incomes.user_id', 'incomes.date_of_income', 'incomes.notes', 'users.name as entry_by', 'income_types.type_of_income as income_type', 'guests.name as rent_from', 'rooms.room_no')
					->leftjoin('users', 'users.id', '=', 'incomes.user_id')
					->leftjoin('rents', 'rents.id', '=', 'incomes.rent_id')
					->leftjoin('guests', 'guests.id', '=', 'rents.guest_id')
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
		return \DB::select(\DB::raw("select DATE_FORMAT(a.Date, '%d/%m/%Y') as date_of_income, IF(bb.amount > 0, bb.amount, 0) as amount
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
	 * Get total Incomes.
	 *
	 * @return \Illuminate\Database\Eloquent\Collection|\App\Incomes[]
	 */
	public function getOverallTotalIncomes ()
	{
		return Incomes::select(\DB::Raw('IF(SUM(amount) > 0, SUM(amount), 0) as amount'))
					->where([ 'is_active' => 1 ])
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
	  $date = date(date('Y-m-d', strtotime($year.'-'.$month.'01')), strtotime("-1 month"));
	  $prev_date = date('Y-m-t', strtotime($date));
		$query = RentIncomes::select(\DB::Raw("if(sum(tbl_rent_incomes.amount + tbl_rent_incomes.electricity_amount) > 0, sum(tbl_rent_incomes.amount + tbl_rent_incomes.electricity_amount), 0) - IF(((select if(sum(tc.amount) > 0, sum(tc.amount), 0) from tbl_incomes tc where tc.rent_id = tbl_rent_incomes.rent_id and tc.is_active = 1 and tc.income_type = '".\Config::get('constants.RENT')."') - (select if(sum(tbl_rent_incomes.amount + tbl_rent_incomes.electricity_amount) > 0, sum(tbl_rent_incomes.amount + tbl_rent_incomes.electricity_amount), 0) from tbl_rent_incomes tri where tri.date_of_rent <= '$prev_date' and tri.is_active = 1)) > 0, ((select if(sum(tc.amount) > 0, sum(tc.amount), 0) from tbl_incomes tc where tc.rent_id = tbl_rent_incomes.rent_id and tc.is_active = 1 and tc.income_type = '".\Config::get('constants.RENT')."') - (select if(sum(tbl_rent_incomes.amount + tbl_rent_incomes.electricity_amount) > 0, sum(tbl_rent_incomes.amount + tbl_rent_incomes.electricity_amount), 0) from tbl_rent_incomes tri where tri.date_of_rent <= '$prev_date' and tri.is_active = 1)), 0) as amount"))
					->where([ 'rent_incomes.is_active' => 1 ])
					->whereRaw('MONTH(tbl_rent_incomes.date_of_rent) = ? AND YEAR(tbl_rent_incomes.date_of_rent) = ? ', [$month, $year]);
		return $query->first();
	}

	/**
	 * Get total pending rents.
	 *
	 * @return \Illuminate\Database\Eloquent\Collection|\App\Incomes[]
	 */
	public function getOverallPendingRents ()
	{
		$query = RentIncomes::select(\DB::Raw("if(sum(tbl_rent_incomes.amount + tbl_rent_incomes.electricity_amount) > 0, sum(tbl_rent_incomes.amount + tbl_rent_incomes.electricity_amount), 0) - (select if(sum(tc.amount) > 0, sum(tc.amount), 0) from tbl_incomes tc where tc.rent_id = tbl_rent_incomes.rent_id and tc.is_active = 1 and tc.income_type = '".\Config::get('constants.RENT')."') as amount"))
					->where([ 'rent_incomes.is_active' => 1 ]);
		return $query->first();
	}

	/**
	 * Get total pending guests.
	 *
	 * @return \Illuminate\Database\Eloquent\Collection|\App\Incomes[]
	 */
	public function getTotalPendingGuests ($month, $year)
	{
		$last_day = date('t', strtotime(date($year.'-'.$month.'-01')));

	  if($month == date('m') && $year == date('Y')) {
		$last_day = date('d');
	  }

	  $date = $year.'-'.$month.'-'.$last_day;

	  $first_date = $year.'-'.$month.'-01';

	  $next_month_date = date('Y-m-d', strtotime($date, strtotime("+1 month")));

		$query = RentIncomes::select(
					\DB::raw('IF((tbl_rt.amount - 
								IF(tbl_incomes.amount is not null, SUM(tbl_incomes.amount), 0)) > (tbl_rent_incomes.amount + tbl_rent_incomes.electricity_amount), 
								  tbl_rent_incomes.amount + tbl_rent_incomes.electricity_amount, 
								  IF(tbl_rt1.amount is not null, 
									IF(tbl_rt.amount - tbl_rt1.amount - IF(tbl_incomes.amount is not null, SUM(tbl_incomes.amount), 0) > 0,
									  tbl_rt.amount - tbl_rt1.amount - IF(tbl_incomes.amount is not null, SUM(tbl_incomes.amount), 0),
									  0
									),
									tbl_rt.amount - IF(tbl_incomes.amount is not null, SUM(tbl_incomes.amount), 0)
								  )
							  )  as pending_amount'))
					->join('rents', 'rents.id', '=', 'rent_incomes.rent_id', 'left')
					
					->leftjoin(\DB::raw("(SELECT SUM(amount + electricity_amount) as amount, rent_id from tbl_rent_incomes where is_active = 1 group by rent_id) tbl_rt"), "rt.rent_id", "=", "rents.id")

					->leftjoin(\DB::raw("(SELECT SUM(amount + electricity_amount) as amount, rent_id from tbl_rent_incomes where is_active = 1 and DATE(tbl_rent_incomes.date_of_rent) >= '".$next_month_date."' group by rent_id) tbl_rt1"), "rt1.rent_id", "=", "rents.id")

					->leftjoin(\DB::raw("(SELECT SUM(amount) as amount, rent_id from tbl_incomes where income_type = '".\Config::get('constants.RENT')."' AND is_active = 1 group by rent_id) tbl_incomes"), "incomes.rent_id", "=", "rents.id")
					->where(array('rent_incomes.is_active' => 1))
					->whereRaw('MONTH(tbl_rent_incomes.date_of_rent) = ? and YEAR(tbl_rent_incomes.date_of_rent) = ?', [$month, $year])
					->having('pending_amount', '>', 0)
					->groupBy('rt.rent_id', 'incomes.rent_id');

		  return \DB::table( \DB::raw("({$query->toSql()}) as sub") )
				->mergeBindings($query->getQuery())
				->count();
	}

	/**
	 * Get overall pending guests.
	 *
	 * @return \Illuminate\Database\Eloquent\Collection|\App\Incomes[]
	 */
	public function getOverallPendingGuests ()
	{

		$query = Rents::select(
					\DB::raw('(tbl_rt.amount - IF(tbl_incomes.amount > 0 , tbl_incomes.amount, 0)) as pending_amount'))
					
					->leftjoin(\DB::raw("(SELECT IF(SUM(amount + electricity_amount) > 0, SUM(amount + electricity_amount), 0) as amount, rent_id from tbl_rent_incomes where is_active = 1 group by rent_id) tbl_rt"), "rt.rent_id", "=", "rents.id")

					->leftjoin(\DB::raw("(SELECT IF(SUM(amount) > 0, SUM(amount), 0) as amount, rent_id from tbl_incomes where income_type = '".\Config::get('constants.RENT')."' AND is_active = 1 group by rent_id) tbl_incomes"), "incomes.rent_id", "=", "rents.id")
					->where(array('rents.is_active' => 1))
					->having('pending_amount', '>', 0)
					->groupBy('rt.rent_id', 'incomes.rent_id');

		  return \DB::table( \DB::raw("({$query->toSql()}) as sub") )
				->mergeBindings($query->getQuery())
				->count();
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

	/**
	 * Get total Incomes between dates.
	 *
	 * @return \Illuminate\Database\Eloquent\Collection|\App\Incomes[]
	 */
	public function getTotalGuestIncomeByDates ($start_date, $end_date, $guest_id)
	{
		$incomes = Incomes::select(\DB::Raw('IF(SUM(amount) > 0, SUM(amount), 0) as amount'))
					->leftjoin('rents', 'incomes.rent_id', '=', 'rents.id')
					->where([ 'incomes.is_active' => 1, 'rents.guest_id' => $guest_id, 'incomes.income_type' => \DB::raw(\Config::get('constants.RENT')) ])
					->whereRaw('DATE(tbl_incomes.date_of_income) >= ? AND DATE(tbl_incomes.date_of_income) <= ?', [ $start_date, $end_date ])
					->first();

		$rent = RentIncomes::select(\DB::Raw('IF(SUM(tbl_rent_incomes.amount + tbl_rent_incomes.electricity_amount) > 0, SUM(tbl_rent_incomes.amount + tbl_rent_incomes.electricity_amount), 0) as amount'))
					->leftjoin('rents', 'rent_incomes.rent_id', '=', 'rents.id')
					->where([ 'rent_incomes.is_active' => 1, 'rents.guest_id' => $guest_id ])
					->whereRaw('DATE(tbl_rent_incomes.date_of_rent) >= ? AND DATE(tbl_rent_incomes.date_of_rent) <= ?', [ $start_date, $end_date ])
					->first();

		$balance = RentIncomes::select(\DB::Raw("if(sum(tbl_rent_incomes.amount + tbl_rent_incomes.electricity_amount) > 0, sum(tbl_rent_incomes.amount + tbl_rent_incomes.electricity_amount), 0) - (select if(sum(tc.amount) > 0, sum(tc.amount), 0) from tbl_incomes tc left join tbl_rents tr on tr.id = tc.rent_id where tr.guest_id = '$guest_id' and tc.date_of_income <= '$end_date' and tc.income_type = '".\Config::get('constants.RENT')."') as balance"))
					->leftjoin('rents', 'rent_incomes.rent_id', '=', 'rents.id')
					->where([ 'rent_incomes.is_active' => 1, 'rents.guest_id' => $guest_id ])
					->whereRaw('DATE(tbl_rent_incomes.date_of_rent) <= ?', [ $end_date ])
					->first();

		  return [ "incomes" => $incomes->amount, "rent" => $rent->amount, "balance" => $balance->balance ];

	}

	/**
	 * Get total Incomes.
	 *
	 * @return \Illuminate\Database\Eloquent\Collection|\App\Incomes[]
	 */
	public function getTotalGuestIncome ($guest_id)
	{
		$incomes = Incomes::select(\DB::Raw('IF(SUM(amount) > 0, SUM(amount), 0) as amount'))
					->leftjoin('rents', 'incomes.rent_id', '=', 'rents.id')
					->where([ 'incomes.is_active' => 1, 'rents.guest_id' => $guest_id, 'incomes.income_type' => \DB::raw(\Config::get('constants.RENT')) ])
					->first();

		$rent = RentIncomes::select(\DB::Raw('IF(SUM(tbl_rent_incomes.amount + tbl_rent_incomes.electricity_amount) > 0, SUM(tbl_rent_incomes.amount + tbl_rent_incomes.electricity_amount), 0) as amount'))
					->leftjoin('rents', 'rent_incomes.rent_id', '=', 'rents.id')
					->where([ 'rent_incomes.is_active' => 1, 'rents.guest_id' => $guest_id ])
					->first();

		$balance = RentIncomes::select(\DB::Raw("if(sum(tbl_rent_incomes.amount + tbl_rent_incomes.electricity_amount) > 0, sum(tbl_rent_incomes.amount + tbl_rent_incomes.electricity_amount), 0) - (select if(sum(tc.amount) > 0, sum(tc.amount), 0) from tbl_incomes tc left join tbl_rents tr on tr.id = tc.rent_id where tr.guest_id = '$guest_id' and tc.income_type = '".\Config::get('constants.RENT')."') as balance"))
					->leftjoin('rents', 'rent_incomes.rent_id', '=', 'rents.id')
					->where([ 'rent_incomes.is_active' => 1, 'rents.guest_id' => $guest_id ])
					->first();

		  return [ "incomes" => $incomes->amount, "rent" => $rent->amount, "balance" => $balance->balance ];

	}

	/**
	 * Get all active monthly Incomes.
	 *
	 * @return \Illuminate\Database\Eloquent\Collection|\App\Incomes[]
	 */
	public function getGuestIncomesReportBetweenDates ($start_date, $end_date, $guest_id)
	{
		//echo $start_date;die;
		$first_query = Incomes::select(\DB::raw('DATE_FORMAT(tbl_incomes.date_of_income, "%d/%m/%Y") as date'), \DB::raw('sum(tbl_incomes.amount) as amount'), \DB::raw('0 as rent_amount'),
						\DB::raw("(select if(sum(trt.amount + trt.electricity_amount) > 0, sum(trt.amount + trt.electricity_amount), 0)  - (select if(sum(tc.amount) > 0, sum(tc.amount), 0) from tbl_incomes tc where tc.rent_id = tbl_rents.id and tc.date_of_income <= tbl_incomes.date_of_income and tc.income_type = '".\Config::get('constants.RENT')."') as balance_amount from tbl_rent_incomes trt where trt.rent_id = tbl_rents.id and trt.date_of_rent <= tbl_incomes.date_of_income) as balance"), 'incomes.date_of_income as date_of_rent')
					->leftjoin('rents', 'rents.id', '=', 'incomes.rent_id')
					->leftjoin('guests', 'guests.id', '=', 'rents.guest_id')
					->leftjoin('rooms', 'rents.room_id', '=', 'rooms.id')
					->join('income_types', 'income_types.id', '=', 'incomes.income_type')
					->where(array('incomes.is_active' => 1, 'rents.guest_id' => $guest_id, 'incomes.income_type' => \DB::raw(\Config::get('constants.RENT'))))
					->where('incomes.amount', '>', 0)
					->whereRaw('DATE(tbl_incomes.date_of_income) >= ? AND DATE(tbl_incomes.date_of_income) <= ? ', [$start_date, $end_date])
					->groupby('incomes.date_of_income');

		$second_query = RentIncomes::select(\DB::raw('DATE_FORMAT(tbl_rent_incomes.date_of_rent, "%d/%m/%Y") as date'), \DB::raw('0 as amount'), \DB::raw('IF(SUM(tbl_rent_incomes.amount + tbl_rent_incomes.electricity_amount) > 0, SUM(tbl_rent_incomes.amount + tbl_rent_incomes.electricity_amount), 0) as rent_amount'), 
		  \DB::raw("(select if(sum(trt.amount + trt.electricity_amount) > 0, sum(trt.amount + trt.electricity_amount), 0) - (select if(sum(tc.amount) > 0, sum(tc.amount), 0) from tbl_incomes tc where tc.rent_id = tbl_rents.id and tc.date_of_income <= tbl_rent_incomes.date_of_rent and tc.income_type = '".\Config::get('constants.RENT')."') as balance_amount from tbl_rent_incomes trt where trt.rent_id = tbl_rents.id and trt.date_of_rent <= tbl_rent_incomes.date_of_rent) as balance"), 'rent_incomes.date_of_rent as date_of_rent')
						  ->leftjoin('rents', 'rents.id', '=', 'rent_incomes.rent_id')
						  ->leftjoin('guests', 'guests.id', '=', 'rents.guest_id')
						  ->leftjoin('rooms', 'rents.room_id', '=', 'rooms.id')
						  ->where(array('rent_incomes.is_active' => 1, 'rents.guest_id' => $guest_id))
						  ->whereRaw('DATE(tbl_rent_incomes.date_of_rent) >= ? AND DATE(tbl_rent_incomes.date_of_rent) <= ? ', [$start_date, $end_date])
						  ->groupby('rent_incomes.date_of_rent');

		$final_query = $first_query->unionAll($second_query)->orderby('date_of_rent', 'asc');
		$result = $final_query->get();
		/*echo "<pre>";
		print_r($result);die;*/
		return $result;
	}
	/**
	 * Get total Incomes.
	 *
	 * @return \Illuminate\Database\Eloquent\Collection|\App\Incomes[]
	 */
	public function getBalanceAmount ($rent_id)
	{

		$balance = RentIncomes::select(\DB::Raw("if(sum(tbl_rent_incomes.amount + tbl_rent_incomes.electricity_amount) > 0, sum(tbl_rent_incomes.amount + tbl_rent_incomes.electricity_amount), 0) - (select if(sum(tc.amount) > 0, sum(tc.amount), 0) from tbl_incomes tc left join tbl_rents tr on tr.id = tc.rent_id where tr.id = '$rent_id' and tc.income_type = '".\Config::get('constants.RENT')."') as balance"))
					->leftjoin('rents', 'rent_incomes.rent_id', '=', 'rents.id')
					->where([ 'rent_incomes.is_active' => 1, 'rents.id' => $rent_id ])
					->first();

		  return [ "balance" => $balance->balance ];

	}    

	/**
	 * Get total Incomes.
	 *
	 * @return \Illuminate\Database\Eloquent\Collection|\App\Incomes[]
	 */
	public function getTotalGuestIncomeUsingRentId ($rent_id)
	{
		$incomes = Incomes::select(\DB::Raw('IF(SUM(amount) > 0, SUM(amount), 0) as amount'))
					->leftjoin('rents', 'incomes.rent_id', '=', 'rents.id')
					->where([ 'incomes.is_active' => 1, 'rents.id' => $rent_id, 'incomes.income_type' => \DB::raw(\Config::get('constants.RENT')) ])
					->first();

		$rent = RentIncomes::select(\DB::Raw('IF(SUM(tbl_rent_incomes.amount + tbl_rent_incomes.electricity_amount) > 0, SUM(tbl_rent_incomes.amount + tbl_rent_incomes.electricity_amount), 0) as amount'))
					->leftjoin('rents', 'rent_incomes.rent_id', '=', 'rents.id')
					->where([ 'rent_incomes.is_active' => 1, 'rents.id' => $rent_id ])
					->first();

		$balance = RentIncomes::select(\DB::Raw("if(sum(tbl_rent_incomes.amount + tbl_rent_incomes.electricity_amount) > 0, sum(tbl_rent_incomes.amount + tbl_rent_incomes.electricity_amount), 0) - (select if(sum(tc.amount) > 0, sum(tc.amount), 0) from tbl_incomes tc left join tbl_rents tr on tr.id = tc.rent_id where tr.id = '$rent_id' and tc.income_type = '".\Config::get('constants.RENT')."') as balance"))
					->leftjoin('rents', 'rent_incomes.rent_id', '=', 'rents.id')
					->where([ 'rent_incomes.is_active' => 1, 'rents.id' => $rent_id ])
					->first();

		  return [ "incomes" => $incomes->amount, "rent" => $rent->amount, "balance" => $balance->balance ];

	}
}

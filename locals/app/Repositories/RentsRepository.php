<?php

namespace App\Repositories;

use App\Rents;

use App\ElectricityBill;

use App\Incomes;

use App\RentIncomes;

use App\Rooms;


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
                    ->groupBy('rents.room_id')
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

        return Rents::select('rents.id', 'rents.room_id', 'rents.guest_id', 'rents.checkin_date', 'rents.checkout_date', 'rents.user_id', 'users.name as entry_by', 'rooms.room_no', 'incomes.amount as advance', 'incomes.id as income_id', 'incomes.rent_amount_received as is_amount_received', 'guests.name', 'guests.email', 'guests.mobile_no', 'rents.id as rent_id', 'guests.id as guest_id', 'rents.is_incharge', 'rents.rent_amount', 'rents.incharge_set')
                    ->join('users', 'users.id', '=', 'rents.user_id', 'left')
                    ->join('guests', 'guests.id', '=', 'rents.guest_id', 'left')
                    ->join('rooms', 'rooms.id', '=', 'rents.room_id', 'left')
                    ->leftjoin('incomes', function($join) 
                     {
                         $join->on('incomes.rent_id', '=', 'rents.id')
                            ->on('incomes.income_type', '=', \DB::raw(\Config::get('constants.ADVANCE')));

                     })
                    ->where(array('rents.is_active' => 1, 'rents.checkout_date' => null, 'rents.room_id' => $data['room_id']))
                    ->orderBy('rents.id', 'desc')
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
                  /*\DB::raw('
                      IF((SUM(tbl_rt.amount) - IF(tbl_incomes.amount is not null, SUM(tbl_incomes.amount), 0)) > tbl_rent_incomes.amount, 
                        tbl_rent_incomes.amount, 
                      IF((SUM(tbl_rt.amount) - IF(tbl_incomes.amount is not null, SUM(tbl_incomes.amount), 0)) <= 0,
                        0, (SUM(tbl_rt.amount) - IF(tbl_incomes.amount is not null, SUM(tbl_incomes.amount), 0)))) as pending_amount')
                  )*/
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

                    ->join('guests', 'guests.id', '=', 'rents.guest_id', 'left')
                    ->join('rooms', 'rooms.id', '=', 'rents.room_id', 'left')
                    ->where(array('rent_incomes.is_active' => 1))
                    ->whereRaw('MONTH(tbl_rent_incomes.date_of_rent) = ? and YEAR(tbl_rent_incomes.date_of_rent) = ?', [$month, $year])
                    ->groupBy('rt.rent_id', 'rt1.rent_id', 'incomes.rent_id');
                    //echo $query->toSql();die;
        if($room_id != 0) {
          $query->where('rents.room_id', $room_id);
        }
        //echo $query->get();die;
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
                  /*\DB::raw('
                      IF((SUM(tbl_rt.amount) - IF(tbl_incomes.amount is not null, SUM(tbl_incomes.amount), 0)) > tbl_rent_incomes.amount, 
                        tbl_rent_incomes.amount, 
                      IF((SUM(tbl_rt.amount) - IF(tbl_incomes.amount is not null, SUM(tbl_incomes.amount), 0)) <= 0,
                        0, (SUM(tbl_rt.amount) - IF(tbl_incomes.amount is not null, SUM(tbl_incomes.amount), 0)))) as pending_amount')
                  )*/
                    \DB::raw('IF((IF(tbl_rt.amount is not null, tbl_rt.amount, 0) - IF(tbl_incomes.amount is not null, SUM(tbl_incomes.amount), 0)) < 0, 
                                IF((IF(tbl_rt.amount is not null, tbl_rt.amount, 0) + tbl_rent_incomes.amount + tbl_rent_incomes.electricity_amount - IF(tbl_incomes.amount is not null, SUM(tbl_incomes.amount), 0)) > 0,
                                  IF(tbl_rt.amount is not null, tbl_rt.amount, 0) + tbl_rent_incomes.amount + tbl_rent_incomes.electricity_amount - IF(tbl_incomes.amount is not null, SUM(tbl_incomes.amount), 0),
                                   0),
                                tbl_rent_incomes.amount + tbl_rent_incomes.electricity_amount)
                                as pending_amount'))
                    ->join('rents', 'rents.id', '=', 'rent_incomes.rent_id', 'left')
                    
                    ->leftjoin(\DB::raw("(SELECT SUM(amount + electricity_amount) as amount, rent_id from tbl_rent_incomes where is_active = 1 and DATE(tbl_rent_incomes.date_of_rent) <= '".$first_date."' group by rent_id) tbl_rt"), "rt.rent_id", "=", "rents.id")

                    ->leftjoin(\DB::raw("(SELECT SUM(amount) as amount, rent_id from tbl_incomes where income_type = '".\Config::get('constants.RENT')."' AND is_active = 1 group by rent_id) tbl_incomes"), "incomes.rent_id", "=", "rents.id")

                    ->leftjoin(\DB::raw("(SELECT SUM(amount + electricity_amount) as amount, rent_id from tbl_rent_incomes where is_active = 1 and DATE(tbl_rent_incomes.date_of_rent) >= '".$next_month_date."' group by rent_id) tbl_rt1"), "rt1.rent_id", "=", "rents.id")

                    ->join('guests', 'guests.id', '=', 'rents.guest_id', 'left')
                    ->join('rooms', 'rooms.id', '=', 'rents.room_id', 'left')
                    ->where(array('rent_incomes.is_active' => 0))
                    ->whereRaw('MONTH(tbl_rent_incomes.date_of_rent) = ? and YEAR(tbl_rent_incomes.date_of_rent) = ?', [$month, $year])
                    ->groupBy('rt.rent_id', 'rt1.rent_id', 'incomes.rent_id');
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
        $income_type = \Config::get('constants.RENT');
        return \DB::select(\DB::raw("SELECT rooms.room_no, COUNT(rent.id) as no_of_person_stayed, rooms.max_persons_allowed, rooms.total_rent_amount, IF(COUNT(rent.id) > 0, SUM(income.amount), 0) as rent_amount_get FROM tbl_rooms rooms left join tbl_rents rent on rent.room_id = rooms.id and ('$start_date' >= DATE(rent.checkin_date) or rent.checkin_date between '$start_date' and '$end_date') and IF(rent.checkout_date is null, true, '$end_date' <= rent.checkout_date) left join tbl_incomes income on income.rent_id = rent.id and income.income_type = '$income_type' and MONTH(income.date_of_income) = '$month' and YEAR(date_of_income) = '$year' where rooms.is_active = 1 group by rent.room_id, rooms.id having no_of_person_stayed = 0"));
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
        $income_type = \Config::get('constants.RENT');
        return \DB::select(\DB::raw("SELECT rooms.room_no, rooms.max_persons_allowed, rooms.total_rent_amount, IF(COUNT(rent.id) > 0, SUM(income.amount), 0) as rent_amount_get, COUNT(rent.id) as no_of_person_stayed FROM tbl_rooms rooms left join tbl_rents rent on rent.room_id = rooms.id and ('$start_date' >= DATE(rent.checkin_date) or rent.checkin_date between '$start_date' and '$end_date') and IF(rent.checkout_date is null, true, '$end_date' <= rent.checkout_date) left join tbl_incomes income on income.rent_id = rent.id and income.income_type = '$income_type' and MONTH(income.date_of_income) = '$month' and YEAR(date_of_income) = '$year' where rooms.is_active = 1 group by rent.room_id, rooms.id having  no_of_person_stayed > 0"));
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
        $income_type = \Config::get('constants.RENT');
        return \DB::select(\DB::raw("SELECT rooms.room_no, rooms.max_persons_allowed, rooms.total_rent_amount, IF(COUNT(rent.id) > 0, SUM(income.amount), 0) as rent_amount_get, COUNT(rent.id) as no_of_person_stayed FROM tbl_rooms rooms left join tbl_rents rent on rent.room_id = rooms.id and ('$start_date' >= DATE(rent.checkin_date) or rent.checkin_date between '$start_date' and '$end_date') and IF(rent.checkout_date is null, true, '$end_date' <= rent.checkout_date) left join tbl_incomes income on income.rent_id = rent.id and income.income_type = '$income_type' and MONTH(income.date_of_income) = '$month' and YEAR(date_of_income) = '$year' where rooms.is_active = 1 group by rent.room_id, rooms.id"));
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
                  /*\DB::raw('
                      IF((SUM(tbl_rt.amount) - IF(tbl_incomes.amount is not null, SUM(tbl_incomes.amount), 0)) > tbl_rent_incomes.amount, 
                        tbl_rent_incomes.amount, 
                      IF((SUM(tbl_rt.amount) - IF(tbl_incomes.amount is not null, SUM(tbl_incomes.amount), 0)) <= 0,
                        0, (SUM(tbl_rt.amount) - IF(tbl_incomes.amount is not null, SUM(tbl_incomes.amount), 0)))) as pending_amount')
                  )*/
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

                    ->leftjoin(\DB::raw("(SELECT SUM(amount) as amount, rent_id from tbl_incomes where income_type = '".\Config::get('constants.RENT')."' AND is_active = 1 group by rent_id) tbl_incomes"), "incomes.rent_id", "=", "rents.id")

                    ->leftjoin(\DB::raw("(SELECT SUM(amount + electricity_amount) as amount, rent_id from tbl_rent_incomes where is_active = 1 and DATE(tbl_rent_incomes.date_of_rent) >= '".$next_month_date."' group by rent_id) tbl_rt1"), "rt1.rent_id", "=", "rents.id")

                    ->join('guests', 'guests.id', '=', 'rents.guest_id', 'left')
                    ->join('rooms', 'rooms.id', '=', 'rents.room_id', 'left')
                    ->where(array('rent_incomes.is_active' => 1))
                    ->whereRaw('MONTH(tbl_rent_incomes.date_of_rent) = ? and YEAR(tbl_rent_incomes.date_of_rent) = ?', [$month, $year])
                    ->having('pending_amount', '=', 0)
                    ->groupBy('rt.rent_id', 'incomes.rent_id')->get();
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
                  /*\DB::raw('
                      IF((SUM(tbl_rt.amount) - IF(tbl_incomes.amount is not null, SUM(tbl_incomes.amount), 0)) > tbl_rent_incomes.amount, 
                        tbl_rent_incomes.amount, 
                      IF((SUM(tbl_rt.amount) - IF(tbl_incomes.amount is not null, SUM(tbl_incomes.amount), 0)) <= 0,
                        0, (SUM(tbl_rt.amount) - IF(tbl_incomes.amount is not null, SUM(tbl_incomes.amount), 0)))) as pending_amount')
                  )*/
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

                    ->join('guests', 'guests.id', '=', 'rents.guest_id', 'left')
                    ->join('rooms', 'rooms.id', '=', 'rents.room_id', 'left')
                    ->where(array('rent_incomes.is_active' => 1))
                    ->whereRaw('MONTH(tbl_rent_incomes.date_of_rent) = ? and YEAR(tbl_rent_incomes.date_of_rent) = ?', [$month, $year])
                    ->having('pending_amount', '>', 0)
                    ->groupBy('rt.rent_id', 'incomes.rent_id')->get();
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
                                as pending_amount'), 'rt1.amount as prev_amount')
                    
                    ->leftjoin(\DB::raw("(SELECT SUM(amount + electricity_amount) as amount, rent_id from tbl_rent_incomes where is_active = 1 and DATE(tbl_rent_incomes.date_of_rent) <= '".$first_date."' group by rent_id) tbl_rt"), "rt.rent_id", "=", "rent_incomes.rent_id")

                    ->leftjoin(\DB::raw("(SELECT SUM(amount) as amount, rent_id from tbl_incomes where income_type = '".\Config::get('constants.RENT')."' AND is_active = 1 group by rent_id) tbl_incomes"), "incomes.rent_id", "=", "rent_incomes.rent_id")

                    ->leftjoin(\DB::raw("(SELECT SUM(amount + electricity_amount) as amount, rent_id from tbl_rent_incomes where is_active = 1 and DATE(tbl_rent_incomes.date_of_rent) >= '".$next_month_date."' group by rent_id) tbl_rt1"), "rt1.rent_id", "=", "rent_incomes.rent_id")

                    ->where(array('rent_incomes.is_active' => 1))
                    ->whereRaw('MONTH(tbl_rent_incomes.date_of_rent) = ? and YEAR(tbl_rent_incomes.date_of_rent) = ?', [$month, $year])
                    ->groupBy('rt.rent_id', 'rt1.rent_id' , 'incomes.rent_id')
                    ->where('rent_incomes.rent_id', $rent_id);
        return $query->first();
    }
    
}

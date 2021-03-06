<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\Repositories\RentsRepository;

use App\Repositories\RoomsRepository;

use App\Repositories\IncomesRepository;

use App\Repositories\RentIncomesRepository;

use App\Repositories\ExpensesRepository;

use App\Repositories\GuestsRepository;

use App\Repositories\ElectricityBillRepository;

use App\Rents;

use App\RentIncomes;

use App\IncomeTypes;

use App\ExpenseTypes;

use App\Incomes;
   
use App\ElectricityBill;

use TextLocalHelper;

use Helper;

class AjaxController extends Controller
{
		/**
     * Create a new controller instance.
     *
     * @return void
     */
    protected $rents;
    protected $rent_incomes;
    protected $incomes;
    protected $rent_repo;
    protected $income_repo;
    protected $rent_income_repo;
    protected $expense_repo;
    protected $room_repo;
    protected $guest_repo;
    protected $income_types;
    protected $expense_types;
    public function __construct()
    {
        parent::__construct();
        $this->middleware('auth');
        $this->rents = new Rents();
        $this->rent_incomes = new RentIncomes();
        $this->incomes = new Incomes();
        $this->bills = new ElectricityBill();
        $this->rent_repo = new RentsRepository();
        $this->room_repo = new RoomsRepository();
        $this->income_repo = new IncomesRepository();
        $this->rent_income_repo = new RentIncomesRepository();
        $this->expense_repo = new ExpensesRepository();
        $this->bill_repo = new ElectricityBillRepository();
        $this->guest_repo = new GuestsRepository();
        $this->income_types = new IncomeTypes();
        $this->expense_types = new ExpenseTypes();
    }
    /**
     * Monthly rent report.
     *
     * @param  NULL
     * @return Response
    */
    public function createNewBill (Request $request)
    {

      $post_params = $request->all();
      if($post_params['month'] <= date('m') && $post_params['year'] <= date('Y')) {
     	   $data = $this->bills->createBillingDetailsForRooms($post_params['month'], $post_params['year']);

     	   $result = $this->bill_repo->activeRoomsElectricityBill($post_params['month'], $post_params['year']);

           $inactive_bill_monthly = $this->bill_repo->inActiveRoomsElectricityBill($post_params['month'], $post_params['year'])->toArray();

         return response()->json([ 'updated_room_ids' => $data, 'bill_result' => $result, 'inactive_bill_monthly' => $inactive_bill_monthly ]);
      } else {
         return response()->json(['errors' => 'Invalid', 'status' => 400], 400);
      }
        
    }

    /**
     * Monthly rent report.
     *
     * @param  NULL
     * @return Response
    */
    public function getGuestDetailsForRoom (Request $request)
    {

        $post_params = $request->all();

        $result = $this->rent_repo->getGuestDetailsForRoom($post_params)->toArray();

        
        $incharge_array = 
          array_filter($result, function ($value) {
              return $value['is_incharge'] == 1;
          });
        if(count($incharge_array) > 1) {
          array_walk ( $result, function (&$key) { $key["is_incharge"] = 0; } );
        }

        return response()->json([ 'guests' => $result, 'guest_ids' => array_column($result, 'guest_id') ]);
        
    }

    /**
     * Update the billing details using column key and column value.
     *
     * @param  Array
     * @return Response
    */
    public function updateElectricityBillByKey (Request $request)
    {

        $post_params = $request->all();

        $result = $this->bills->updateByKey($post_params);

        return response()->json($result);
        
    }

    /**
     * Remove bill records by ids.
     *
     * @param  ids
     * @return Response
    */
    public function deleteBillsByIds (Request $request)
    {

        $post_params = $request->all();

        $result = $this->bills->deleteByIds($post_params['ids']);

        $bill_monthly = $this->bill_repo->activeRoomsElectricityBill($post_params['month'], $post_params['year'])->toArray();

        $inactive_bill_monthly = $this->bill_repo->inActiveRoomsElectricityBill($post_params['month'], $post_params['year'])->toArray();

        return response()->json([ 'bill_monthly' => $bill_monthly, 'inactive_bill_monthly' => $inactive_bill_monthly ]);
        
    }

    /**
     * Move the deleted bills to active.
     *
     * @param  ids
     * @return Response
    */
    public function moveToActiveElectricBills (Request $request)
    {

        $post_params = $request->all();

        $result = $this->bills->updateActiveByIds($post_params['ids']);

        $bill_monthly = $this->bill_repo->activeRoomsElectricityBill($post_params['month'], $post_params['year'])->toArray();

        $inactive_bill_monthly = $this->bill_repo->inActiveRoomsElectricityBill($post_params['month'], $post_params['year'])->toArray();

        return response()->json([ 'bill_monthly' => $bill_monthly, 'inactive_bill_monthly' => $inactive_bill_monthly ]);
        
    }

    /**
     * Update the rent information by using key.
     *
     * @param  Array
     * @return Response
    */
    public function updateRoomRentByKey (Request $request)
    {

        $post_params = $request->all();
        $key = $post_params['column_key'];
        $value = $post_params['column_value'];
        $data = [ "guest_id" => $post_params['guest_id'], "id" => $post_params['id'], "rent_id" => $post_params['rent_id'] ];
        $data[$key] = $value;

        $validate = [ "email", "mobile_no", "amount", "checkin_date", "electricity_amount" ];
        if(in_array($key, $validate)) {
            $valid = $this->rents->keyValidate($data, $key);
            if($valid) {
               $result = $this->rents->updateByKey($post_params);
                $result['pending'] = $this->rent_repo->getPendingAmountUsingRentId($post_params['rent_id'], $post_params['month'], $post_params['year']);
                $result['result'] = $this->rent_repo->getPendingAmountUsingRentId($post_params['rent_id'], $post_params['month'], $post_params['year']);
               return response()->json($result);
            } else {
               $errors['error'] = $this->rents->errors();
               $errors['status'] = 400;
               return response()->json($errors, 400);
            }
        } else {
            $result = $this->rents->updateByKey($post_params);
            $result['pending'] = (int) $this->rent_repo->getPendingAmountUsingRentId($post_params['rent_id'], $post_params['month'], $post_params['year']);
            $result['result'] = $this->rent_repo->getPendingAmountUsingRentId($post_params['rent_id'], $post_params['month'], $post_params['year']);
            return response()->json($result);
        }
    }

    /**
     * Monthly rent report.
     *
     * @param  NULL
     * @return Response
    */
    public function createNewRentIncome (Request $request)
    {

      $post_params = $request->all();
      // /echo var_dump($post_params['month'] == date('m'));exit;
      if(($post_params['month'] < date('m') && $post_params['year'] <= date('Y')) || ($post_params['month'] == date('m') && $post_params['year'] == date('Y') && date('d') >= 26)) {
          $data = $this->rents->createRentsDetailsForRooms($post_params['month'], $post_params['year']);

          $rent_income_result = $this->rent_repo->activeRentsIncome($post_params['month'], $post_params['year']);

          $inactive_rent_income = $this->rent_repo->inActiveRentsIncome($post_params['month'], $post_params['year']);

         return response()->json([ 'updated_income_ids' => $data, 'rent_income_result' => $rent_income_result, 'inactive_rent_income' => $inactive_rent_income ]);
      } else {
         return response()->json(['errors' => 'Invalid', 'status' => 400, 'month' => date('m')], 400);
      }
        
    }

    /**
     * Remove room rent income records by ids.
     *
     * @param  ids
     * @return Response
    */
    public function deleteRentIncomesByIds (Request $request)
    {

        $post_params = $request->all();

        $result = $this->incomes->deleteByIds($post_params['ids']);

        $rent_income = $this->rent_repo->activeRentsIncome($post_params['month'], $post_params['year']);

        $inactive_rent_income = $this->rent_repo->inActiveRentsIncome($post_params['month'], $post_params['year']);

        return response()->json([ 'rent_income' => $rent_income, 'inactive_rent_income' => $inactive_rent_income ]);
        
    }

    /**
     * Move the deleted rents to active.
     *
     * @param  ids
     * @return Response
    */
    public function moveToActiveRents (Request $request)
    {

        $post_params = $request->all();

        $result = $this->incomes->updateActiveByIds($post_params['ids']);

        $rent_income = $this->rent_repo->activeRentsIncome($post_params['month'], $post_params['year']);

        $inactive_rent_income = $this->rent_repo->inActiveRentsIncome($post_params['month'], $post_params['year']);

        return response()->json([ 'rent_income' => $rent_income, 'inactive_rent_income' => $inactive_rent_income ]);
        
    }

    /**
     * Update the rent details.
     *
     * @param  ids
     * @return Response
    */
    public function updateRent (Request $request)
    {

        $post_params = $request->all();
        $key = $post_params['column_key'];
        $value = $post_params['column_value'];
        $data = [ "guest_id" => $post_params['guest_id'], "id" => $post_params['id'], "income_id" => $post_params['income_id'] ];

        $data[$key] = $value;

        $validate = [ "email", "mobile_no", "advance", "checkin_date", "checkout_date" ];

        if(in_array($key, $validate)) {
            $valid = $this->rents->rentKeyValidate($data, $key);
            if($valid) {
               $result = $this->rents->updateByRentKey($post_params);
               return response()->json($result);
            } else {
               $errors['error'] = $this->rents->errors();
               $errors['status'] = 400;
               return response()->json($errors, 400);
            }
        } else {
          if ($key == 'is_incharge') {
            $this->rents->updateIsInchargeByRoomId($post_params);
          }
          $result = $this->rents->updateByRentKey($post_params);
          return response()->json($result);
        }
        
    }

    /**
     * Create the new rent by room id.
     *
     * @param  id
     * @return Response
    */
    public function addNewRentByRoom (Request $request)
    {

        $post_params = $request->all();

        $room_id = $post_params['room_id'];
        
        $result = $this->rents->addNewRentByRoom($post_params['room_id']);

        $rents = $this->rent_repo->getGuestDetailsForRoom([ 'room_id' => $room_id ])->toArray();

        return response()->json([ 'rents' => $rents ]);
        
    }

    /**
     * Create the new rent by room id and guest id.
     *
     * @param  id
     * @return Response
    */
    public function addNewRentByRoomAndGuest (Request $request)
    {

        $post_params = $request->all();

        $room_id = $post_params['room_id'];

        $guest_id = $post_params['guest_id'];

        $valid = $this->rents->checkValidGuest($guest_id);

        if($valid) {
        
          $result = $this->rents->addNewRentByRoomAndGuest($room_id, $guest_id);

          $rents = $this->rent_repo->getGuestDetailsForRoom([ 'room_id' => $room_id ])->toArray();

          return response()->json([ 'rents' => $rents ]);
        } else {

          return response()->json(['msg' => 'Guest already registered.', 'status' => 400], 400);
        }
        
    }

    /**
     * Create the new rent by room id.
     *
     * @param  id
     * @return Response
    */
    public function roomReport (Request $request)
    {

        $post_params = $request->all();
        $start_date = date('Y-m-01', strtotime($post_params['start_date']));
        $end_date = date('Y-m-t', strtotime($post_params['end_date']));

        switch($post_params['type']) {

          case "all":
            $room_report = $this->rent_repo->getAllRooms($start_date, $end_date);
            break;
          case "vacant":
            $room_report = $this->rent_repo->getVacantRooms($start_date, $end_date);
            break;
          case "nonvacant":
            $room_report = $this->rent_repo->getNonVacantRooms($start_date, $end_date);
            break;
        }

        return response()->json([ 'report_result' => $room_report ]);
        
    }

    /**
     * Create the new rent by room id.
     *
     * @param  id
     * @return Response
    */
    public function rentReport (Request $request)
    {

      $post_params = $request->all();
      // /echo var_dump($post_params['month'] == date('m'));exit;
      $date = $post_params['start_date'];
      $month = date('m', strtotime($date));
      $year = date('Y', strtotime($date));
      if(($month < date('m') && $year <= date('Y')) || ($month == date('m') && $year == date('Y') && date('d') >= 26)) {

          $data = $this->rents->createRentsDetailsForRooms($month, $year);

          switch($post_params['type']) {
            case "all":
              $rent_income_result = $this->rent_repo->activeRentsIncome($month, $year);
              break;
            case "paid":
              $rent_income_result = $this->rent_repo->getGuestsRentPaid($month, $year);
              break;
            case "unpaid":
              $rent_income_result = $this->rent_repo->getGuestsRentsUnPaid($month, $year);
              break;
          }

         return response()->json([ 'updated_income_ids' => $data, 'report_result' => $rent_income_result ]);

      } else {
         return response()->json(['errors' => 'Invalid', 'status' => 400, 'month' => date('m')], 400);
      }

    }

    /**
     * income report by monthwise.
     *
     * @param  NULL
     * @return Response
    */
    public function incomeReport (Request $request)
    {

      $post_params = $request->all();
      // /echo var_dump($post_params['month'] == date('m'));exit;
      $date = $post_params['start_date'];
      $month = date('m', strtotime($date));
      $year = date('Y', strtotime($date));
      if(($month < date('m') && $year <= date('Y')) || ($month == date('m') && $year == date('Y') && date('d') >= 26)) {

          $data = $this->rents->createRentsDetailsForRooms($month, $year);

          switch($post_params['type']) {
            case "all":
              $rent_income_result = $this->rent_repo->activeRentsIncome($month, $year);
              break;
            case "paid":
              $rent_income_result = $this->rent_repo->getGuestsRentPaid($month, $year);
              break;
            case "unpaid":
              $rent_income_result = $this->rent_repo->getGuestsRentsUnPaid($month, $year);
              break;
          }

         return response()->json([ 'updated_income_ids' => $data, 'report_result' => $rent_income_result ]);

      } else {
         return response()->json(['errors' => 'Invalid', 'status' => 400, 'month' => date('m')], 400);
      }

    }

    /**
     * income report by monthwise.
     *
     * @param  NULL
     * @return Response
    */
    public function incomeReportBetweenDate (Request $request)
    {
      $post_params = $request->all();
      $start_date = date('Y-m-d', strtotime(str_replace('/', '-', $post_params['start_date'])));
      $end_date = date('Y-m-d', strtotime(str_replace('/', '-', $post_params['end_date'])));
      $report_options = Helper::checkRoleAndGetDate($start_date, $end_date);
			if (!$report_options['admin_role']) {
				$start_date = $report_options['start_date'];
				$end_date = $report_options['end_date'];
			}

      $monthly_income_report = $this->income_repo->getIncomesReportBetweenDates($start_date, $end_date);

      $total_amount = $this->income_repo->getTotalIncomesByDates($start_date, $end_date);

      return response()->json([ 'monthly_report' => $monthly_income_report, 'total_amount' => $total_amount->amount, "start_date" => date('d/m/Y', strtotime($start_date)), "end_date" => date('d/m/Y', strtotime($end_date)) ]);

    }

    /**
     * income report by monthwise.
     *
     * @param  NULL
     * @return Response
    */
    public function incomeReportMonth (Request $request)
    {
      $post_params = $request->all();
      $date = $post_params['start_date'];
      $month = date('m', strtotime($date));
      $year = date('Y', strtotime($date));

      if(($year < date('Y')) || ($year == date('Y') && $month <= date('m'))) {

        $start_date = date('Y-m-01', strtotime($date));

        $monthly_income_report_by_date = $this->income_repo->getMonthlyIncomesByDateReport($start_date);

        $monthly_income_report_by_date = json_decode(json_encode($monthly_income_report_by_date), true);

        $x_axis = array_column($monthly_income_report_by_date, 'date_of_income');

        $y_axis = json_encode(array_column($monthly_income_report_by_date, 'amount'), JSON_NUMERIC_CHECK);

        $total_amount = $this->income_repo->getTotalMonthlyIncomes($month, $year);

        return response()->json([ 'x_axis' => $x_axis, 'y_axis' => $y_axis, 'total_amount' => $total_amount->amount ]);

      } else {
         return response()->json(['errors' => 'Invalid', 'status' => 400, 'month' => date('m')], 400);
      }

    }

    /**
     * income report by yearwise.
     *
     * @param  NULL
     * @return Response
    */
    public function incomeReportYear (Request $request)
    {
      $post_params = $request->all();
      
      $year = $post_params['year'];

      if($year <= date('Y')) {

        $yearly_income_report_by_month = $this->income_repo->getYearlyIncomesByMonthReport($year);

        $yearly_income_report_by_month = json_decode(json_encode($yearly_income_report_by_month), true);

        $yearly_x_axis = array_keys($yearly_income_report_by_month[0]);

        $yearly_y_axis = json_encode(array_values($yearly_income_report_by_month[0]), JSON_NUMERIC_CHECK);

        $total_amount = $this->income_repo->getTotalYearlyIncomes($year);

        return response()->json([ 'yearly_x_axis' => $yearly_x_axis, 'yearly_y_axis' => $yearly_y_axis, 'total_amount' => $total_amount->amount ]);

      } else {
         return response()->json(['errors' => 'Invalid', 'status' => 400, 'year' => date('Y')], 400);
      }
    }

    /**
     * income report by monthwise.
     *
     * @param  NULL
     * @return Response
    */
    public function expenseReportBetweenDate (Request $request)
    {
      $post_params = $request->all();
      $start_date = date('Y-m-d', strtotime(str_replace('/', '-', $post_params['start_date'])));
      $end_date = date('Y-m-d', strtotime(str_replace('/', '-', $post_params['end_date'])));
      $report_options = Helper::checkRoleAndGetDate($start_date, $end_date);
			if (!$report_options['admin_role']) {
				$start_date = $report_options['start_date'];
				$end_date = $report_options['end_date'];
			}

      $monthly_expense_report = $this->expense_repo->getExpensesReportBetweenDates($start_date, $end_date);

      $total_amount = $this->expense_repo->getTotalExpensesByDates($start_date, $end_date);

      return response()->json([ 'monthly_report' => $monthly_expense_report, 'total_amount' => $total_amount->amount, "start_date" => date('d/m/Y', strtotime($start_date)), "end_date" => date('d/m/Y', strtotime($end_date)) ]);

    }

    /**
     * income report by monthwise.
     *
     * @param  NULL
     * @return Response
    */
    public function expenseReportMonth (Request $request)
    {
      $post_params = $request->all();
      $date = $post_params['start_date'];
      $month = date('m', strtotime($date));
      $year = date('Y', strtotime($date));

      if(($year < date('Y')) || ($year == date('Y') && $month <= date('m'))) {

        $start_date = date('Y-m-01', strtotime($date));

        $monthly_expense_report_by_date = $this->expense_repo->getMonthlyExpensesByDateReport($start_date);

        $monthly_expense_report_by_date = json_decode(json_encode($monthly_expense_report_by_date), true);

        $x_axis = array_column($monthly_expense_report_by_date, 'date_of_expense');

        $y_axis = json_encode(array_column($monthly_expense_report_by_date, 'amount'), JSON_NUMERIC_CHECK);

        $total_amount = $this->expense_repo->getTotalMonthlyExpenses($month, $year);

        return response()->json([ 'x_axis' => $x_axis, 'y_axis' => $y_axis, 'total_amount' => $total_amount->amount ]);

      } else {
         return response()->json(['errors' => 'Invalid', 'status' => 400, 'month' => date('m')], 400);
      }

    }

    /**
     * income report by yearwise.
     *
     * @param  NULL
     * @return Response
    */
    public function expenseReportYear (Request $request)
    {
      $post_params = $request->all();
      
      $year = $post_params['year'];

      if($year <= date('Y')) {

        $yearly_expense_report_by_month = $this->expense_repo->getYearlyExpensesByMonthReport($year);

        $yearly_expense_report_by_month = json_decode(json_encode($yearly_expense_report_by_month), true);

        $yearly_x_axis = array_keys($yearly_expense_report_by_month[0]);

        $yearly_y_axis = json_encode(array_values($yearly_expense_report_by_month[0]), JSON_NUMERIC_CHECK);

        $total_amount = $this->expense_repo->getTotalYearlyExpenses($year);

        return response()->json([ 'yearly_x_axis' => $yearly_x_axis, 'yearly_y_axis' => $yearly_y_axis, 'total_amount' => $total_amount->amount ]);

      } else {
         return response()->json(['errors' => 'Invalid', 'status' => 400, 'year' => date('Y')], 400);
      }
    }

    /**
     * Electricity report by monthwise for the corresponding year.
     *
     * @param  $year
     * @return Response
    */
    public function getElectricityBillReportMonth (Request $request)
    {
      $post_params = $request->all();
      
      $year = $post_params['year'];

      if($year <= date('Y')) {

        $monthly_bill_report = $this->bill_repo->getElectricityReportMonthwise($year);

        $monthly_bill_report = json_decode(json_encode($monthly_bill_report), true);

        $yearly_x_axis = array_keys($monthly_bill_report[0]);

        $yearly_y_axis = json_encode(array_values($monthly_bill_report[0]), JSON_NUMERIC_CHECK);

        $total_amount = $this->bill_repo->getTotalElectricityMonthwise($year);

        return response()->json([ 'yearly_x_axis' => $yearly_x_axis, 'yearly_y_axis' => $yearly_y_axis, 'total_amount' => $total_amount->amount ]);

      } else {
         return response()->json(['errors' => 'Invalid', 'status' => 400, 'year' => date('Y')], 400);
      }
    }

    /**
     * Electricity report between months.
     *
     * @param  
     *    $start_month
     *    $end_month
     * @return Response
    */
    public function getElectricityBillReportBetweenMonth (Request $request)
    {
      $post_params = $request->all();
      
      $month_start = date('m', strtotime($post_params['start_date']));
      $month_end = date('m', strtotime($post_params['end_date']));

      $month = date('m');

      if(date('d') < 26) {
        $month = date('m', strtotime("-1 month"));
      }

      //if($month_start <= $month && $month_end <= $month) {

        $bill_between_months = $this->bill_repo->getElectrictyBillsBetweenMonths($month_start, $month_end);

        $total_bill_between_months = $this->bill_repo->getTotalElectrictyBetweenMonths($month_start, $month_end);

        return response()->json([ 'monthly_report' => $bill_between_months, 'total_amount' => $total_bill_between_months->amount ]);

      /*} else {
         return response()->json(['errors' => 'Invalid', 'status' => 400, 'year' => date('Y')], 400);
      }*/
    }

    /**
     * Get guset list using the type of search.
     *
     * @param  NULL
     * @return Response
    */
    public function getGuestDetailsByType (Request $request)
    {

        $post_params = $request->all();

        $result = $this->guest_repo->getGuestDetailsByType($post_params)->toArray();

        return response()->json([ 'suggestions' => $result ]);
        
    }

    /**
     * Get guset details by id.
     *
     * @param  $id
     * @return Response
    */
    public function getGuestById (Request $request)
    {

        $post_params = $request->all();

        $result = $this->guest_repo->getGuestDetailsById($post_params['guest_id']);

        return response()->json([ 'guest' => $result ]);
        
    }

    /**
     * Get electricity bill by room no.
     *
     * @param  $room_no
     * @return Response
    */
    public function getBillByRoomNo (Request $request)
    {

        $post_params = $request->all();

        $room_id = $post_params['room_id'];

        $bill_monthly = $this->bill_repo->activeRoomsElectricityBill($post_params['month'], $post_params['year'], $room_id)->toArray();

        $inactive_bill_monthly = $this->bill_repo->inActiveRoomsElectricityBill($post_params['month'], $post_params['year'], $room_id)->toArray();

        return response()->json([ 'bill_monthly' => $bill_monthly, 'inactive_bill_monthly' => $inactive_bill_monthly ]);
        
    }

    /**
     * Get rent income using room no.
     *
     * @param  ids
     * @return Response
    */
    public function getRentByRoomNo (Request $request)
    {

        $post_params = $request->all();

        $room_id = $post_params['room_id'];

        $rent_income = $this->rent_repo->activeRentsIncome($post_params['month'], $post_params['year'], $room_id);

        $inactive_rent_income = $this->rent_repo->inActiveRentsIncome($post_params['month'], $post_params['year'], $room_id);

        return response()->json([ 'rent_income' => $rent_income, 'inactive_rent_income' => $inactive_rent_income ]);
        
    }

    /**
     * create the new income type.
     *
     * @param  
     * @return Response
    */
    public function createIncomeType (Request $request)
    {

        $post_params = $request->all();
        $data['type_of_income'] = $post_params['category'];
        $valid = $this->income_types->ajaxValidate($data);
        if($valid) {
          $id = $this->income_types->ajaxInsertOrUpdate($post_params);
          // redirect
          return response()->json([ 'success' => true, 'msg' => 'New income type created successfully!', 'id' => $id, 'value' => $post_params['category'] ]);

        } else {
          $errors = $this->income_types->errors();
          return response()->json(['errors' => 'Invalid', 'msg' => $errors, 'status' => 400], 400);          
        }
    }
    /**
     * create the new expense type.
     *
     * @param  
     * @return Response
    */
    public function createExpenseType (Request $request)
    {

        $post_params = $request->all();
        $data['type_of_expense'] = $post_params['category'];
        $valid = $this->expense_types->ajaxValidate($data);
        if($valid) {
         $id =  $this->expense_types->ajaxInsertOrUpdate($post_params);
          // redirect
          return response()->json([ 'success' => true, 'msg' => 'New expense type created successfully!', 'id' => $id, 'value' => $post_params['category'] ]);

        } else {
          $errors = $this->expense_types->errors();
          return response()->json(['errors' => 'Invalid', 'msg' => $errors, 'status' => 400], 400);          
        }
    }

    /**
     * create the new income.
     *
     * @param  
     * @return Response
    */
    public function createNewIncome (Request $request) {
      $post_params = $request->all();
      $post_params['income_type'] = \Config::get('constants.RENT');
      $month = $post_params['month'];
      $year = $post_params['year'];
      $rent_id = $post_params['rent_id'];
      $amount = $post_params['amount'];
      unset($post_params['month'], $post_params['year']);
      if ($this->incomes->ajaxValidate($post_params)) {
        $valid = $this->incomes->rentValidate($post_params);
        if (!$valid['error']) {
          $id = $this->incomes->insertOrUpdate($post_params);
          $pending = $this->rent_repo->getPendingAmountUsingRentId($rent_id, $month, $year);
          $pending_amount = (int) $pending->pending_amount;
          $user_details = $this->rent_repo->getUserDetailsUsingRent($rent_id);
          $textlocal = new TextLocalHelper(env('TEXT_LOCAL_USERNAME'), '', env('TEXT_LOCAL_KEY'));
          $messages = 
            ["messages" => 
              [ 
                [ "text" => "Your payment amount of &#8377;&nbsp;$amount received successfully!.".($pending_amount > 0 ? " Your pending amount is &#8377;&nbsp;$pending_amount" : ""), 'number' => $user_details->mobile_no ],
                //[ "text" => "You have recieved the new rent amount of &#8377;&nbsp;$amount from $user_details->name for room no $user_details->room_no".($pending_amount > 0 ? " His pending amount is &#8377;&nbsp;$pending_amount" : ""), 'number' => $this->setting['admin_mobile_no'] ]
              ]
            ];
          $messages['test'] = true;

          $response = json_decode(json_encode($textlocal->bulkJSONApi($messages)), true);
          
          return response()->json([ 'success' => true, 'msg' => 'New income created successfully!', 'id' => $id, 'pending' => $pending, 'response' => $response ]);
        } else {
          return response()->json(['errors' => 'Invalid', 'msg' => [$valid['msg']], 'status' => 400], 400);            
        }
      } else {
        $errors = $this->incomes->errors();
        return response()->json(['errors' => 'Invalid', 'msg' => $errors, 'status' => 400], 400);          
      }
    }
    /**
     * Get last 5 rent amount received for particular user.
     *
     * @param  
     * @return Response
    */
    public function getLast5PaidRental (Request $request) {
      $post_params = $request->all();    
      $last_paid_rent = $this->income_repo->getLastPaidRentUsingRentId($post_params)->toArray();
      return response()->json([ 'success' => true, 'last_paid_rent' => $last_paid_rent ]);
    }

    /**
     * Remove the rents by rent ids.
     *
     * @param  id
     * @return Response
    */
    public function removeRents (Request $request)
    {

        $post_params = $request->all();
        $ids = $post_params['ids'];
        $rent_incomes = $this->rent_income_repo->checkRentIncomeExists($ids);
        if ($rent_incomes['exists']) {
        	$errors['error'] = [ "msg" => "Some of the rent already created", "data" => $rent_incomes ];
	        $errors['status'] = 400;
	        return response()->json($errors, 400);
        } else {
        	$result = $this->rents->removeRentsByIds($ids);
        	return response()->json([ 'success' => true, "msg" => "Rent removed successfully" ]);	
        }
    }
    /**
     * Get all guests.
     *
     * @param  NULL
     * @return Response
    */
    public function getGuests (Request $request)
    {

        $post_params = $request->all();
        //print_r($post_params);die;
        $result = $this->guest_repo->getAllGuests($post_params);
        $total = $this->guest_repo->getTotalGuests();

        return response()->json([ "recordsTotal" => $total, "recordsFiltered" => $result['count'], "data" => $result['data'] ]);
        
    }

    /**
     * Get total income for guest.
     *
     * @param  NULL
     * @return Response
    */
    public function getGuestIncomeBetweenDate (Request $request)
    {
      $post_params = $request->all();
      $start_date = date('Y-m-d', strtotime(str_replace('/', '-', $post_params['start_date'])));
      $end_date = date('Y-m-d', strtotime(str_replace('/', '-', $post_params['end_date'])));
      $guest_id = $post_params['guest_id'];

      $monthly_guest_income_report = $this->income_repo->getGuestIncomesReportBetweenDates($start_date, $end_date, $guest_id);
      $total_amount = $this->income_repo->getTotalGuestIncomeByDates($start_date, $end_date, $guest_id);

      return response()->json([ 'monthly_report' => $monthly_guest_income_report, 'total_amount' => $total_amount, "start_date" => date('d/m/Y', strtotime($start_date)), "end_date" => date('d/m/Y', strtotime($end_date)) ]);
    }
    /**
     * Get advance and income details for guest
     *
     * @param  NULL
     * @return Response
    */
    public function getGuestDetails ($rent_id)
    {
      $guest_income = $this->income_repo->getTotalGuestIncomeUsingRentId($rent_id);
      $check_incharge = $this->rent_repo->checkInchargeAndGetDetails($rent_id);
      $rent_details = $this->rent_repo->getUserDetailsUsingRent($rent_id);
      return response()->json([ 'guest_income' => $guest_income, 'check_incharge' => $check_incharge, 'rent_details' => $rent_details ]);
    }

    /**
     * Update the user settlement
     *
     * @param  NULL
     * @return Response
    */
    public function updateSettlement (Request $request)
    {
      $post_params = $request->all();
      $valid = $this->rents->settleValidate($post_params);
      if($valid) {
        $this->rents->updateSettle($post_params);
        return response()->json([ 'success' => true, "msg" => 'Amount settled successfully' ]);
      } else {
         $errors['error'] = $this->rents->errors();
         $errors['status'] = 400;
         return response()->json($errors, 400);
      }
      
    }
    /**
     * Get settlement amount.
     *
     * @param  NULL
     * @return Response
    */
    public function calculateSettlement (Request $request)
    {
    	$post_params = $request->all();
    	$valid = $this->rents->checkoutDateValidate($post_params);
      if($valid) {
        $amount = $this->rent_repo->getSettlementAmount($post_params);
        return response()->json([ 'success' => true, "amount" => $amount ]);
      } else {
         $errors['error'] = $this->rents->errors();
         $errors['status'] = 400;
         return response()->json($errors, 400);
      }
    }

    /**
     * Update the electricity bill to rent
     *
     * @param  NULL
     * @return Response
    */
    public function updateElectricityBillToRent (Request $request)
    {
    	$post_params = $request->all();
      $result = $this->rents->updateElectricityBillToRent($post_params);
      return [ "success" => true, "msg" => "The electricity bills updated successfully to all rents" ];
    }

    /**
     * Monthly rent report.
     *
     * @param  NULL
     * @return Response
    */
    public function getSettledGuestDetailsForRoom (Request $request)
    {

        $post_params = $request->all();

        $result = $this->rent_repo->getSettledGuestDetailsForRoom($post_params)->toArray();

        return response()->json([ 'guests' => $result ]);
        
    }

    /**
     * Monthly rent report.
     *
     * @param  NULL
     * @return Response
    */
    public function saveOldRent (Request $request)
    {
      $post_params = $request->all();
      $rent_id = $post_params['rent_id'];
      $rent_incomes = $this->rent_income_repo->findByOldRent($rent_id);
      if (isset($rent_incomes->id)) {
      	$post_params['rent_income_id'] = $rent_incomes->id;
      }
      $valid = $this->rent_incomes->oldRentValidate($post_params);
      if($valid) {
        $this->rent_incomes->updateOldRent($post_params);
        return response()->json([ 'success' => true, "msg" => "Old rent updated successfully!" ]);
      } else {
         $errors['error'] = $this->rent_incomes->errors();
         $errors['status'] = 400;
         return response()->json($errors, 400);
      }
    }
}

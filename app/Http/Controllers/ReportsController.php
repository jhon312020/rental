<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\Rooms;

use App\Rents;

use App\Incomes;

use App\Expenses;

use App\ElectricityBill;

use App\Repositories\ElectricityBillRepository;

use App\Repositories\RoomsRepository;

use App\Repositories\RentsRepository;

use App\Repositories\IncomesRepository;

use App\Repositories\ExpensesRepository;

use App\Repositories\GuestsRepository;

class ReportsController extends Controller
{
		/**
     * Create a new controller instance.
     *
     * @return void
     */
		protected $rents;
   	protected $incomes;
    protected $rent_repo;
    protected $room_repo;
    protected $income_repo;
    protected $expense_repo;
    public function __construct()
    {
        $this->middleware('auth');
        $this->rents = new Rents();
        $this->incomes = new Incomes();
        $this->bills = new ElectricityBill();
        $this->rent_repo = new RentsRepository();
        $this->room_repo = new RoomsRepository();
        $this->bill_repo = new ElectricityBillRepository();
        $this->income_repo = new IncomesRepository();
        $this->expense_repo = new ExpensesRepository();
        $this->guest_repo = new GuestsRepository();
    }
    /**
     * Monthly room report.
     *
     * @param  NULL
     * @return Response
    */
    public function rooms ()
    {
    		$start_date = date('Y-m-01');
    		$end_date = date('Y-m-t');
				//echo $start_date.'----'.$end_date;die;
     	  $all_room = $this->rent_repo->getAllRooms($start_date, $end_date);

        return view('reports.rooms')
            ->with([ 
            		'all_room' => $all_room,
            		'start_date' => $start_date,
            		'end_date' => $end_date,
            	 ]);
        
    }

    /**
     * Monthly rent report.
     *
     * @param  NULL
     * @return Response
     */
    public function rentMonthlyReport ()
    {
        
        $month = date('m');
        $year = date('Y');
        $last_date = date('t');

        $start_date = date('Y-m-01');
        $end_date = date('Y-m-t');

        if(date('d') < 26) {
          $previous_month = date('Y-m-d', strtotime("-1 month"));

          $month = date('m', strtotime($previous_month));
          $year = date('Y', strtotime($previous_month));
          $last_date = date('t', strtotime($previous_month));

          $start_date = $previous_month;
          $end_date = date('Y-m-t', strtotime($previous_month));
        }

        $current_month = date('Y-m-d', strtotime($year.'-'.$month.'-01'));
        $next_month = date('Y-m-d', strtotime($year.'-'.$month.'-'.$last_date));
        
        //Create the current month electric bills details.
        $this->rents->createRentsDetailsForRooms($month, $year);

        $rent_monthly = $this->rent_repo->activeRentsIncome($month, $year);

      //print_r($rent_monthly);die;
       return view('reports.guests_rent')
            ->with([ 'rent_monthly' => $rent_monthly, 'start_date' => $start_date, 'end_date' => $end_date, ]);
    }

    /**
     * Monthly and yearly income report.
     *
     * @param  NULL
     * @return Response
     */
    public function incomeReport ()
    {
        $month = date('m');
        $year = date('Y');

        $start_date = date('Y-m-01');
        $end_date = date('Y-m-d');

        //Create the current month electric bills details.
        $monthly_income_report = $this->income_repo->getIncomesReportBetweenDates($start_date, $end_date);

        $monthly_income_report_by_date = $this->income_repo->getMonthlyIncomesByDateReport($start_date);

        $monthly_income_report_by_date = json_decode(json_encode($monthly_income_report_by_date), true);

        //echo "<pre>";

        $x_axis = array_column($monthly_income_report_by_date, 'date_of_income');

        $total_monthly_income = $this->income_repo->getTotalMonthlyIncomes($month, $year);

        //print_r($x_axis);die;

        
        $y_axis = json_encode(array_column($monthly_income_report_by_date, 'amount'), JSON_NUMERIC_CHECK);

        $yearly_income_report_by_month = $this->income_repo->getYearlyIncomesByMonthReport($year);

        $yearly_income_report_by_month = json_decode(json_encode($yearly_income_report_by_month), true);

        //echo "<pre>";
        //print_r($yearly_income_report_by_month);die;

        $yearly_x_axis = array_keys($yearly_income_report_by_month[0]);

        $total_yearly_income = $this->income_repo->getTotalYearlyIncomes($year);

        $total_incomes_between_date = $this->income_repo->getTotalIncomesByDates($start_date, $end_date);

        //print_r($x_axis);die;

        
        $yearly_y_axis = json_encode(array_values($yearly_income_report_by_month[0]), JSON_NUMERIC_CHECK);

        //print_r($yearly_y_axis);die;
        
       return view('reports.incomes')
            ->with([ 'monthly_income_report' => $monthly_income_report, 'monthly_income_report_by_date' => $monthly_income_report_by_date, 'yearly_income_report_by_month' => $yearly_income_report_by_month, 'start_date' => $start_date, 'end_date' => $end_date, 'x_axis' => $x_axis, 'y_axis' => $y_axis, 'yearly_x_axis' => $yearly_x_axis, 'yearly_y_axis' => $yearly_y_axis, 'total_monthly_income' => $total_monthly_income->amount, 'total_yearly_income' => $total_yearly_income->amount, 'total_incomes_between_date' => $total_incomes_between_date->amount ]);
    }

    /**
     * Monthly and yearly expense report.
     *
     * @param  NULL
     * @return Response
     */
    public function expenseReport ()
    {
        $month = date('m');
        $year = date('Y');

        $start_date = date('Y-m-01');
        $end_date = date('Y-m-d');

        //Create the current month electric bills details.
        $monthly_expense_report = $this->expense_repo->getExpensesReportBetweenDates($start_date, $end_date);

        $monthly_expense_report_by_date = $this->expense_repo->getMonthlyExpensesByDateReport($start_date);

        $monthly_expense_report_by_date = json_decode(json_encode($monthly_expense_report_by_date), true);

        //echo "<pre>";

        $x_axis = array_column($monthly_expense_report_by_date, 'date_of_expense');

        //print_r($x_axis);die;

        
        $y_axis = json_encode(array_column($monthly_expense_report_by_date, 'amount'), JSON_NUMERIC_CHECK);

        $yearly_expense_report_by_month = $this->expense_repo->getYearlyExpensesByMonthReport($year);

        $yearly_expense_report_by_month = json_decode(json_encode($yearly_expense_report_by_month), true);

        //echo "<pre>";
        //print_r($yearly_expense_report_by_month);die;

        $yearly_x_axis = array_keys($yearly_expense_report_by_month[0]);

        //print_r($x_axis);die;

        
        $yearly_y_axis = json_encode(array_values($yearly_expense_report_by_month[0]), JSON_NUMERIC_CHECK);

        $total_expenses_between_date = $this->expense_repo->getTotalExpensesByDates($start_date, $end_date);

        $total_monthly_expense = $this->expense_repo->getTotalMonthlyExpenses($month, $year);

        $total_yearly_expense = $this->expense_repo->getTotalYearlyExpenses($year);
        //print_r($yearly_y_axis);die;
        
       return view('reports.expenses')
            ->with([ 'monthly_expense_report' => $monthly_expense_report, 'monthly_expense_report_by_date' => $monthly_expense_report_by_date, 'yearly_expense_report_by_month' => $yearly_expense_report_by_month, 'start_date' => $start_date, 'end_date' => $end_date, 'x_axis' => $x_axis, 'y_axis' => $y_axis, 'yearly_x_axis' => $yearly_x_axis, 'yearly_y_axis' => $yearly_y_axis, 'total_monthly_expense' => $total_monthly_expense->amount, 'total_yearly_expense' => $total_yearly_expense->amount, 'total_expenses_between_date' => $total_expenses_between_date->amount ]);
    }

    /**
     * Monthly and yearly expense report.
     *
     * @param  NULL
     * @return Response
     */
    public function electricityBillReport ()
    {
        $month = date('m');
        $year = date('Y');

        $start_date = date('Y-m-01');
        $end_date = date('Y-m-d');

        $month_start = $month_end = date('m');

        if(date('d') < 26) {
          $start_date = date('Y-m-01', strtotime("-1 month"));
          $end_date = date('Y-m-t', strtotime("-1 month"));

          $month_start = $month_end = date('m', strtotime($start_date));
          $month = date('m', strtotime($start_date));
          $year = date('Y', strtotime($start_date));
        }

        $year_start = date('Y', strtotime("-10 years"));
        $year_end = date('Y');
        
        //Create the billing details for room if does not exists.
        $this->bills->createBillingDetailsForRooms($month, $year);
        //echo $month_start.$month_end;die;

        //Create the current month electric bills details.
        $monthly_bill_report = $this->bill_repo->getElectricityReportMonthwise($year);

        $total_monthly_bill = $this->bill_repo->getTotalElectricityMonthwise($year);

        $yearly_bill_report = [];//$this->bill_repo->getElectricityReportYearwise($year_start, $year_end);

        $total_yearly_bill = [];//$this->bill_repo->getTotalElectricityYearwise($year_start, $year_end);

        $monthly_bill_report = json_decode(json_encode($monthly_bill_report), true);

        $x_axis = array_keys($monthly_bill_report[0]);

        $y_axis = json_encode(array_values($monthly_bill_report[0]), JSON_NUMERIC_CHECK);

        //echo "<pre>";
        //print_r($yearly_expense_report_by_month);die;

        $yearly_x_axis = [];//array_keys($yearly_bill_report[0]);

        //print_r($x_axis);die;

        $yearly_y_axis = [];//json_encode(array_values($yearly_bill_report[0]), JSON_NUMERIC_CHECK);

        $bill_between_months = $this->bill_repo->getElectrictyBillsBetweenMonths($month_start, $month_end);

        $total_bill_between_months = $this->bill_repo->getTotalElectrictyBetweenMonths($month_start, $month_end);

        //print_r($yearly_y_axis);die;
        
       return view('reports.electricity')
            ->with([ 
              'monthly_bill_report' => $monthly_bill_report,
              'yearly_bill_report' => $yearly_bill_report,
              'bill_between_months' => $bill_between_months,
              'start_date' => $start_date,
              'end_date' => $end_date,
              'x_axis' => $x_axis,
              'y_axis' => $y_axis,
              'yearly_x_axis' => $yearly_x_axis,
              'yearly_y_axis' => $yearly_y_axis,
              'total_monthly_bill' => $total_monthly_bill->amount,
              //'total_yearly_bill' => $total_yearly_bill->amount,
              'total_bill_between_months' => $total_bill_between_months->amount
              ]);
    }

    /**
     * Guest income report.
     *
     * @param  NULL
     * @return Response
     */
    public function guestIncomeReport ($guest_id)
    {
        $month = date('m');
        $year = date('Y');

        $start_date = date('Y-m-01');
        $end_date = date('Y-m-t');
        /*$start_date = date('Y-m-01', strtotime("-1 month"));
        $end_date = date('Y-m-t', strtotime("-1 month"));*/
        $guest_details = $this->guest_repo->find($guest_id);
        $total_guest_income_between_date = $this->income_repo->getTotalGuestIncomeByDates($start_date, $end_date, $guest_id);
        //print_r($total_guest_income_between_date);die;
        $guest_income_between_date = $this->income_repo->getGuestIncomesReportBetweenDates($start_date, $end_date, $guest_id);

        $guest_income = $this->income_repo->getTotalGuestIncome($guest_id);
        return view('reports.guest_incomes')
            ->with([ 'guest_income_between_date' => $guest_income_between_date, 'total_guest_income_between_date' => $total_guest_income_between_date, 'start_date' => $start_date, 'end_date' => $end_date, 'guest_id' => $guest_id, 'guest_details' => $guest_details, 'guest_income' => $guest_income ]);
    }
}

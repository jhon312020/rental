<?php

/*
 * Taken from
 * https://github.com/laravel/framework/blob/5.3/src/Illuminate/Auth/Console/stubs/make/controllers/HomeController.stub
 */

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;

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
/**
 * Class HomeController
 * @package App\Http\Controllers
 */
class HomeController extends Controller
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
        //\View::share('page_name_active', trans('message.home_page'));
    }

    /**
     * Show the application dashboard.
     *
     * @return Response
     */
    public function index()
    {
        $start_date = date('Y-m-d', strtotime("-30 days"));
        $end_date = date('Y-m-d');

        $month = date('m');
        $year = date('Y');
        $date = date('Y-m-d');
        if(date('d') < 26) {
          $month = date('m', strtotime("-1 month"));
          $year = date('Y', strtotime("-1 month"));
          $date = date('Y-m-d', strtotime("-1 month"));
        }

        $total_income = $this->income_repo->getTotalIncomesByDates($start_date, $end_date);
        $total_expense = $this->expense_repo->getTotalExpensesByDates($start_date, $end_date);

        $overall_total_income = $this->income_repo->getOverallTotalIncomes();
        $overall_total_expense = $this->expense_repo->getOverallTotalExpenses();

        $income_month = $this->income_repo->getIncomesReportBetweenDatesGroup($start_date, $end_date)->toArray();
        $dates = array();
        $income_date = array_combine(array_column($income_month, 'date_of_income'), array_column($income_month, 'amount'));

        /*echo "<pre>";
        print_r($income_date);*/

        $expense_month = $this->expense_repo->getExpensesReportBetweenDatesGroup($start_date, $end_date)->toArray();

        $expense_date = array_combine(array_column($expense_month, 'date_of_expense'), array_column($expense_month, 'amount'));

        $income_y_axis = [];
        $expense_y_axis = [];
        while( $start_date <= $end_date ) {
          $data_date = date( "d/m/Y", strtotime($start_date) );
          $dates[] = $data_date;
          $start_date = date('Y-m-d', strtotime( "+1 day", strtotime($start_date) ));

          if(isset($income_date[$data_date])) {
            $income_y_axis[] = (int) $income_date[$data_date];
          } else {
            $income_y_axis[] = 0;
          }
          if(isset($expense_date[$data_date])) {
            $expense_y_axis[] = (int) $expense_date[$data_date];
          } else {
            $expense_y_axis[] = 0;
          }
        }

        $total_pending_rent = $this->income_repo->getTotalPendingRents($month, $year);

        $total_pending_guests = $this->income_repo->getTotalPendingGuests($month, $year);

        $overall_pending_rent = $this->income_repo->getOverallPendingRents();

        $overall_pending_guests = $this->income_repo->getOverallPendingGuests();

        $paid_guests = $this->rent_repo->getGuestsRentPaid($month, $year);

        $unpaid_guests = $this->rent_repo->getGuestsRentsUnPaid($month, $year);

        //echo $total_pending_guests->total;die;
      /*echo "<pre>";
       print_r($total_income);die;*/

        return view('home')
            ->with(
                [ 
                  'x_axis' => $dates,
                  'income_y_axis' => $income_y_axis,
                  'expense_y_axis' => $expense_y_axis,
                  'total_income' => $total_income->amount,
                  'total_expense' => $total_expense->amount,
                  'total_pending_rent' => $total_pending_rent->amount,
                  'total_pending_guests' => $total_pending_guests,
                  'overall_pending_rent' => $overall_pending_rent->amount,
                  'overall_pending_guests' => $overall_pending_guests,
                  'date' => $date,
                  'paid_guests' => $paid_guests,
                  'unpaid_guests' => $unpaid_guests,
                  'overall_total_income' => $overall_total_income->amount,
                  'overall_total_expense' => $overall_total_expense->amount 
                ]);
    }
}
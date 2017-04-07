<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\Rooms;

use App\Rents;

use App\Incomes;

use App\ElectricityBill;

use App\Repositories\ElectricityBillRepository;

use App\Repositories\RoomsRepository;

use App\Repositories\RentsRepository;

use App\Repositories\IncomesRepository;

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
        $last_date = date('t');

        $start_date = date('Y-m-01');
        $end_date = date('Y-m-t');

        $current_month = date('Y-m-d', strtotime($year.'-'.$month.'-01'));
        $next_month = date('Y-m-d', strtotime($year.'-'.$month.'-'.$last_date));
        
        //Create the current month electric bills details.
        $monthly_income_report = $this->income_repo->getMonthlyIncomesReport($month, $year);

        $monthly_income_report_by_date = $this->income_repo->getMonthlyIncomesByDateReport($start_date);

        $monthly_income_report_by_date = json_decode(json_encode($monthly_income_report_by_date), true);

        //echo "<pre>";

        $x_axis = array_column($monthly_income_report_by_date, 'date_of_income');

        //print_r($x_axis);die;

        
        $y_axis = json_encode(array_column($monthly_income_report_by_date, 'amount'), JSON_NUMERIC_CHECK);

        $yearly_income_report_by_month = $this->income_repo->getYearlyIncomesByMonthReport($year);

        $yearly_income_report_by_month = json_decode(json_encode($yearly_income_report_by_month), true);

        //echo "<pre>";
        //print_r($yearly_income_report_by_month);die;

        $yearly_x_axis = array_keys($yearly_income_report_by_month[0]);

        //print_r($x_axis);die;

        
        $yearly_y_axis = json_encode(array_values($yearly_income_report_by_month[0]), JSON_NUMERIC_CHECK);

        //print_r($yearly_y_axis);die;
        
       return view('reports.incomes')
            ->with([ 'monthly_income_report' => $monthly_income_report, 'monthly_income_report_by_date' => $monthly_income_report_by_date, 'yearly_income_report_by_month' => $yearly_income_report_by_month, 'start_date' => $start_date, 'end_date' => $end_date, 'x_axis' => $x_axis, 'y_axis' => $y_axis, 'yearly_x_axis' => $yearly_x_axis, 'yearly_y_axis' => $yearly_y_axis ]);
    }
}

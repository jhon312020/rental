<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\Repositories\UsersRepository;

use App\Users;

use Auth;

use TextLocalHelper;

class UsersController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
		protected $income_repo;
    protected $expense_repo;
    public function __construct()
    {
				$this->income_repo = new IncomesRepository();
				$this->expense_repo = new ExpensesRepository();
    }
    /**
     * Change password
     *
     * @param  NULL
     * @return Response
     */
    public function changePassword(Request $request)
    {
	    $today = date('d/m/Y');
	    $date = date('Y-m-d');
	    $total_incomes = $this->income_repo->getTotalIncomesByDates($date, $date);
	    $total_expenses = $this->expense_repo->getTotalExpensesByDates($date, $date);
	    $textlocal = new TextLocalHelper(env('TEXT_LOCAL_USERNAME'), '', env('TEXT_LOCAL_KEY'));
	    $messages = 
	      ["messages" => 
	        [ 
	          [ "text" => "Today($today) total income &#8377;&nbsp;$total_income, total expense &#8377;&nbsp;$total_expense", 'number' => $this->setting['admin_mobile_no'] ]
	        ]
	      ];
	    $messages['test'] = true;

	    $response = json_decode(json_encode($textlocal->bulkJSONApi($messages)), true);
	  }
}

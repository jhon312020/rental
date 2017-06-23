<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\Repositories\ExpensesRepository;

use App\Repositories\ExpenseTypesRepository;

use App\Expenses;

use Helper;

class ExpensesController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
		protected $expenses;
    protected $expense_repo;
    protected $expense_types_repo;
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission');
				$this->expenses = new Expenses();
        $this->expense_repo = new ExpensesRepository();
        $this->expense_types_repo = new ExpenseTypesRepository();

        $types = $this->expense_types_repo->allActive()->lists('type_of_expense', 'id')->toArray();
        $expense_types = array('' => 'Select') + $types;

        \View::share(['expense_types' => $expense_types]);
    }
    /**
     * Display a listing of the incomes.
     *
     * @return Response
    */
    public function index()
    {
        // get all the nerds
        $start_date = date('Y-m-01');
        $end_date = date('Y-m-d');
        $report_options = Helper::checkRoleAndGetDate($start_date, $end_date);
				if (!$report_options['admin_role']) {
					$start_date = $report_options['start_date'];
					$end_date = $report_options['end_date'];
				}

        //Create the current month electric bills details.
        $expenses = $this->expense_repo->getExpensesReportBetweenDates($start_date, $end_date);

        $total_expenses = $this->expense_repo->getTotalExpensesByDates($start_date, $end_date);

        /*echo "<pre>";
        print_r($expenses->toArray());die;*/
        // load the view and pass the nerds
        return view('expenses.index')
            ->with(array('expenses' => $expenses, 'total_expenses' => $total_expenses->amount, 'start_date' => $start_date, 'end_date' => $end_date ));
    }
	/**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        // load the create form (app/views/nerds/create.blade.php)
        return view('expenses.create');
    }
	/**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(Request $request)
    {
		$data = $request->all();
		$valid = $this->expenses->validate($data);
		if($valid) {
			$this->expenses->insertOrUpdate($data);
			// redirect
			return \Redirect::to('expenses');
		} else {
			$errors = $this->expenses->errors();
			return redirect()->back()->withErrors($errors)->withInput();
		}
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        // get the nerd
        $expense = $this->expenses->find($id);
        //print_r($expense);die;
        // show the edit form and pass the nerd
        return view('expenses.edit')
            ->with(['expense' => $expense]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update($id, Request $request)
    {
        $data = $request->all();
        $data['id'] = $id;
        $valid = $this->expenses->validate($data);
        if($valid) {
            $this->expenses->insertOrUpdate($data);
            // redirect
            return \Redirect::to('expenses');
        } else {
            $errors = $this->expenses->errors();
            return redirect()->back()->withErrors($errors)->withInput();
        }
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
       $data = array('is_active' => 0, 'id' => $id);
       $this->expenses->insertOrUpdate($data);
       \Session::flash('message', trans('message.expense_remove_success'));
       return \Redirect::to('expenses');
    }
}

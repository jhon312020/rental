<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\Repositories\IncomesRepository;

use App\Repositories\IncomeTypesRepository;

use App\Incomes;

use Helper;

class IncomesController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
	protected $incomes;
    protected $income_repo;
    protected $income_types_repo;
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission');
				$this->incomes = new Incomes();
        $this->income_repo = new IncomesRepository();
        $this->income_types_repo = new IncomeTypesRepository();

        $types = $this->income_types_repo->allActive()->lists('type_of_income', 'id')->toArray();
        $income_types = array('' => 'Select') + $types;

        \View::share(['income_types' => $income_types]);
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
        $incomes = $this->income_repo->getIncomesReportBetweenDates($start_date, $end_date);
        $total_incomes = $this->income_repo->getTotalIncomesByDates($start_date, $end_date);

        //$incomes = $this->income_repo->allActive();

        // load the view and pass the nerds
        return view('incomes.index')
            ->with(array('incomes' => $incomes, 'start_date' => $start_date, 'end_date' => $end_date, 'total_incomes' => $total_incomes->amount ));
    }
	/**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        // load the create form (app/views/nerds/create.blade.php)
        return view('incomes.create');
    }
	/**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(Request $request)
    {
		$data = $request->all();
		$valid = $this->incomes->validate($data);
		if($valid) {
			$this->incomes->insertOrUpdate($data);
			// redirect
			return \Redirect::to('incomes');
		} else {
			$errors = $this->incomes->errors();
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
        $income = $this->incomes->find($id);
        //print_r($guest);die;
        // show the edit form and pass the nerd
        return view('incomes.edit')
            ->with(['income' => $income]);
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
        $valid = $this->incomes->validate($data);
        if($valid) {
            $this->incomes->insertOrUpdate($data);
            // redirect
            return \Redirect::to('incomes');
        } else {
            $errors = $this->incomes->errors();
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
       $this->incomes->insertOrUpdate($data);
       \Session::flash('message', trans('message.income_remove_success'));
       return \Redirect::to('incomes');
    }
}

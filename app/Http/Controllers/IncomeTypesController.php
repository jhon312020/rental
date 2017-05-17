<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\Repositories\IncomeTypesRepository;

use App\IncomeTypes;

class IncomeTypesController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
	protected $income_types;
    protected $income_types_repo;
    public function __construct()
    {
        $this->middleware('auth');
		$this->income_types = new IncomeTypes();
        $this->income_types_repo = new IncomeTypesRepository();

        //\View::share('page_name_active', trans('message.income_types'));
    }
    /**
     * Display a listing of the income-types.
     *
     * @return Response
    */
    public function index()
    {
        // get all the nerds
        $income_types = $this->income_types_repo->allActiveEdit();

        // load the view and pass the nerds
        return view('income-types.index')
            ->with(array('income_types' => $income_types));
    }
	/**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        // load the create form (app/views/nerds/create.blade.php)
        return view('income-types.create');
    }
	/**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(Request $request)
    {
		$data = $request->all();
		$valid = $this->income_types->validate($data);
		if($valid) {
			$this->income_types->insertOrUpdate($data);
			// redirect
			return \Redirect::to('income-types');
		} else {
			$errors = $this->income_types->errors();
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
        $editable = \Config::get('constants.INCOME_EDIT');
        if(in_array($id, $editable)) {
            return \Redirect::to('income-types');
        }
        // get the nerd
        $income_type = $this->income_types->find($id);
        //print_r($income_type);die;
        // show the edit form and pass the nerd
        return view('income-types.edit')
            ->with(['income_type' => $income_type]);
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
        $valid = $this->income_types->validate($data);
        if($valid) {
            $this->income_types->insertOrUpdate($data);
            // redirect
            return \Redirect::to('income-types');
        } else {
            $errors = $this->income_types->errors();
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
       $this->income_types->insertOrUpdate($data);
       \Session::flash('message', trans('message.income_type_remove_success'));
       return \Redirect::to('income-types');
    }
}

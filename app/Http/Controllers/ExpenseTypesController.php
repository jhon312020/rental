<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\Repositories\ExpenseTypesRepository;

use App\ExpenseTypes;

class ExpenseTypesController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
	protected $expense_types;
    protected $expense_types_repo;
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission');
				$this->expense_types = new ExpenseTypes();
        $this->expense_types_repo = new ExpenseTypesRepository();

        //\View::share('page_name_active', trans('message.expense_types'));
    }
    /**
     * Display a listing of the expense-types.
     *
     * @return Response
    */
    public function index()
    {
        // get all the nerds
        $expense_types = $this->expense_types_repo->allActive();

        // load the view and pass the nerds
        return view('expense-types.index')
            ->with(array('expense_types' => $expense_types));
    }
	/**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        // load the create form (app/views/nerds/create.blade.php)
        return view('expense-types.create');
    }
	/**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(Request $request)
    {
		$data = $request->all();
		$valid = $this->expense_types->validate($data);
		if($valid) {
			$this->expense_types->insertOrUpdate($data);
			// redirect
			return \Redirect::to('expense-types');
		} else {
			$errors = $this->expense_types->errors();
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
        $expense_type = $this->expense_types->find($id);
        //print_r($guest);die;
        // show the edit form and pass the nerd
        return view('expense-types.edit')
            ->with(['expense_type' => $expense_type]);
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
        $valid = $this->expense_types->validate($data);
        if($valid) {
            $this->expense_types->insertOrUpdate($data);
            // redirect
            return \Redirect::to('expense-types');
        } else {
            $errors = $this->expense_types->errors();
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
       $this->expense_types->insertOrUpdate($data);
       \Session::flash('message', trans('message.expense_type_remove_success'));
       return \Redirect::to('expense-types');
    }
}

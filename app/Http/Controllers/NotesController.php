<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\Notes;

use App\Repositories\NotesRepository;

class NotesController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
	protected $notes;
    protected $notes_repo;
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission');
		$this->notes = new Notes();
        $this->notes_repo = new NotesRepository();

        //\View::share('page_name_active', trans('message.notes'));
    }
    /**
     * Display a listing of the notes.
     *
     * @return Response
    */
    public function index()
    {
        // get all the nerds
        $notes = $this->notes_repo->allActive();

        // load the view and pass the nerds
        return view('notes.index')
            ->with(array('notes' => $notes));
    }
	/**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        // load the create form (app/views/nerds/create.blade.php)
        return view('notes.create');
    }
	/**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(Request $request)
    {
		$data = $request->all();
		$valid = $this->notes->validate($data);
		if($valid) {
			$this->notes->insertOrUpdate($data);
			// redirect
			return \Redirect::to('notes');
		} else {
			$errors = $this->notes->errors();
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
        $notes = $this->notes->find($id);
        //print_r($guest);die;
        // show the edit form and pass the nerd
        return view('notes.edit')
            ->with(['notes' => $notes]);
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
        $valid = $this->notes->validate($data);
        if($valid) {
            $this->notes->insertOrUpdate($data);
            // redirect
            return \Redirect::to('notes');
        } else {
            $errors = $this->notes->errors();
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
       $this->notes->insertOrUpdate($data);
       \Session::flash('message', trans('message.notes_remove_success'));
       return \Redirect::to('notes');
    }
}

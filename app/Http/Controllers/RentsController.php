<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\Repositories\RentsRepository;

use App\Repositories\RoomsRepository;

use App\Rents;

class RentsController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
	protected $rents;
    protected $rent_repo;
    protected $room_repo;
    public function __construct()
    {
        $this->middleware('auth');
		$this->rents = new Rents();
        $this->rent_repo = new RentsRepository();
        $this->room_repo = new RoomsRepository();

        $room = $this->room_repo->allActive()->lists('room_no', 'id')->toArray();        
        $rooms = array('' => 'Select') + $room;
        \View::share(['page_name_active' => trans('message.rents_page'), 'rooms' => $rooms]);
    }
    /**
     * Display a listing of the rents.
     *
     * @return Response
    */
    public function index()
    {
        // get all the nerds
        $rents = $this->rent_repo->allActive();

        // load the view and pass the nerds
        return view('rents.index')
            ->with(array('rents' => $rents));
    }
	/**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        // load the create form (app/views/nerds/create.blade.php)
        return view('rents.create');
    }
	/**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(Request $request)
    {
		$data = $request->all();
		$valid = $this->rents->validate($data);
		if($valid) {
			$this->rents->insertOrUpdate($data);
			// redirect
			return \Redirect::to('rents');
		} else {
			$errors = $this->rents->errors();
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
        $booking = $this->rents->find($id);
        //print_r($guest);die;
        // show the edit form and pass the nerd
        return view('rents.edit')
            ->with(['booking' => $booking]);
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
        $valid = $this->rents->validate($data);
        if($valid) {
            $this->rents->insertOrUpdate($data);
            // redirect
            return \Redirect::to('rents');
        } else {
            $errors = $this->rents->errors();
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
       $this->rents->insertOrUpdate($data);
       \Session::flash('message', trans('message.rent_remove_success'));
       return \Redirect::to('rents');
    }
}

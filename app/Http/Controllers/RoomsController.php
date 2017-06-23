<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\Repositories\RoomsRepository;

use App\Rooms;

class RoomsController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
	protected $rooms;
    protected $room_repo;
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission');
		$this->rooms = new Rooms();
        $this->room_repo = new RoomsRepository();

        //\View::share('page_name_active', trans('message.rooms'));
    }
    /**
     * Display a listing of the rooms.
     *
     * @return Response
    */
    public function index()
    {
        // get all the nerds
        $rooms = $this->room_repo->allActive();

        // load the view and pass the nerds
        return view('rooms.index')
            ->with(array('rooms' => $rooms));
    }
	/**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        // load the create form (app/views/nerds/create.blade.php)
        return view('rooms.create');
    }
	/**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(Request $request)
    {
		$data = $request->all();
		$valid = $this->rooms->validate($data);
		if($valid) {
			$this->rooms->insertOrUpdate($data);
			// redirect
			return \Redirect::to('rooms');
		} else {
			$errors = $this->rooms->errors();
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
        $room = $this->rooms->find($id);
        //print_r($guest);die;
        // show the edit form and pass the nerd
        return view('rooms.edit')
            ->with(['room' => $room]);
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
        $valid = $this->rooms->validate($data);
        if($valid) {
            $this->rooms->insertOrUpdate($data);
            // redirect
            return \Redirect::to('rooms');
        } else {
            $errors = $this->rooms->errors();
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
       $this->rooms->insertOrUpdate($data);
       \Session::flash('message', trans('message.room_remove_success'));
       return \Redirect::to('rooms');
    }
}

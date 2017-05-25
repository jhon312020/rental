<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\Repositories\GuestsRepository;

use App\Guests;

class GuestsController extends Controller
{
	/**
     * Create a new controller instance.
     *
     * @return void
     */
	protected $guests;
    protected $guest_repo;
    public function __construct()
    {
        $this->middleware('auth');
		$this->guests = new Guests();
        $this->guest_repo = new GuestsRepository();

        //\View::share('page_name_active', trans('message.guests'));
    }
    /**
     * Display a listing of the guests.
     *
     * @return Response
    */
    public function index(GuestsRepository $guests)
    {
        // get all the nerds
        $guests = $this->guest_repo->allActive();

        // load the view and pass the nerds
        return view('guests.index')
            ->with(array('guests' => $guests));
    }
	/**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        // load the create form (app/views/nerds/create.blade.php)
        return view('guests.create');
    }
	/**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(Request $request)
    {
		$data = $request->all();
		$valid = $this->guests->validate($data);
		if($valid) {
			$this->guests->insertOrUpdate($data);
			// redirect
			return \Redirect::to('guests');
		} else {
			$errors = $this->guests->errors();
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
        $guest = $this->guests->find($id);
        //print_r($guest);die;
        // show the edit form and pass the nerd
        return view('guests.edit')
            ->with(['guest' => $guest]);
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
        $valid = $this->guests->validate($data);
        if($valid) {
            $this->guests->insertOrUpdate($data);
            // redirect
            return \Redirect::to('guests');
        } else {
            $errors = $this->guests->errors();
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
       $this->guests->insertOrUpdate($data);
       \Session::flash('message', trans('message.guests_remove_success'));
       return \Redirect::to('guests');
    }
    /**
     * Return the guest deatils using search key value.
     *
     * @param  string  $search_key
     * @param  string  $search_value
     * @return Response Array
     *    
     */
    public function getGuestByKey()
    {
       $result = array(array('label' => 'test', 'value' => 'data'));
       $countries = [
           [ 'value' =>  'Andorra', 'data' => 'AD' ],
           // ...
           [ 'value' => 'Zimbabwe', 'data' => 'ZZ' ]
        ];

       return response()->json(['suggestions' => $countries]);
    }
}

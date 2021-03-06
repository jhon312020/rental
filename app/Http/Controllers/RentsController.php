<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\Repositories\RentsRepository;

use App\Repositories\RoomsRepository;

use App\Repositories\ElectricityBillRepository;

use App\Rents;

use App\ElectricityBill;

use TextLocalHelper;

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
	protected $bills;
	public function __construct()
	{
		$this->middleware('auth');
		$this->middleware('permission');
		$this->rents = new Rents();
		$this->rent_repo = new RentsRepository();
		$this->room_repo = new RoomsRepository();
		$this->bills = new ElectricityBill();
		$this->bill_repo = new ElectricityBillRepository();

		$room = $this->room_repo->allActive()->lists('room_no', 'id')->toArray();        
		$rooms = array('' => 'Select') + $room;
		\View::share(['rooms' => $rooms]);
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

		/*echo "<pre>";
		print_r($rents);die;*/

		// load the view and pass the nerds
		return view('rents.index')
			->with(array('rents' => $rents));
	}
	/**
	 * Display a listing of the rents.
	 *
	 * @return Response
	*/
	public function settledRents ()
	{
		// get all the nerds
		$rents = $this->rent_repo->allSettled();

		/*echo "<pre>";
		print_r($rents);die;*/

		// load the view and pass the nerds
		return view('rents.settled_rents')
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

	  /*echo "<pre>";
	  print_r($data);die;*/

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
		$rent = $this->rents->findWithGuest($id);

		//print_r($rent);die;

		// show the edit form and pass the nerd
		return view('rents.edit')
			->with(['rent' => $rent]);
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
		$data['rent']['id'] = $id;
		$valid = $this->rents->editValidate($data);
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

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroyByRoom ($room_id)
	{
	   $this->rents->removeUsingRoom($room_id);
	   \Session::flash('message', trans('message.rent_remove_success'));
	   return \Redirect::to('rents');
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function editRentByRoomId ($room_id)
	{
	   // get the nerd
		$rent = $this->rent_repo->getGuestDetailsForRoom([ 'room_id' => $room_id ])->toArray();

		$room = $this->room_repo->getActiveRoomById($room_id);
		if ($room) {
			$room_result = $room->toArray();
		} else {
			return view('errors.500');
		}
		/*echo "<pre>";
		print_r($rent);die;*/

		if($room) {
			// show the edit form and pass the nerd
			return view('rents.edit_rent')
				->with(['rent' => $rent, 'room' => $room ]);
		} else {
			return \Redirect::to('rooms');
		}
		
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
		if(date('d') < 26) {
		  $previous_month = date('Y-m-d', strtotime("-1 month"));

		  $month = date('m', strtotime($previous_month));
		  $year = date('Y', strtotime($previous_month));
		  $last_date = date('t', strtotime($previous_month));
		}

		$current_month = date('Y-m-d', strtotime($year.'-'.$month.'-01'));
		$next_month = date('Y-m-d', strtotime($year.'-'.$month.'-'.$last_date));
		//Create the current month electric bills details.
		$this->bills->createBillingDetailsForRooms($month, $year);

		$bill_monthly = $this->bill_repo->activeRoomsElectricityBill($month, $year)->toArray();

		$inactive_bill_monthly = $this->bill_repo->inActiveRoomsElectricityBill($month, $year)->toArray();

		//Create the current month electric bills details.
		$this->rents->createRentsDetailsForRooms($month, $year);

		$rent_monthly = $this->rent_repo->activeRentsIncome($month, $year)->toArray();

		/*echo "<pre>";
		print_r($rent_monthly);die;*/

		$inactive_rent_monthly = $this->rent_repo->inActiveRentsIncome($month, $year)->toArray();

		$rooms = $this->room_repo->allActive()->toArray();
		
		//print_r($rent_monthly);die;
	   return view('rents.rent_monthly')
			->with(['rent_monthly' => $rent_monthly, 'bill_monthly' => $bill_monthly, 'inactive_bill_monthly' => $inactive_bill_monthly, 'inactive_rent_monthly' => $inactive_rent_monthly, "date_month" => $current_month, 'next_month' => $next_month, 'rooms' => $rooms]);
	}

	/**
	 * Monthly rent report.
	 *
	 * @param  NULL
	 * @return Response
	 */
	public function billMonthlyReport ()
	{
		/*$helper = new TextLocalHelper('brightsaharia@gmail.com', '', 'RJWyEb7HAoM-jLVyWi94a3NyV85Dc0A4VQHbwwNWh8');
		echo "<pre>";
		//$response = $helper->sendsms([ "9659404824", "73585769242" ], 'Hai', 'TXTLCL', '12');
		$response = $helper->bulkJSONApiTest();
		print_r($response);die;*/

		$month = date('m');
		$year = date('Y');
		$last_date = date('t');
		if(date('d') < 26) {
		  $previous_month = date('Y-m-d', strtotime("-1 month"));

		  $month = date('m', strtotime($previous_month));
		  $year = date('Y', strtotime($previous_month));
		  $last_date = date('t', strtotime($previous_month));
		}

		$current_month = date('Y-m-d', strtotime($year.'-'.$month.'-01'));
		$next_month = date('Y-m-d', strtotime($year.'-'.$month.'-'.$last_date));
		//Create the current month electric bills details.
		$this->bills->createBillingDetailsForRooms($month, $year);

		$bill_monthly = $this->bill_repo->activeRoomsElectricityBill($month, $year)->toArray();

		$inactive_bill_monthly = $this->bill_repo->inActiveRoomsElectricityBill($month, $year)->toArray();

		//Create the current month electric bills details.
		//$this->rents->createRentsDetailsForRooms($month, $year);

		$rent_monthly = $this->rent_repo->activeRentsIncome($month, $year)->toArray();

		/*echo "<pre>";
		print_r($rent_monthly);die;*/

		$inactive_rent_monthly = $this->rent_repo->inActiveRentsIncome($month, $year)->toArray();

		$rooms = $this->room_repo->allActive()->toArray();
		
		//print_r($rent_monthly);die;
	   return view('rents.bill_monthly')
			->with(['rent_monthly' => $rent_monthly, 'bill_monthly' => $bill_monthly, 'inactive_bill_monthly' => $inactive_bill_monthly, 'inactive_rent_monthly' => $inactive_rent_monthly, "date_month" => $current_month, 'next_month' => $next_month, 'rooms' => $rooms]);
	}

	/**
	 * update the old rent record.
	 *
	 * @param  NULL
	 * @return Response
	 */
	public function listUpdate ()
	{
	   // get the nerd
		$rent = $this->rent_repo->getAllGuestDetails()->toArray();

		$rooms = $this->room_repo->allActive()->toArray();
		//print_r($room);die;

		if($rent) {
			// show the edit form and pass the nerd
			return view('rents.list_update')
				->with(['rent' => $rent, 'rooms' => $rooms ]);
		} else {
			return \Redirect::to('rents');
		}
		
	}

	/**
	 * Display a listing of the rents.
	 *
	 * @return Response
	*/
	public function settlement ()
	{
		// get all the nerds
		$rents = $this->rent_repo->getAllActiveRents();

		/*echo "<pre>";
		print_r($rents);die;*/

		// load the view and pass the nerds
		return view('rents.settlement')
			->with(array('rents' => $rents));
	}
}

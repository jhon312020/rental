<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\Repositories\UsersRepository;

use App\Users;

use Auth;

class UsersController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
	protected $users;
    protected $user_repo;
    public function __construct()
    {
        $this->middleware('auth');
		$this->users = new Users();
        $this->user_repo = new UsersRepository();

        //\View::share('page_name_active', trans('message.user_profile'));
    }
    /**
     * Display a listing of the users.
     *
     * @return Response
    */
    public function index()
    {
        // get all the nerds
        $users = $this->user_repo->allActive();

        // load the view and pass the nerds
        return view('users.index')
            ->with(array('users' => $users));
    }
	/**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        // load the create form (app/views/nerds/create.blade.php)
        return view('users.create');
    }
	/**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(Request $request)
    {
		$data = $request->all();
		$valid = $this->users->validate($data);
		if($valid) {
			$this->users->insertOrUpdate($data);
			// redirect
			return \Redirect::to('users');
		} else {
			$errors = $this->users->errors();
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
        $user = $this->users->find($id);
        //print_r($guest);die;
        // show the edit form and pass the nerd
        return view('users.edit')
            ->with(['user' => $user]);
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
        $valid = $this->users->validate($data);
        if($valid) {
            $this->users->insertOrUpdate($data);
            // redirect
            return \Redirect::to('users');
        } else {
            $errors = $this->users->errors();
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
       $this->users->insertOrUpdate($data);
       \Session::flash('message', trans('message.user_remove_success'));
       return \Redirect::to('users');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  NULL
     * @return Response
     */
    public function profile(Request $request)
    {
        $id = Auth::User()->id;
        //echo $id;die;
        if ($request->isMethod('post')) {
            $data = $request->all();
            $data['id'] = $id;
            $valid = $this->users->validate($data);
            //print_r($_FILES);die;
            if($valid) {
                $this->users->insertOrUpdate($request);
                \Session::flash('message', trans('message.user_update_profile_success'));
                // redirect
                return \Redirect::to('users/profile');
            } else {
                $errors = $this->users->errors();
                return redirect()->back()->withErrors($errors)->withInput();
            }
        } else {
            // get the nerd
            $user = $this->users->find($id);
            //print_r($user);die;
            // show the edit form and pass the nerd
            return view('users.profile')
                ->with(['user' => $user]);    
        }
    }
}

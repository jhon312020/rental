<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\Repositories\SettingsRepository;

use App\Settings;

use Auth;

class SettingsController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
	protected $settings;
    protected $setting_repo;
    public function __construct()
    {
        $this->middleware('auth');
		$this->settings = new Settings();
        $this->setting_repo = new SettingsRepository();

        //\View::share('page_name_active', trans('message.settings_page'));
    }
    /**
     * Display a listing of the settings.
     *
     * @return Response
    */
    public function index()
    {
        // load the view and pass the nerds
        return view('settings.index');
    }
	/**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(Request $request)
    {
		$data = $request->all();
		$valid = $this->settings->validate($data);
		if($valid) {
			$this->settings->insertOrUpdate($request);
			// redirect
			return \Redirect::to('settings');
		} else {
			$errors = $this->settings->errors();
			return redirect()->back()->withErrors($errors)->withInput();
		}
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\Repositories\SettingsRepository;

use App\Settings;

use App\Messages;

use App\Repositories\RentIncomesRepository;

use App\Repositories\MessagesRepository;

use App\Repositories\RentsRepository;

use Auth;

use TextLocalHelper;

class MessageController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
    */
    protected $textlocal;
    protected $rent_incomes_repo;
    protected $messages_repo;
    protected $messages;
    protected $rents_repo;
    protected $live = [ "username" => '', "key" => '' ];
    protected $local = [ "username" => 'bright@proisc.com', "key" => 'VZScz8zU9Wo-8A2NTR9q7ikdIacimuqvAoGhAh2vRP' ];
    protected $local1 = [ "username" => 'brightsaharia@gmail.com', "key" => 'RJWyEb7HAoM-jLVyWi94a3NyV85Dc0A4VQHbwwNWh8' ];
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission');
        $this->rent_incomes_repo = new RentIncomesRepository();
        $this->messages_repo = new MessagesRepository();
        $this->messages = new Messages();
        $this->rents_repo = new RentsRepository();
        $this->textlocal = new TextLocalHelper($this->local1['username'], '', $this->local1['key']);
    }
    /**
     * Display a listing of the settings.
     *
     * @return Response
    */
    public function index (Request $request)
    {
        $month = date('m');
        $year = date('Y');
        $month_name = date('F');
        $end_date = date('Y-m-d');
        $start_date = date('Y-m-d');
        if(date('d') < 26) {
          $previous_month = date('Y-m-d', strtotime("-1 month"));
          $month = date('m', strtotime($previous_month));
          $year = date('Y', strtotime($previous_month));
          $month_name = date('F', strtotime($previous_month));
          //$end_date = date('Y-m-d', strtotime($previous_month));
          $start_date = date('Y-m-d', strtotime($previous_month));
        }
        if (\Request::isMethod('post')) {
          $post_params = $request->all();
          $post_date = $post_params['start_date'];
          $month = date('m', strtotime($post_date));
          $year = date('Y', strtotime($post_date));
          $month_name = date('F', strtotime($post_date));
          //$end_date = date('Y-m-d', strtotime($post_date));
          $start_date = date('Y-m-d', strtotime($post_date)); 

          //Send message to all users.
          if ($post_params['send_message'] == 1) {
            $is_existing_message = $this->rent_incomes_repo->checkMessageSend($month, $year);
            //print_r($is_existing_message);die;
            if (!$is_existing_message['isSend']) {
              $messages = $this->rent_incomes_repo->getMobileNos($is_existing_message['rent_income_id']);
              $messages['test'] = true;
              //print_r($messages);die;
              $response = json_decode(json_encode($this->textlocal->bulkJSONApi($messages)), true);
              $this->messages->remove($month, $year);
              //print_r($response);die;
              if (isset($response['messages'])) {
                $array = [];
                foreach ($response['messages'] as $key => $message) {
                  //echo $message['messages'][0]['recipient'];die;
                  $array[$key]['message'] = $message['message']['content'];
                  $array[$key]['date_of_message'] = date('Y-m-d', strtotime(date($year.'-'.$month.'-26')));
                  $mobile_no = $message['messages'][0]['recipient'];
                  $mobile_no = substr($mobile_no, 2, strlen($mobile_no));
                  $array[$key]['rent_income_id'] = implode(',', $this->rent_incomes_repo->getRentIncomeIdsUsingMobileno($mobile_no, $month, $year)['rent_income_id']);
                  $array[$key]['delivery_status'] = 1;
                }
                //print_r($array);die;
                $this->messages->insertOrUpdate($array, $month, $year);
              }

              //Save the undelivered message in the DB.
              if (isset($response['messages_not_sent'])) {
                $array = [];
                foreach ($response['messages_not_sent'] as $key => $message) {
                  $mobile_no = $message['number'];
                  //echo $mobile_no;die;
                  $array[$key]['rent_income_id'] = implode(',', $this->rent_incomes_repo->getRentIncomeIdsUsingMobileno($mobile_no, $month, $year)['rent_income_id']);
                  $array[$key]['error_message'] = $message['error']['message'];
                  $array[$key]['message'] = $message['message'];
                  $array[$key]['date_of_message'] = date('Y-m-d', strtotime(date($year.'-'.$month.'-26')));
                  $array[$key]['delivery_status'] = 0;
                }
                //print_r($array);die;
                $this->messages->insertOrUpdate($array, $month, $year);
              }
            }
          }
        }
        //echo "<pre>";
        //echo $start_date.'--------'.$end_date;die;
        //echo $month.'--------'.$year;die;
        $deliveredUsers = $this->messages_repo->getDeleiverdUsers($month, $year);
        $nonDeliveredUsers = $this->messages_repo->getNonDeleiverdUsers($month, $year);
        $count_of_messages = count($deliveredUsers->toArray());
        //echo $count_of_messages;die;
        //print_r($deliveredUsers);die;
        //print_r($nonDeliveredUsers);die;
        // load the view and pass the nerds
        return view('messages.index')->with([ 'deliveredUsers' => $deliveredUsers, 'nonDeliveredUsers' => $nonDeliveredUsers, 'month' => $month, 'year' => $year, 'month_name' => $month_name, 'end_date' => $end_date, 'start_date' => $start_date, 'message_count' => $count_of_messages ]);
    }
}

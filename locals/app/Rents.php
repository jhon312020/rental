<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\Guests;

use App\Incomes;

use App\RentIncomes;

class Rents extends Model
{
    private $post = [];
    private $rules = 
			[
				"rent.room_id" => "required",
				"rent.checkin_date" => "required|date_format:d/m/Y",
				"rent.checkout_date" => "date_format:d/m/Y",
			];
		private $attribute_names = 
			[
				"rent.room_id" => "room no",
				"rent.checkin_date" => "Checkin date",
				"rent.checkout_date" => "Checkout date",
			];

		private $edit_attributes_name = 
			[
				"rent.room_id" => "room no",
				"rent.checkin_date" => "Checkin date",
				"rent.checkout_date" => "Checkout date",
				"guest.name" => 'name',
				"guest.email" => 'email',
				"guest.mobile_no" => 'mobile no',
				"rent.advance" => 'advance',
			];

		private $edit_rules = 
			[
				"rent.checkin_date" => "required|date_format:d/m/Y",
				"rent.checkout_date" => "date_format:d/m/Y",
				"guest.name" => 'required',
				"rent.advance" => "required|numeric",
				"guest.email" => 'required|email|unique:guests,email',
				"guest.mobile_no" => 'required|digits:10|unique:guests,mobile_no',
			];

			private $key_rules = 
				[
					"email" => 'required|email|unique:guests,email',
					"mobile_no" => 'required|digits:10|unique:guests,mobile_no',
					"amount" => "required|numeric",
					"electricity_amount" => "required|numeric",
					"checkin_date" => "required|date_format:d/m/Y",
				];

			private $rent_key_rules = 
				[
					"email" => 'required|email|unique:guests,email',
					"mobile_no" => 'required|digits:10|unique:guests,mobile_no',
					"advance" => "required|numeric",
					"name" => "required",
					"checkin_date" => "required|date_format:d/m/Y",
					"checkout_date" => "required|date_format:d/m/Y",
				];
	/**
   * Validation all necessary fields.
   *
   * @param  Array  $data
   * @return Response Bool
  */
	public function validate($data)
  {
  		//print_r($data);die;
  		$this->post = $data;
  		$unique_value = array('email', 'mobile_no');
  		$guest_rules = array(
	  			"name" => 'required',
					"email" => 'required|email|unique:guests,email',
					"mobile_no" => 'required|digits:10|unique:guests,mobile_no',
					"advance" => 'required|numeric',
					"rent_amount" => 'required|numeric',
				);
  		//print_r($data);die;
  		/* Form the rules for data validation */
  		if(isset($data['guest'])) {
	  		foreach($data['guest'] as $key => $array_data) {
	  			foreach($array_data as $name => $value) {
	  				
	  				$input_name = 'guest.'. $key .'.'.$name;
	  				if(isset($guest_rules[$name])) {
	  					$this->attribute_names[$input_name] = str_replace('_', ' ', $name);
	  				}
	  				if(in_array($name, $unique_value)) {
	  					if(isset($array_data['guest_id'])) {
	  						$this->rules[$input_name] = $guest_rules[$name].','.$array_data['guest_id'].',id,is_active,1';
	  						
	  					} else {
	  						$this->rules[$input_name] = $guest_rules[$name].',NULL,id,is_active,1';
	  					}
	  				} else {
	  					if(isset($guest_rules[$name])) {
	  						$this->rules[$input_name] = $guest_rules[$name];
	  					}
	  				}

	  				if(isset($array_data['guest_id']) && $array_data['guest_id'] && !isset($array_data['rent_id'])) {
	  					$this->rules['guest.'.$key.'.guest_id'] = 'notexists:rents,guest_id,is_active,1,checkout_date,NULL';
	  				}

	  			}
	  		}
  		}


	      // make a new validator object
			$v = \Validator::make($data, $this->rules);

			$v->after(function($v) use($data) {
				if(isset($data['guest'])) {
					$unique_email = $this->checkUnique('email');
					if(!$unique_email) {
						$v->errors()->add('email', 'Email must be unique!');
					}
					$unique_mobile = $this->checkUnique('mobile_no');
					if(!$unique_mobile) {
						$v->errors()->add('mobile_no', 'Mobile no must be unique!');
					}
				} else {
					$v->errors()->add('guest', 'Atleast add one guest!');
				}

				if(isset($data['rent']['id'])) {
	  			if(isset($data['rent']['checkin_date']) && $data['rent']['checkin_date'] != '') {
	  				$checkin_validate = $this->checkinValidate($data['rent']['checkin_date'], $data['rent']['id']);

	  				if(!$checkin_validate['valid']) {
	  					$v->errors()->add('rent.checkin_date', $checkin_validate['msg']);
	  				}
	  			}
	  			if(isset($data['rent']['checkout_date']) && $data['rent']['checkout_date'] != '') {
	  				$checkout_validate = $this->checkoutValidate($data['rent']['checkout_date'], $data['rent']['id']);

	  				if(!$checkout_validate['valid']) {
	  					$v->errors()->add('rent.checkout_date', $checkout_validate['msg']);
	  				}
	  			}
	  		} else {
	  			
	  			$checkin_date = date('Y-m-d', strtotime(str_replace('/', '-', $data['rent']['checkin_date'])));
	  			$checkout_date = date('Y-m-d', strtotime(str_replace('/', '-', $data['rent']['checkout_date'])));

	  			if($data['rent']['checkout_date'] != '' && strtotime($checkin_date) >= strtotime($checkout_date)) {
	  				$v->errors()->add('rent.checkout_date', 'Checkout date should be greater than '.$data['rent']['checkin_date']);
	  			}
	  			//echo strtotime($checkin_date) .'----'. strtotime("today");die;
	  			//var_dump($data['rent']['checkin_date'] != '' && strtotime($checkin_date) > strtotime("today"));die;
	  			if($data['rent']['checkin_date'] != '' && strtotime($checkin_date) >= strtotime("today")) {
	  				$v->errors()->add('rent.checkin_date', 'Checkin date should be less than '.date('d/m/y'));
	  			}

	  		}


			});

			//Set the name of the error message.
			$v->setAttributeNames($this->attribute_names);

			$this->post = $data;
			// check for failure
			if ($v->fails())
			{
					// set errors and return false
					$this->errors = $v;
					return false;
			}
			// validation pass
			return true;
    }

    public function editValidate ($data) {
    	$this->edit_rules['guest.email'] = $this->edit_rules['guest.email'].','.$data['guest']['guest_id'].',id,is_active,1';
    	$this->edit_rules['guest.mobile_no'] = $this->edit_rules['guest.mobile_no'].','.$data['guest']['guest_id'].',id,is_active,1';

    	$this->post = $data;
    	
    	// make a new validator object
			$v = \Validator::make($data, $this->edit_rules);
			//print_r($v->fails());die;
			$v->after(function($v) use($data) {
				//echo $data['rent']['checkin_date'];die;
				if(isset($data['rent']['id'])) {
					
	  			if(isset($data['rent']['checkin_date']) && $data['rent']['checkin_date'] != '') {
	  				//echo $data['rent']['checkin_date'];die;
	  				$checkin_validate = $this->checkinValidate($data['rent']['checkin_date'], $data['rent']['id']);
	  				//echo "asdfasdf";die;
	  				//print_r($checkin_validate);die;
	  				if(!$checkin_validate['valid']) {
	  					$v->errors()->add('rent.checkin_date', $checkin_validate['msg']);
	  				}
	  			}
	  			if(isset($data['rent']['checkout_date']) && $data['rent']['checkout_date'] != '') {
	  				$checkout_validate = $this->checkoutValidate($data['rent']['checkout_date'], $data['rent']['id'], $data['rent']['checkin_date']);

	  				if(!$checkout_validate['valid']) {
	  					$v->errors()->add('rent.checkout_date', $checkout_validate['msg']);
	  				}
	  			}
	  		}
			});
			//echo "asdfasdf12";die;
			//Set the name of the error message.
			$v->setAttributeNames($this->edit_attributes_name);

			
			// check for failure
			if ($v->fails())
			{
					// set errors and return false
					$this->errors = $v;
					return false;
			}
			// validation pass
			return true;
    }

    public function checkinValidate ($date, $rent_id) {
    	$income_model = new RentIncomes();
    	$checkin_date = date('Y-m-d', strtotime(str_replace("/", "-", $date)));

    	$rent_data = $income_model->where([ "rent_id" => $rent_id, "is_active" => 1 ])->orderBy('date_of_rent', 'asc')->first();
    	//echo $rent_id;die;
    	
    	if($rent_data) {
    		$rent_date = $rent_data->date_of_rent;
    		$rent_date = date('Y-m-d', strtotime(str_replace("/", "-", $rent_date)));
    		//echo $rent_date.'----'.$checkin_date;die;
    		//echo strtotime($rent_date) .'----'. strtotime($checkin_date);die;
    		if(strtotime($rent_date) < strtotime($checkin_date)) {
    			return [ "valid" => false, "msg" => "Checkin date should be less than ".date('d/m/Y', strtotime($rent_date)) ];
    		} else if(strtotime($checkin_date) > strtotime("today")) {
    			return [ "valid" => false, "msg" => "Checkin date should be less than or equal to ".date('d/m/Y') ];
    		}
    	}
    	return [ "valid" => true ];
    	
    }

    public function amountValidate ($amount, $rent_id, $rent_income_id, $key) {
    	$income_model = new Incomes();
    	$rent_incomes = new RentIncomes();
    	$income = $income_model->select(\DB::raw("SUM(amount) as amount"))->where([ "rent_id" => $rent_id, 'income_type' => \DB::raw(\Config::get('constants.RENT')), 'is_active' => 1 ])->first();
      
      $rent_incomes_result = $rent_incomes->select(\DB::raw("SUM(amount + electricity_amount) as amount"))->where("rent_id", $rent_id)->where('id', '<>', $rent_income_id)->first();

      $rent_incomes_amount = $rent_incomes->select('amount', 'electricity_amount')->where("id", $rent_income_id)->first();
      $remain_amount = $rent_incomes_amount->amount;
      if ($key == 'amount') {
      	$remain_amount = $rent_incomes_amount->electricity_amount;
      }
      if ($rent_incomes_result->amount + $amount + $remain_amount < $income->amount) {
          return [ "valid" => false, "msg" => "Value should be greater than or equal to ".($income->amount - $remain_amount - $rent_incomes_result->amount) ];
       }

       return [ "valid" => true ];
    }

    public function checkoutValidate ($date, $rent_id, $checkin_date) {
    	$income_model = new RentIncomes();

    	$checkout_date = date('Y-m-d', strtotime(str_replace("/", "-", $date)));
    	if (!$checkin_date) {
				return [ "valid" => false, "msg" => "Please add a valid checkin date" ];
    	}
    	$checkin_date = date('Y-m-d', strtotime(str_replace("/", "-", $checkin_date)));
    	
    	$rent_data = $income_model->where([ "rent_id" => $rent_id, "is_active" => 1 ])->orderBy('date_of_rent', 'desc')->first();
    	if($rent_data) {
    		$rent_date = $rent_data->date_of_rent;
    		$rent_date = date('Y-m-d', strtotime(str_replace("/", "-", $rent_date)));

    		if(date('Y', strtotime($checkout_date)) == date('Y', strtotime($rent_date)) && (date('m', strtotime($checkout_date)) == date('m', strtotime($rent_date)) || date('m', strtotime($checkout_date)) == date('m', strtotime($rent_date)) + 1) && strtotime($checkout_date) > strtotime($checkin_date)) {

    			return [ "valid" => true ];

    		} else if(strtotime($checkout_date) < strtotime($checkin_date)) {

	    		return [ "valid" => false, "msg" => "Checkout date should be greater than ".date('d/m/Y', strtotime($checkin_date)) ];

	    	} else {

    			return [ "valid" => false, "msg" => "Checkout date should be in the month of ".date('m', strtotime($rent_date)).' '.date('Y', strtotime($rent_date)) ];

    		}
    		
    	} else if(strtotime($checkout_date) < strtotime($checkin_date)) {

    		return [ "valid" => false, "msg" => "Checkout date should be greater than ".date('d/m/Y', strtotime($checkin_date)) ];

    	}
    	return [ "valid" => true ];
    }

    public function keyValidate ($data, $key) {

    	$rules = $this->key_rules;
    	if($key == 'email' || $key == 'mobile_no') {
          $rules[$key] = $rules[$key].','.$data['guest_id'].',id,is_active,1';
      }

      $this->key_rules = [];
      $this->key_rules[$key] = $rules[$key];

    	// make a new validator object
			$v = \Validator::make($data, $this->key_rules);

			$v->after(function($v) use($data, $key) {
				if($key == 'checkin_date') {
					$checkin_validate = $this->checkinValidate($data['checkin_date'], $data['rent_id']);

					if(!$checkin_validate['valid']) {
						$v->errors()->add('checkin_date', $checkin_validate['msg']);
					}
				}
				if ($key == 'amount' || $key == 'electricity_amount') {
					$amount_validate = $this->amountValidate($data[$key], $data['rent_id'], $data['id'], $key);
					if(!$amount_validate['valid']) {
						$v->errors()->add($key, $amount_validate['msg']);
					}
				}
			});

			$this->post = $data;
			// check for failure
			if ($v->fails())
			{
					// set errors and return false
					$this->errors = $v->errors()->all();
					return false;
			}
			// validation pass
			return true;
    }

    public function rentKeyValidate ($data, $key) {

    	$rules = $this->rent_key_rules;
    	if($key == 'email' || $key == 'mobile_no') {
          $rules[$key] = $rules[$key].','.$data['guest_id'].',id,is_active,1';
      }

      $this->key_rules = [];
      $this->key_rules[$key] = $rules[$key];

    	// make a new validator object
			$v = \Validator::make($data, $this->key_rules);

			$v->after(function($v) use($data, $key) {
				if($key == 'checkin_date') {
					$checkin_validate = $this->checkinValidate($data['checkin_date'], $data['id']);

					if(!$checkin_validate['valid']) {
						$v->errors()->add('checkin_date', $checkin_validate['msg']);
					}
				}
				if($key == 'checkout_date') {
					$rental_data = $this->find($data['id']);
					$checkout_validate = $this->checkoutValidate($data['checkout_date'], $data['id'], $rental_data->checkin_date);

					if(!$checkout_validate['valid']) {
						$v->errors()->add('checkout_date', $checkout_validate['msg']);
					}
				}
			});

			$this->post = $data;
			// check for failure
			if ($v->fails())
			{
					// set errors and return false
					$this->errors = $v->errors()->all();
					return false;
			}
			// validation pass
			return true;
    }

    public function checkUnique ($input_name) {
    	$array = array_column($this->post['guest'], $input_name);

    	$unique = array_unique($array);
    	
    	if(count($array) == count($unique)) {
    		return true;
    	}

    	return false;
    }
    /**
     * Revert back if any errors after validation.
     *
     * @param  null
     * @return Response
     */
		public function errors()
    {
        return $this->errors;
    }
    /**
     * Insert or update the record.
     *
     * @param  Array $data
     * @return Response
     */
		public function insertOrUpdate($data) {
			$guests = new Guests();
			$incomes = new Incomes();
			$user_id = \Auth::User()->id;
			
			/*echo "<pre>";
			print_r($data);die;*/

			unset($data['_token'], $data['_method'], $data['incharge_set']);
			$rent_data = $data['rent'];
			
			if(isset($rent_data['id'])) {
				$rent_id = $rent_data['id'];

				$guest_data = $data['guest'];

				$guest_id = $guest_data['guest_id'];

				$advance = $rent_data['advance'];

				unset($rent_data['id'], $guest_data['guest_id']);

				\Session::flash('message', trans('message.rent_update_success'));

				$rent_data['checkin_date'] = date('Y-m-d', strtotime(str_replace('/', '-', $rent_data['checkin_date'])));
				if($rent_data['checkout_date'] != '') {
					$rent_data['checkout_date'] = date('Y-m-d', strtotime(str_replace('/', '-', $rent_data['checkout_date'])));
				} else {
					$rent_data['checkout_date'] = null;
				}

				$this->where('id', $rent_id)->update($rent_data);

				$guests->where('id', $guest_id)->update($guest_data);

				$incomes_data = 
					array(
							"amount" => $advance,
							"user_id" => $user_id,
						);

				$incomes->where('rent_id', $rent_id)->update($incomes_data);

			} else {
				$is_valid_incharge = 0;
				$rent_id_list = [];
				foreach ($data['guest'] as $key => $value) {
					$guest_data = $value;
					$advance = $guest_data['advance'];
					$rent_amount = $guest_data['rent_amount'];

					$rent_id = 0;
					if(isset($guest_data['rent_id'])) {
						$rent_id = $guest_data['rent_id'];
						unset($guest_data['rent_id']);
					}

					/*$rent_data['is_incharge'] = 0;
					if(isset($guest_data['is_incharge'])) {
						$rent_data['is_incharge'] = 1;
					}*/
					$incharge = 0;
					if(isset($guest_data['is_incharge'])) {
						$incharge = 1;	
					}
					
					
					unset($guest_data['advance'], $guest_data['is_incharge'], $guest_data['rent_amount'], $guest_data['incharge_set']);

					if(isset($value['guest_id'])) {
						$guest_id = $guest_data['guest_id'];
						unset($guest_data['guest_id']);
						$guests->where('id', $guest_id)->update($guest_data);
					} else {
						$guest_id = $guests->insertGetId($guest_data);
					}

					if(!$rent_id) {

						$rent_data['guest_id'] = $guest_id;
						$rent_data['user_id'] = $user_id;
						$rent_data['rent_amount'] = $rent_amount;
						$rent_data['checkin_date'] = date('Y-m-d', strtotime(str_replace('/', '-', $rent_data['checkin_date'])));
						if($rent_data['checkout_date'] != '') {
							$rent_data['checkout_date'] = date('Y-m-d', strtotime(str_replace('/', '-', $rent_data['checkout_date'])));
						} else {
							$rent_data['checkout_date'] = null;
						}

						$rent_id = $this->insertGetId($rent_data);
						$rent_id_list[] = $rent_id;
						if($incharge) {
							$is_valid_incharge = $rent_id;
						}

						if($advance < 0) {
							$advance = 0;
						}
						$incomes_data = 
							array(
									"rent_id" => $rent_id,
									"amount" => $advance,
									"income_type" => \Config::get('constants.ADVANCE'),
									"user_id" => $user_id,
									"date_of_income" => date('Y-m-d')
								);
						$incomes->insert($incomes_data);
						
					} else {
						if($incharge) {
							$is_valid_incharge = $rent_id;
						}
						$rent_id_list[] = $rent_id;
						$rent_data_array = [ "user_id" => $user_id, "rent_amount" => $rent_amount ];
						
						$this->where('id', $rent_id)->update($rent_data_array);

						$incomes_result = $incomes->where([ 'rent_id' => $rent_id, 'income_type' => \Config::get('constants.ADVANCE')])->first();

						if($advance < 0) {
							$advance = 0;
						}

						if($incomes_result) {
							$incomes_data = 
							array(
									"amount" => $advance,
									"user_id" => $user_id,
								);
							$incomes->where('id', $incomes_result->id)->update($incomes_data);
						} else {

							$incomes_data = 
							array(
									"rent_id" => $rent_id,
									"amount" => $advance,
									"income_type" => \Config::get('constants.ADVANCE'),
									"user_id" => $user_id,
									"date_of_income" => date('Y-m-d')
								);
							$incomes->insert($incomes_data);

						}
						
					}
					
				}

				/*echo $is_valid_incharge;
				print_r($rent_id_list);die;*/

				if($is_valid_incharge) {
					$this->whereIn('id', $rent_id_list)->update([ 'is_incharge' => 0, 'incharge_set' => 1 ]);
					$this->where('id', $is_valid_incharge)->update([ 'is_incharge' => 1 ]);
				} else {
					$this->whereIn('id', $rent_id_list)->update([ 'is_incharge' => 1, 'incharge_set' => 0 ]);
				}
				
				\Session::flash('message', trans('message.rent_create_success'));
				$last_id = $this->id;
			}
			return true;
		}
		/**
     * Always format the date while the checkin date when we retrieve it
     */
    public function getCheckinDateAttribute($value) {
        return $value ? date('d/m/Y', strtotime($value)) : '';
    }
    /**
     * Always format the date while the checkin date when we insert it
     */
    public function setCheckinDateAttribute($value) {
    	$this->attributes['checkin_date'] = date('Y-m-d', strtotime(str_replace('/', '-', $value)));
    }
    /**
     * Always format the date while the checkout date when we retrieve it
     */
    public function getCheckoutDateAttribute($value) {
        return $value ? date('d/m/Y', strtotime($value)) : '';
    }
    /**
     * Always format the date while the checkout date when we insert it
     */
    public function setCheckoutDateAttribute($value) {
    	$this->attributes['checkout_date'] = $value ? date('Y-m-d', strtotime(str_replace('/', '-', $value))) : null;
    }

    /**
     * Get the rent details with guest information
     * @params
     * 		$id - Rent id
     * @return
     *		Array
     */
    public function findWithGuest ($id) {
    	return $this->select('rents.id', 'rents.room_id', 'rents.guest_id', 'rents.checkin_date', 'rents.checkout_date', 'guests.name', 'guests.city', 'guests.state', 'guests.country', 'guests.zip', 'guests.email', 'guests.address', 'guests.mobile_no', 'incomes.amount as advance')
                    ->join('guests', 'guests.id', '=', 'rents.guest_id', 'left')
                    ->join('incomes', 'incomes.rent_id', '=', 'rents.id', 'left')
                    ->where(array('rents.id' => $id))
                    ->first();
    }

  //Populate the bill record for the current month.
	public function createRentsDetailsForRooms ($month, $year) {
		$income_model = new RentIncomes();
		//$month = $month - 1;
		$date = $year.'-'.$month.'-26';

		$last_day = date('t', strtotime(date($year.'-'.$month.'-01')));

    /*if($month == date('m') && $year == date('Y')) {
      $last_day = date('d');
    }*/

    $last_date = $year.'-'.$month.'-'.$last_day;

    $first_date = $year.'-'.$month.'-01';

		//echo $date;die;
		$rents_query = $this
										->select('rents.id as rent_id', \DB::raw("'$date' as date_of_rent"), \DB::raw('trim(IF(tbl_rts.total_amount is not null, tbl_rts.total_amount, 0) + tbl_rents.rent_amount)+0 as rent_amount'), \DB::raw("(IF(tbl_rts.total_count > 1, tbl_eb.eb_amount, tbl_eb.single_eb_amount)) as electricity_amount"), \DB::raw('IF(tbl_rts.total_count is null, 1, tbl_rts.total_count + 1) as no_of_person'),
											//,'ris.type_rent',
											//'rent_incomes.id',
											\DB::raw('CASE WHEN tbl_rents.incharge_set = "1" THEN "group" ELSE "individual" END as type_of_rent')
											)

										->leftjoin(\DB::raw("(SELECT 
											sum(
												IF( IF(checkout_date is null, 
				                IF(MONTH(checkin_date) = '".$month."' AND YEAR(checkin_date) = '".$year."', 
				                    DATEDIFF(DATE('".$last_date."'), checkin_date), DATEDIFF(DATE('".$last_date."'), DATE('".$first_date."'))),
				                IF(MONTH(checkout_date) = '".$month."' AND YEAR(checkout_date) = '".$year."', 
				                    IF(MONTH(checkin_date) = '".$month."' AND YEAR(checkin_date) = '".$year."', 
				                        DATEDIFF(checkout_date, checkin_date), DATEDIFF(checkout_date, DATE('".$first_date."'))),

				                    IF(MONTH(checkin_date) = '".$month."' AND YEAR(checkin_date) = '".$year."', 
				                      DATEDIFF(DATE('".$last_date."'), checkin_date),
				                      DATEDIFF(DATE('".$last_date."'), DATE('".$first_date."'))))) + 1 > 15, rent_amount, rent_amount / 2) 

												) as total_amount,

											 count(id) as total_count, room_id from tbl_rents where (is_active = 1 and is_incharge=0) and (checkout_date is null and $month >= MONTH(checkin_date) and $year >= YEAR(checkin_date)) or (checkout_date is not null and $month <= MONTH(checkout_date) and $year <= YEAR(checkout_date) and $month >= MONTH(checkin_date) and $year >= YEAR(checkin_date)) group by room_id) tbl_rts"), "rts.room_id", "=", "rents.room_id")

										->leftjoin('rent_incomes', function($join) use($month, $year) {
											$join->on(\DB::raw('MONTH(tbl_rent_incomes.date_of_rent)'), '=', \DB::raw($month))
													->on(\DB::raw('YEAR(tbl_rent_incomes.date_of_rent)'), '=', \DB::raw($year))
													//->on(\DB::raw('tbl_rent_incomes.type_of_rent'), '=', \DB::raw("'individual'"))
													->on('rent_incomes.rent_id', '=', 'rents.id');
										})

										->leftjoin(\DB::raw("(SELECT tbl_rent_incomes.type_of_rent, tbl_rents.room_id from tbl_rent_incomes left join tbl_rents on tbl_rents.id = tbl_rent_incomes.rent_id where MONTH(tbl_rent_incomes.date_of_rent) = $month and YEAR(tbl_rent_incomes.date_of_rent) = $year group by tbl_rents.room_id) tbl_ris"), 'ris.room_id', '=', 'rents.room_id')

										->leftJoin(\DB::raw("(select eb.room_id, count(rents.id) as total_person, eb.amount as eb_amount, ROUND(eb.amount / IF(count(rents.id) > 0, count(rents.id), 1)) as single_eb_amount  from tbl_electricity_bills eb left join tbl_rents rents on rents.room_id = eb.room_id and (rents.is_active = 1) and (rents.checkout_date is null and $month >= MONTH(rents.checkin_date) and $year >= YEAR(rents.checkin_date)) or (rents.checkout_date is not null and $month <= MONTH(rents.checkout_date) and $year <= YEAR(rents.checkout_date) and $month >= MONTH(rents.checkin_date) and $year >= YEAR(rents.checkin_date)) where MONTH(eb.billing_month_year) = $month AND YEAR(eb.billing_month_year) = $year group by rents.room_id) tbl_eb"), "eb.room_id", "=", "rents.room_id")

										->whereRaw("(tbl_ris.type_of_rent IS NULL OR tbl_ris.type_of_rent LIKE 'individual')")

										->whereRaw("(IF(tbl_ris.type_of_rent is not null, tbl_ris.type_of_rent LIKE 'individual' AND tbl_rents.incharge_set = '0', 1))")

										->where([ 'rents.is_active' => 1, 'rents.is_incharge' => 1, 'rent_incomes.id' => null ])
										//->whereNotNull('rents.checkin_date')
										->whereRaw('(tbl_rents.checkout_date is null and ? >= MONTH(tbl_rents.checkin_date) and ? >= YEAR(tbl_rents.checkin_date)) or (tbl_rents.checkout_date is not null and ? <= MONTH(tbl_rents.checkout_date) and ? <= YEAR(tbl_rents.checkout_date) and ? >= MONTH(tbl_rents.checkin_date) and ? >= YEAR(tbl_rents.checkin_date))', [ $month, $year, $month, $year, $month, $year ]);

								//echo $rents_query->get();die;

		$bindings = $rents_query->getBindings();
		$insertQuery = 'INSERT into tbl_rent_incomes (rent_id, date_of_rent, amount, electricity_amount, no_of_person, type_of_rent) '
                . $rents_query->toSql();
    \DB::insert($insertQuery, $bindings);
		//die;

		return [];
	}

	/**
     * Update the billing details using column key and column value.
     *
     * @param  Array
     * @return Response
    */
    public function updateByKey ($data)
    {
    		$id = $data['id'];
    		$income_model = new Incomes();
    		$guest_model = new Guests();
    		$rent_model = new Rents();
    		$rent_incomes_model = new RentIncomes();
    		$form_data = [];

    		$rent_columns = [ "checkin_date" ];

    		$rent_income_columns = [ "amount", "electricity_amount" ];

    		$columns = [ "email", "name", "mobile_no", "city",];

        $form_data[$data['column_key']] = $data['column_value'];

        $model = "income";
        $key = $data['column_key'];
        $value = $data['column_value'];

        if(in_array($key, $rent_columns)) {
        	$model = 'rent';
        	$id = $data['rent_id'];
        }
        else if(in_array($key, $columns)) {
        	$model = 'guest';
        	$id = $data['guest_id'];
        }
        else if(in_array($key, $rent_income_columns)) {
        	$model = 'rent_incomes';
        	$id = $data['id'];
        }

    		switch ($model) {
    			case 'guest':
    				$result = $guest_model->where('id', $id)->update($form_data);		
    				break;

    			case 'rent':
    				$model = $rent_model->find($id);
    				$model->{$key} = $value;
    				$model->save();

    				break;
    			default:
    				$result = $rent_incomes_model->where('id', $id)->update($form_data);
    				break;
    		}

        return [ 'success' => true ];
        
    }

    /**
     * Update the billing details using column key and column value.
     *
     * @param  Array
     * @return Response
    */
    public function deleteByIds ($ids)
    {
    		$income_model = new Income();
    		$update_data = [ 'is_active' => 0 ];
        $result = $income_model->whereIn('id', $ids)->update($update_data);

        return [ 'success' => true ];
    }

    /**
     * Update the billing details using column key and column value.
     *
     * @param  Array
     * @return Response
    */
    public function updateByRentKey ($data)
    {
    		$id = $data['id'];
    		$income_model = new Incomes();
    		$guest_model = new Guests();

    		$form_data = [];

    		$columns = [ "email", "name", "mobile_no", "city", "state", "country", "zip" ];
    		$date_key = [ "checkin_date", "checkout_date" ];
    		$income_columns = [ "advance" ];

        $form_data[$data['column_key']] = $data['column_value'];

        $model = "rent";
        $key = $data['column_key'];
        $value = $data['column_value'];

        if(in_array($key, $columns)) {
        	$model = 'guest';
        	$id = $data['guest_id'];
        } else if(in_array($key, $income_columns)) {
        	$model = 'income';
        	$id = $data['income_id'];

        	if($key == 'advance') {
        		$form_data = [];

        		$form_data['amount'] = $data['column_value'];
        	}
        }

        if ($key == 'is_incharge' && $value) {
        	$form_data['incharge_set'] = 1;
        } else if ($key == 'is_incharge' && !$value) {
        	return;
        }

        if (in_array($key, $date_key)) {
        	$form_data[$key] = date('Y-m-d', strtotime(str_replace("/", "-", $value)));
        }
        //echo $value;die;

    		switch ($model) {
    			case 'guest':
    				$result = $guest_model->where('id', $id)->update($form_data);		
    				break;

    			case 'income':
    				$result = $income_model->where('id', $id)->update($form_data);
    				break;
    			
    			default:
    				/*$rent_model = $this->find($id);
    				$rent_model->{$data['column_key']} = $data['column_value'];
    				$rent_model->save();*/
    				$result = $this->where('id', $id)->update($form_data);
    				break;
    		}

        return [ 'success' => true ];
        
    }

    /**
     * Create the new rent by room id.
     *
     * @param  ids
     * @return Response
    */
    public function updateIsInchargeByRoomId ($data)
    {
    	$rents = $this->where('id', $data['id'])->first();
    	$room_ids = $this->where([ 'room_id' => $rents->room_id, 'checkout_date' => null ])->pluck('id')->toArray();
    	$form_data = array('incharge_set' => 0, 'is_incharge' => 1);
    	if ($data['column_value']) {
    		$form_data['incharge_set'] = 1;
    		$form_data['is_incharge'] = 0;
    	}
    	$this->whereIn('id', $room_ids)->update($form_data);
    	return true;
    }

    /**
     * Create the new rent by room id.
     *
     * @param  ids
     * @return Response
    */
    public function addNewRentByRoom ($room_id)
    {
    		$income_model = new Incomes();

        $guests = new Guests();

        $guest_id = $guests->insertGetId([]);

        $user_id = \Auth::User()->id;

        $rent_data = [ "room_id" => $room_id, "guest_id" => $guest_id, "checkin_date" => null, "checkout_date" => null, "user_id" => $user_id, "incharge_set" => 0 ];

        $rent_datails = $this->where([ 'room_id' => $room_id, 'checkout_date' => null ])->first();
        if ($rent_datails && $rent_datails->incharge_set == 1) {
        	$rent_data['incharge_set'] = 1;
        }

        $id = $this->insertGetId($rent_data);

        $incomes_data = 
					array(
							"rent_id" => $id,
							"amount" => 0,
							"income_type" => \Config::get('constants.ADVANCE'),
							"user_id" => $user_id,
							"date_of_income" => date('Y-m-d')
						);

				$income_model->insert($incomes_data);

        return [ 'success' => true ];
        
    }

    /**
     * Create the new rent by room id and guest.
     *
     * @param  ids
     * @return Response
    */
    public function addNewRentByRoomAndGuest ($room_id, $guest_id)
    {
    		$income_model = new Incomes();

        $guests = new Guests();

        //$guest_id = $guests->insertGetId([]);

        $user_id = \Auth::User()->id;

        $rent_data = [ "room_id" => $room_id, "guest_id" => $guest_id, "checkin_date" => null, "checkout_date" => null, "user_id" => $user_id ];

        $rent_datails = $this->where([ 'room_id' => $room_id, 'checkout_date' => null ])->first();
        if ($rent_datails && $rent_datails->incharge_set == 1) {
        	$rent_data['incharge_set'] = 1;
        }
        $id = $this->insertGetId($rent_data);

        $incomes_data = 
					array(
							"rent_id" => $id,
							"amount" => 0,
							"income_type" => \Config::get('constants.ADVANCE'),
							"user_id" => $user_id,
							"date_of_income" => date('Y-m-d')
						);

				$income_model->insert($incomes_data);

        return [ 'success' => true ];
        
    }

    /**
     * Check if the guest is already exists or not.
     *
     * @param  $guest_id
     * @return Response
    */
    public function checkValidGuest ($guest_id)
    {
    	$rent_count = $this->select('rents.id')
    								->where([ 'is_active' => 1, 'checkout_date' => null, 'guest_id' => $guest_id ])
    								->count();

    	if($rent_count > 0) {
    		return false;
    	}

    	return true;
    }

    /**
     * Remove rents using ids.
     *
     * @param  $ids
     * @return Response
    */
    public function removeRentsByIds ($ids)
    {

    	$this->whereIn('id', $ids)->update([ "is_active" => 0 ]);
    	return true;

    }
}

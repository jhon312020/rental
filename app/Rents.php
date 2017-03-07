<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\Guests;

use App\Incomes;

class Rents extends Model
{
    private $post = [];
    private $rules = 
			[
				"rent.room_id" => "required",
				"rent.advance" => "required|numeric",
				"rent.checkin_date" => "required|date_format:d/m/Y",
				"rent.checkout_date" => "date_format:d/m/Y",
			];
		private $attribute_names = 
			[
				"rent.room_id" => "room no",
				"rent.advance" => "advance",
				"rent.checkin_date" => "Checkin date",
				"rent.checkout_date" => "Checkout date",
			];
	/**
   * Validation all necessary fields.
   *
   * @param  Array  $data
   * @return Response Bool
  */
	public function validate($data)
  {
  		$unique_value = array('email', 'mobile_no');
  		$guest_rules = array(
	  			"name" => 'required',
					"email" => 'required|email|unique:guests,email',
					"mobile_no" => 'required|digits:10|unique:guests,mobile_no',
					"city" => 'required',
					"state" => 'required',
					"country" => 'required',
					"address" => 'required',
					"zip" => 'required|numeric'
				);

  		/* Form the rules for data validation */
  		foreach($data['guest'] as $key => $array_data) {
  			foreach($array_data as $name => $value) {
  				$id = $array_data['id'];
  				$input_name = 'guest.'. $key .'.'.$name;
  				if(isset($guest_rules[$name])) {
  					$this->attribute_names[$input_name] = str_replace('_', ' ', $name);
  				}
  				if(in_array($name, $unique_value)) {
  					if($id) {
  						$this->rules[$input_name] = $guest_rules[$name].','.$id.',id,is_active,1';
  					} else {
  						$this->rules[$input_name] = $guest_rules[$name].',NULL,id,is_active,1';
  					}
  				} else {
  					if(isset($guest_rules[$name])) {
  						$this->rules['guest.'. $key .'.'.$name] = $guest_rules[$name];
  					}
  				}
  			}
  		}
	      // make a new validator object
			$v = \Validator::make($data, $this->rules);

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
			
			unset($data['_token'], $data['_method']);
			$rent_data = $data['rent'];

			$advance = $rent_data['advance'];
			if(isset($rent_data['id'])) {
				$id = $data['id'];
				unset($data['id']);
				\Session::flash('message', trans('message.rent_update_success'));

				$this->where('id', $id)->update($rent_data);
				$last_id = $id;
			} else {
				foreach ($data['guest'] as $key => $value) {
					if($value['id']) {
						$guests->where('id', $id)->update($value);
						$guest_id = $value['id'];
					} else {
						$guest_id = $guests->insertGetId($value);
					}
					$this->guest_id = $guest_id;
					$this->user_id = $user_id;

					foreach ($rent_data as $rent_key => $rent_value) {
						$this->{$rent_key} = $rent_value;
					}
					$this->save();
					$rent_id = $this->id;

					if($advance > 0) {
						$incomes_data = 
							array(
									"rent_id" => $rent_id,
									"amount" => $rent_data['advance'],
									"income_type" => \Config::get('constants.ADVANCE'),
									"user_id" => $user_id,
									"date_of_income" => date('Y-m-d')
								);
						$incomes->insert($incomes_data);
					}
				}
				
				\Session::flash('message', trans('message.rent_create_success'));
				$last_id = $this->id;
			}
			return $last_id;
		}
		/**
     * Always format the date while the checkin date when we retrieve it
     */
    public function getCheckinDateAttribute($value) {
        return date('d/m/Y', strtotime($value));
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
}

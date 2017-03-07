<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Contracts\Validation\Validator;
use DB;
use Auth;

class Guests extends Model
{
		private $post = [];
    private $rules = 
			[
				"name" => 'required',
				"email" => 'required|email|unique:guests,email',
				"mobile_no" => 'required|digits:10|unique:guests,mobile_no',
				"city" => 'required',
				"state" => 'required',
				"country" => 'required',
				"address" => 'required',
				"zip" => 'required|numeric',
			];
	/**
   * Validation all necessary fields.
   *
   * @param  Array  $data
   * @return Response Bool
  */
	public function validate($data)
  {
  		if(isset($data['id'])) {
				$this->rules['email'] = $this->rules['email'].','.$data['id'].',id,is_active,1';
				$this->rules['email'] = $this->rules['email'].','.$data['id'].',id,is_active,1';
			} else {
				$this->rules['mobile_no'] = $this->rules['mobile_no'].',NULL,id,is_active,1';
				$this->rules['mobile_no'] = $this->rules['mobile_no'].',NULL,id,is_active,1';
			}
	    // make a new validator object
			$v = \Validator::make($data, $this->rules);
		
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
			unset($data['_token'], $data['_method']);
			if(isset($data['id'])) {
				$id = $data['id'];
				unset($data['id']);
				\Session::flash('message', trans('message.guests_update_success'));
				$this->where('id', $id)->update($data);
				$last_id = $id;
			} else {
				$this->insert($data);
				\Session::flash('message', trans('message.guests_create_success'));
				$last_id = $this->id;
			}
			return $last_id;
		}
}
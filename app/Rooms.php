<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Rooms extends Model
{
    private $post = [];
    private $rules = 
			[
				"room_name" => 'required',
				"room_no" => 'required|numeric|unique:rooms,room_no',
				"max_persons_allowed" => 'required|numeric',
				"rent_amount_person" => 'required|numeric',
				"total_rent_amount" => 'required|numeric',
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
				$this->rules['room_no'] = $this->rules['room_no'].','.$data['id'].',id,is_active,1';
			} else {
				$this->rules['room_no'] = $this->rules['room_no'].',NULL,id,is_active,1';
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
				\Session::flash('message', trans('message.room_update_success'));
				$this->where('id', $id)->update($data);
				$last_id = $id;
			} else {
				$this->insert($data);
				\Session::flash('message', trans('message.room_create_success'));
				$last_id = $this->id;
			}
			return $last_id;
		}
}

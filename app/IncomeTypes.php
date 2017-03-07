<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class IncomeTypes extends Model
{
    private $post = [];
    private $rules = 
			[
				"type_of_income" => 'required|unique:income_types,type_of_income',
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
				$this->rules['type_of_income'] = $this->rules['type_of_income'].','.$data['id'].',id,is_active,1';
			} else {
				$this->rules['type_of_income'] = $this->rules['type_of_income'].',NULL,id,is_active,1';
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
				\Session::flash('message', trans('message.income_type_create_success'));
				$this->where('id', $id)->update($data);
				$last_id = $id;
			} else {
				$this->insert($data);
				\Session::flash('message', trans('message.income_type_update_success'));
				$last_id = $this->id;
			}
			return $last_id;
		}
}

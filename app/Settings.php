<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Settings extends Model
{
    private $post = [];
    private $rules = 
			[
				'title' => 'required',
				'electricity_bill_units' => 'required|numeric',
				'admin_email' => 'required|email'
			];
	/**
   * Validation all necessary fields.
   *
   * @param  Array  $data
   * @return Response Bool
  */
	public function validate($data)
  {
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
		public function insertOrUpdate($request) {
			$data = $request->all();
			unset($data['_token']);
			foreach ($data as $key => $value) {
				$this->where('setting_key', $key)->update(['setting_value' => $value]);
			}
			
			\Session::flash('message', trans('message.setting_update_success'));
			
			return true;
		}
}

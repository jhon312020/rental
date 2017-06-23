<?php

namespace App;

use Auth;

use Illuminate\Database\Eloquent\Model;

class Expenses extends Model
{
    private $post = [];
    private $rules = 
			[
				"expense_type" => 'required',
				"date_of_expense" => 'required|date_format:d/m/Y',
				"amount" => 'required|numeric',
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
		public function insertOrUpdate($data) {
			unset($data['_token'], $data['_method']);
			$data['user_id'] = Auth::user()->id;
			if(isset($data['id'])) {
				$id = $data['id'];
				unset($data['id']);
				\Session::flash('message', trans('message.expense_update_success'));
				if (isset($data['date_of_expense']))
					$data['date_of_expense'] = date('Y-m-d', strtotime(str_replace("/", "-", $data['date_of_expense'])));
				$this->where('id', $id)->update($data);
				$last_id = $id;
			} else {
				
				foreach ($data as $key => $value) {
					$this->{$key} = $value;
				}
				$this->save();
				\Session::flash('message', trans('message.expense_create_success'));
				$last_id = $this->id;
			}
			return $last_id;
		}

		/**
     * Always format the date while the date of income when we retrieve it
     */
    public function getDateOfExpenseAttribute($value) {
        return date('d/m/Y', strtotime($value));
    }
    /**
     * Always format the date while the date of expense date when we insert it
     */
    public function setDateOfExpenseAttribute($value) {
    	$this->attributes['date_of_expense'] = $value ? date('Y-m-d', strtotime(str_replace('/', '-', $value))) : null;
    }
}

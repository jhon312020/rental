<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Messages extends Model
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
		public function insertOrUpdate($data, $month, $year) {
			$rent_income_ids = array_column($data, 'rent_income_id');
			$this->whereIn('rent_income_id', $rent_income_ids)
					->whereRaw('Month(tbl_messages.date_of_message) = ? AND Year(tbl_messages.date_of_message) = ?', [ $month, $year ])->delete();

			$this->insert($data);
			return true;
		}
		/**
     * Remove message if rent income id is numm
     *
     * @param  Array $data
     * @return Response
     */
		public function remove($month, $year) {
			$this->where('rent_income_id', 0)
					->whereRaw('Month(tbl_messages.date_of_message) = ? AND Year(tbl_messages.date_of_message) = ?', [ $month, $year ])->delete();
			return true;
		}
}

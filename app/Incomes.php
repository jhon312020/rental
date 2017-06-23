<?php

namespace App;

use Auth;

use App\RentIncomes;

use Illuminate\Database\Eloquent\Model;

class Incomes extends Model
{
    private $post = [];
    private $rules = 
			[
				"income_type" => 'required',
				"date_of_income" => 'required|date_format:d/m/Y',
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
   * Ajax validation all necessary fields.
   *
   * @param  Array  $data
   * @return Response Bool
  */
    public function ajaxValidate($data)
  {
          // make a new validator object
            $v = \Validator::make($data, $this->rules);
        
            $this->post = $data;
            // check for failure
            if ($v->fails())
            {
                    // set errors and return false
                    $this->errors = $v->errors()->all();;
                    return false;
            }
            // validation pass
            return true;
    }
    /**
    * Rent income validation for amount.
    *
    * @param  Array  $data
    * @return Response Bool
    */
    public function rentValidate($data)
    {
        $rent_incomes = new RentIncomes();
        $error = [ "error" => false ];
        $income = $this->select(\DB::raw("SUM(amount) as amount"))->where([ "rent_id" => $data['rent_id'], 'income_type' => \DB::raw(\Config::get('constants.RENT')), 'is_active' => 1 ])->first();
        $rent_income = $rent_incomes->select(\DB::raw("SUM(amount + electricity_amount) as amount"))->where([ "rent_id" => $data['rent_id'], 'is_active' => 1 ])->first();
        if ($rent_income->amount < $income->amount + $data['amount']) {
            $error['error'] = true;
            $error['msg'] = "The amount shoul less than or equal to ".($rent_income->amount - $income->amount);
            return $error;
        }
        // validation pass
        return $error;
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
				\Session::flash('message', trans('message.income_update_success'));
				if (isset($data['date_of_income']))
					$data['date_of_income'] = date('Y-m-d', strtotime(str_replace("/", "-", $data['date_of_income'])));
				$this->where('id', $id)->update($data);
				$last_id = $id;
			} else {

				foreach ($data as $key => $value) {
					$this->{$key} = $value;
				}
				$this->save();

				\Session::flash('message', trans('message.income_create_success'));
				$last_id = $this->id;
			}
			return $last_id;
		}

		/**
     * Always format the date while the date of income when we retrieve it
     */
    public function getDateOfIncomeAttribute($value) {
        return date('d/m/Y', strtotime($value));
    }
    /**
     * Always format the date while the date of income date when we insert it
     */
    public function setDateOfIncomeAttribute($value) {
    	$this->attributes['date_of_income'] = $value ? date('Y-m-d', strtotime(str_replace('/', '-', $value))) : null;
    }

    /**
     * Delete incomes by ids
     * @params
     * 		$ids - income ids Array
     * @return 
     *		
     */
    public function deleteByIds ($ids) {

    	$this->whereIn('id', $ids)->update([ 'is_active' => 0 ]);

    	return [ 'success' => true ];
    }

    /**
     * Move the deleted incomes to active
     * @params
     *      $ids - income ids Array
     * @return 
     *      
     */
    public function updateActiveByIds ($ids) {

        $this->whereIn('id', $ids)->update([ 'is_active' => 1 ]);

        return [ 'success' => true ];
    }
}

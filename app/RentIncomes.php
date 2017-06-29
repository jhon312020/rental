<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RentIncomes extends Model
{
    private $old_rent_rules = 
			[
				"old_rent_amount" => "required|numeric",
			];
		private $post = [];
		
		public function oldRentValidate ($data) {

			$this->post = $data;
			
			// make a new validator object
			$v = \Validator::make($data, $this->old_rent_rules);
			//print_r($v->fails());die;
			$v->after(function($v) use($data) {
				//echo $data['rent']['checkin_date'];die;
				if (isset($data['rent_income_id'])) {
					$amount_validate = $this->amountValidate($data['old_rent_amount'], $data['rent_id'], $data['rent_income_id']);
					if(!$amount_validate['valid']) {
						$v->errors()->add('old_rent_amount', $amount_validate['msg']);
					}
				}
			});
			
			// check for failure
			if ($v->fails())
			{
					// set errors and return false
					$this->errors = $v->errors();
					return false;
			}
			// validation pass
			return true;
		}

		public function amountValidate ($amount, $rent_id, $rent_income_id) {
			$income_model = new Incomes();
			$rent_incomes = new RentIncomes();
			$income = $income_model->select(\DB::raw("SUM(amount) as amount"))->where([ "rent_id" => $rent_id, 'income_type' => \DB::raw(\Config::get('constants.RENT')), 'is_active' => 1 ])->first();
			
			$rent_incomes_result = $rent_incomes->select(\DB::raw("SUM(amount + electricity_amount) as amount"))->where("rent_id", $rent_id)->where('id', '<>', $rent_income_id)->first();

			$rent_incomes_amount = $rent_incomes->select('amount', 'electricity_amount')->where("id", $rent_income_id)->first();
			$remain_amount = $rent_incomes_amount->amount;
			
			if ($rent_incomes_result->amount + $amount + $remain_amount < $income->amount) {
				return [ "valid" => false, "msg" => "Value should be greater than or equal to ".($income->amount - $remain_amount - $rent_incomes_result->amount) ];
			 }

			 return [ "valid" => true ];
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

		public function updateOldRent ($data) {
			$form_data = 
				[
					"income_type" => \Config::get('constants.OLD_RENT'),
					"amount" => $data['old_rent_amount'],
					"rent_id" => $data['rent_id']
				];

			if (isset($data['rent_income_id'])) {
				$this->where('id', $data['rent_income_id'])->update([ "amount" => $data['old_rent_amount'] ]);
			} else {
				$this->insert($form_data);
			}
		}
}

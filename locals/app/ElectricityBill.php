<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\Rooms;

use App\Repositories\SettingsRepository;

class ElectricityBill extends Model
{
    //Populate the bill record for the current month.
	public function createBillingDetailsForRooms ($month, $year) {
		$room_model = new Rooms();

        $rooms_query = $room_model->where(['is_active' => 1]);

		$room_ids = $rooms_query->pluck('id')->toArray();

		$rooms = $rooms_query->get()->toArray();
		
		$date = 'Y-'.$month.'-26';

		$old_bill_data_rooms = 
					$this->whereRaw('MONTH(billing_month_year) = ? and YEAR(billing_month_year) = ?', [$month, $year])
							->pluck('room_id')->toArray();
											
		$bill_data = [];
		
		foreach ($rooms as $room) {
			
			if(!in_array($room['id'], $old_bill_data_rooms)) {
				$bill_data[] = [ "room_id" => $room['id'], 'billing_month_year' => date('Y-m-d', strtotime(date($date))), "units_used" => 0 ];
			}
		}

		$this->insert($bill_data);

		return array_diff($room_ids, $old_bill_data_rooms);
	}

	/**
     * Update the billing details using column key and column value.
     *
     * @param  Array
     * @return Response
    */
    public function updateByKey ($data)
    {

        $form_data = [];
        $key = $data['column_key'];
        $setting_repo = new SettingsRepository();
        $setting = $setting_repo->all()->lists('setting_value', 'setting_key')->toArray();
        $form_data[$data['column_key']] = $data['column_value'];
        if($key == 'units_used') {
            $form_data['amount'] = $data['column_value'] * $setting['electricity_bill_units'];
        }
        $result = $this->where('id', $data['id'])->update($form_data);

        return [ 'success' => true, 'amount' => $form_data['amount'] ];
        
    }

    /**
     * Update the billing details using column key and column value.
     *
     * @param  Array
     * @return Response
    */
    public function deleteByIds ($ids)
    {
    		$update_data = [ 'is_active' => 0 ];
        $result = $this->whereIn('id', $ids)->update($update_data);

        return [ 'success' => true ];
    }

    /**
     * Update the billing details using column key and column value.
     *
     * @param  Array
     * @return Response
    */
    public function updateActiveByIds ($ids)
    {
            $update_data = [ 'is_active' => 1 ];
        $result = $this->whereIn('id', $ids)->update($update_data);

        return [ 'success' => true ];
    }
}

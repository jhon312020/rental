<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Users extends Model
{
    private $post = [];
    private $rules = 
			[
				"username" => 'required',
				"email" => 'required|unique:users,email',
				'avatar' => 'image|mimes:jpeg,png,jpg,gif,svg',
			];
		private $password_rules = 
			[
				"new_password" => 'required',
				'confirm_password' => 'required|same:new_password',
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
			} else {
				$this->rules['email'] = $this->rules['email'].',NULL,id,is_active,1';
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
   * Validation for password
   *
   * @param  Array  $data
   * @return Response Bool
  */
	public function passwordValidate($data)
  {
	      // make a new validator object
			$v = \Validator::make($data, $this->password_rules);
		
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
			$id = \Auth::User()->id;
			//print_r($data);die;
			if($request->avatar) {
				$imageName = 'avatar.'.$request->avatar->getClientOriginalExtension();
				$request->avatar->move(public_path('images/'.$id), $imageName);
        $data['avatar'] = $imageName;
			}
			unset($data['_token'], $data['_method']);

			if(isset($data['password'])) {
				$data['password'] = bcrypt($data['password']);
			}
			if(isset($data['new_password'])) {
				$data['password'] = bcrypt($data['new_password']);
				unset($data['confirm_password'], $data['new_password']);
			}
			$data['id'] = $id;

			if(isset($data['id'])) {
				$id = $data['id'];
				unset($data['id']);
				\Session::flash('message', trans('message.user_update_success'));
				$this->where('id', $id)->update($data);
				$last_id = $id;
			} else {
				$this->insert($data);
				\Session::flash('message', trans('message.user_create_success'));
				$last_id = $this->id;
			}
			return $last_id;
		}
}

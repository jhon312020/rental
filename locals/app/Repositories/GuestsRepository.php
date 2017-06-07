<?php

namespace App\Repositories;

use App\Guests;


class GuestsRepository
{
    /**
     * Get all instance of Guests.
     *
     * @return \Illuminate\Database\Eloquent\Collection|\App\Guests[]
     */
    public function all()
    {
        return Guests::all();
    }

    /**
     * Find an instance of Guests with the given ID.
     *
     * @param  int  $id
     * @return \App\Guests
     */
    public function find($id)
    {
        return Guests::find($id);
    }

    /**
     * Create a new instance of Guests.
     *
     * @param  array  $attributes
     * @return \App\Guests
     */
    public function create(array $attributes = [])
    {
        return Guests::create($attributes);
    }

    /**
     * Update the Guests with the given attributes.
     *
     * @param  int    $id
     * @param  array  $attributes
     * @return bool|int
     */
    public function update($id, array $attributes = [])
    {
        return Guests::find($id)->update($attributes);
    }

    /**
     * Delete an entry with the given ID.
     *
     * @param  int  $id
     * @return bool|null
     * @throws \Exception
     */
    public function delete($id)
    {
        return Guests::find($id)->delete();
    }
	/**
     * Get all active instance of Guests.
     *
     * @return \Illuminate\Database\Eloquent\Collection|\App\Guests[]
     */
    public function allActive()
    {
        return Guests::where(array('is_active' => 1))->get();
    }

    /**
     * Get guest list by type of seach.
     *
     * @return \Illuminate\Database\Eloquent\Collection|\App\Guests[]
     */
    public function getGuestDetailsByType ($data)
    {
      $value = $data['column_value'];
      $search = $data['column_search'];
        return Guests::select(\DB::raw('tbl_guests.'.$search.' as label'), \DB::raw('tbl_guests.'.$search.' as value'), 'guests.id')
                    ->leftjoin('rents', function($join)
                     {
                         $join->on('rents.guest_id', '=', 'guests.id')
                            ->on('rents.is_active', '=', \DB::raw('1'))
                            ->on('rents.checkout_date', 'is', \DB::raw('null'));
                     })
                    ->where(array('guests.is_active' => 1, 'rents.id' => null))
                    ->where('guests.'.$search, 'like', "%$value%")
                    ->whereNotIn('guests.id', explode(',', $data['guest_ids']))
                    ->skip(0)->take(50)
                    ->get();
    }

    /**
     * Get guest list by id.
     *
     * @return \Illuminate\Database\Eloquent\Collection|\App\Guests[]
     */
    public function getGuestDetailsById ($id)
    {
        return Guests::select('guests.id as guest_id', 'guests.name', 'guests.email', 'guests.mobile_no', 'guests.city', 'guests.state', 'guests.country', 'guests.address', 'guests.id')
                  ->where([ 'id' => $id ])
                    ->first();
    }
    /**
     * Get guest All guests
     *
     * @return \Illuminate\Database\Eloquent\Collection|\App\Guests[]
     */
    public function getAllGuests ($data)
    {
      //$columns = $data['columns'];
      $order = $data['order'][0];
      $columns = [ "name", "city", "state", "email", "mobile_no" ];
      $column_name = $columns[$order['column']];

      $sort = $order['dir'];
      $search = $data['search']['value'];
      $query = Guests::select('guests.id as guest_id', 'guests.name', 'guests.email', 'guests.mobile_no', 'guests.city', 'guests.state', 'guests.country', 'guests.address', 'guests.id')
                  ->where([ 'is_active' => 1 ]);
        if (trim($search)) {
          $query->where(function ($query) use($search, $columns) {
            foreach ($columns as $key => $value) {
              $query->orwhere(\DB::raw('lower('.$value.')'), 'like', '%'.$search.'%');
            }
          });
        }
        $count = $query->count();
        $query->skip($data['start'])->take($data['length']);

        $query->orderby($column_name, $sort);

        $result = $query->get()->toArray();
        return [ "count" => $count, "data" => $result ];
    }
    /**
     * Get total number of guests.
     *
     * @return \Illuminate\Database\Eloquent\Collection|\App\Guests[]
     */
    public function getTotalGuests ()
    {
        return Guests::where([ 'is_active' => 1 ])
                    ->count();
    }
}

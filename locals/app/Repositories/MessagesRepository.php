<?php

namespace App\Repositories;

use App\Messages;

use App\RentIncomes;


class MessagesRepository
{
    /**
     * Get all instance of Messages.
     *
     * @return \Illuminate\Database\Eloquent\Collection|\App\Messages[]
     */
    public function all()
    {
        return Messages::all();
    }

    /**
     * Find an instance of Messages with the given ID.
     *
     * @param  int  $id
     * @return \App\Messages
     */
    public function find($id)
    {
        return Messages::find($id);
    }

    /**
     * Create a new instance of Messages.
     *
     * @param  array  $attributes
     * @return \App\Messages
     */
    public function create(array $attributes = [])
    {
        return Messages::create($attributes);
    }

    /**
     * Update the Messages with the given attributes.
     *
     * @param  int    $id
     * @param  array  $attributes
     * @return bool|int
     */
    public function update($id, array $attributes = [])
    {
        return Messages::find($id)->update($attributes);
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
        return Messages::find($id)->delete();
    }
    /**
     * Get all active instance of Messages.
     *
     * @return \Illuminate\Database\Eloquent\Collection|\App\Messages[]
     */
    public function allActive()
    {
        return Messages::where(array('is_active' => 1))
                    ->orderby('id', 'asc')
                    ->get();
    }
    /**
     * Get all active delivered Messages.
     * @params $month string
     * @params $year string
     * @return \Illuminate\Database\Eloquent\Collection|\App\Messages[]
     */
    public function getDeleiverdUsers($month, $year)
    {
        return Messages::select("rooms.room_no", "guests.name", "guests.mobile_no", "messages.message")
                ->leftjoin("rent_incomes", "rent_incomes.id", "=", "messages.rent_income_id")
                ->leftjoin("rents", "rents.id", "=", "rent_incomes.rent_id")
                ->leftjoin("guests", "guests.id", "=", "rents.guest_id")
                ->leftjoin("rooms", "rooms.id", "=", "rents.room_id")
                ->where(array('messages.is_active' => 1, 'messages.delivery_status' => 1))
                ->whereRaw("Month(tbl_messages.date_of_message) = ? and Year(tbl_messages.date_of_message) = ?", [ $month, $year ])
                ->get();
    }

    /**
     * Get all undelivered messages.
     * @params $month string
     * @params $year string
     * @return \Illuminate\Database\Eloquent\Collection|\App\messages[]
     */
    public function getNonDeleiverdUsers ($month, $year)
    {
        return RentIncomes::select("rooms.room_no", "guests.name", "guests.mobile_no", "messages.message", "rent_incomes.id", "messages.error_message")
                ->leftjoin("messages", function ($join) use($month, $year) {
                  $join->on("messages.rent_income_id", "=", "rent_incomes.id");
                })
                ->leftjoin("rents", "rents.id", "=", "rent_incomes.rent_id")
                ->leftjoin("guests", "guests.id", "=", "rents.guest_id")
                ->leftjoin("rooms", "rooms.id", "=", "rents.room_id")
                ->where('rent_incomes.is_active', 1)
                ->whereRaw("Month(tbl_messages.date_of_message) = ? and Year(tbl_messages.date_of_message) = ?", [ $month, $year ])
                 ->orWhere(function ($query) {
                    $query->where('messages.delivery_status', '=', \DB::raw("'0'"))
                      ->whereNull('messages.delivery_status');
                })
                ->get();
    }
}

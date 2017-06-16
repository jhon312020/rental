<?php

namespace App\Repositories;

use App\Reports;


class ReportsRepository
{
    /**
     * Get all instance of Reports.
     *
     * @return \Illuminate\Database\Eloquent\Collection|\App\Reports[]
     */
    public function all()
    {
        return Reports::all();
    }

    /**
     * Find an instance of Reports with the given ID.
     *
     * @param  int  $id
     * @return \App\Reports
     */
    public function find($id)
    {
        return Reports::find($id);
    }

    /**
     * Create a new instance of Reports.
     *
     * @param  array  $attributes
     * @return \App\Reports
     */
    public function create(array $attributes = [])
    {
        return Reports::create($attributes);
    }

    /**
     * Update the Reports with the given attributes.
     *
     * @param  int    $id
     * @param  array  $attributes
     * @return bool|int
     */
    public function update($id, array $attributes = [])
    {
        return Reports::find($id)->update($attributes);
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
        return Reports::find($id)->delete();
    }

}

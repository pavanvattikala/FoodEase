<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TableLocation;
use Illuminate\Http\Request;

class TableLocationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $locations = TableLocation::getCachedTableLocations();
        return view('admin.tableLocation.index', compact('locations'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.tableLocation.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $request->validate([
            'name' => 'required',
        ]);

        $locationName = $request->name;

        TableLocation::create([
            'name' => $locationName,
        ]);

        return to_route('admin.table-location.index')->with('success', 'Table Location created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(TableLocation $tableLocation)
    {
        return view('admin.tableLocation.edit', compact('tableLocation'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, TableLocation $tableLocation)
    {
        $request->validate([
            'name' => 'required',
        ]);

        $locationName = $request->name;
        $tableLocation->update([
            'name' => $locationName,
        ]);

        return to_route('admin.table-location.index')->with('success', 'Table Location updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(TableLocation $tableLocation)
    {
        $tableLocation->delete();


        return to_route('admin.table-location.index')->with('danger', 'Table Location daleted successfully.');
    }
}

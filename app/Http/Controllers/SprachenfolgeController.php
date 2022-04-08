<?php

namespace App\Http\Controllers;

use App\Models\Sprachenfolge;
use App\Http\Requests\StoreSprachenfolgeRequest;
use App\Http\Requests\UpdateSprachenfolgeRequest;

class SprachenfolgeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreSprachenfolgeRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreSprachenfolgeRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Sprachenfolge  $sprachenfolge
     * @return \Illuminate\Http\Response
     */
    public function show(Sprachenfolge $sprachenfolge)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Sprachenfolge  $sprachenfolge
     * @return \Illuminate\Http\Response
     */
    public function edit(Sprachenfolge $sprachenfolge)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateSprachenfolgeRequest  $request
     * @param  \App\Models\Sprachenfolge  $sprachenfolge
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateSprachenfolgeRequest $request, Sprachenfolge $sprachenfolge)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Sprachenfolge  $sprachenfolge
     * @return \Illuminate\Http\Response
     */
    public function destroy(Sprachenfolge $sprachenfolge)
    {
        //
    }
}

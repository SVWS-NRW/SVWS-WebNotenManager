<?php

namespace App\Http\Controllers;

use App\Models\Lernabschnitt;
use App\Http\Requests\StoreLernabschnittRequest;
use App\Http\Requests\UpdateLernabschnittRequest;

class LernabschnittController extends Controller
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
     * @param  \App\Http\Requests\StoreLernabschnittRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreLernabschnittRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Lernabschnitt  $lernabschnitt
     * @return \Illuminate\Http\Response
     */
    public function show(Lernabschnitt $lernabschnitt)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Lernabschnitt  $lernabschnitt
     * @return \Illuminate\Http\Response
     */
    public function edit(Lernabschnitt $lernabschnitt)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateLernabschnittRequest  $request
     * @param  \App\Models\Lernabschnitt  $lernabschnitt
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateLernabschnittRequest $request, Lernabschnitt $lernabschnitt)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Lernabschnitt  $lernabschnitt
     * @return \Illuminate\Http\Response
     */
    public function destroy(Lernabschnitt $lernabschnitt)
    {
        //
    }
}

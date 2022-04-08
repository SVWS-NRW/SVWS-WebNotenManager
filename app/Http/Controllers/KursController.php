<?php

namespace App\Http\Controllers;

use App\Models\Kurs;
use App\Http\Requests\StoreKursRequest;
use App\Http\Requests\UpdateKursRequest;

class KursController extends Controller
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
     * @param  \App\Http\Requests\StoreKursRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreKursRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Kurs  $kurs
     * @return \Illuminate\Http\Response
     */
    public function show(Kurs $kurs)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Kurs  $kurs
     * @return \Illuminate\Http\Response
     */
    public function edit(Kurs $kurs)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateKursRequest  $request
     * @param  \App\Models\Kurs  $kurs
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateKursRequest $request, Kurs $kurs)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Kurs  $kurs
     * @return \Illuminate\Http\Response
     */
    public function destroy(Kurs $kurs)
    {
        //
    }
}

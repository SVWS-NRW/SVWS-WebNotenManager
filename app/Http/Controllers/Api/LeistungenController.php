<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Leistung;
use App\Http\Resources\LeistungCollection;
use App\Http\Resources\LeistungResource;
use Validator;

class LeistungenController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return new \App\Http\Resources\LeistungCollection(
            Leistung::with(["schueler.klasse.klassenlehrer","lerngruppe","note","lerngruppe.fach"])->get()
        );

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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $leistung = Leistung::find($id);

        if (is_null($leistung)) {
            return $this->sendError('Leistung not found.');
        }
        return response()->json([
            'leistung' => $leistung
        ]);
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Leistung $leistung)
    {
        $input = $request->all();

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Leistung $leistung)
    {
        $leistung->delete();
    
        return response()->json([
            'message' => "Leistung deleted successfully!",
        ], 200);
    }
}

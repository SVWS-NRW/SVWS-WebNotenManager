<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\SchuelerBemerkungenRequest;
use App\Models\Bemerkung;
use App\Models\Schueler;
use Illuminate\Database\Eloquent\Model;

class SchuelerBemerkung extends Controller
{
    public function __invoke(SchuelerBemerkungenRequest $request, Schueler $schueler): Model
    {
		return Bemerkung::updateOrCreate(['schueler_id' => $schueler->id], [$request->key => $request->value]);
    }
}

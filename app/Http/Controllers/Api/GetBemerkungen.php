<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Leistung;
use App\Models\LeistungNormalized;
use App\Models\Schueler;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class GetBemerkungen extends Controller
{
    public function get(LeistungNormalized $leistungNormalized, string $group): string|null
    {
        return $leistungNormalized->leistung->schueler->bemerkung->firstOrCreate()->$group;
    }

    public function set(Request $request, LeistungNormalized $leistungNormalized): bool
    {
        return $leistungNormalized->leistung->schueler->bemerkung->update([
            $request->key => $request->value
        ]);
    }

    private function getBemerkung(Leistung $leistung): Model
    {
        return $leistung->schueler->bemerkung()->firstOrCreate();
    }
}

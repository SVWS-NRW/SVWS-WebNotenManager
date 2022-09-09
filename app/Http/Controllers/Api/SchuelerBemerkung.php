<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Schueler;

class SchuelerBemerkung extends Controller
{
    public function __invoke(Schueler $schueler): bool
    {
        return $schueler->update(request()->all());
    }
}

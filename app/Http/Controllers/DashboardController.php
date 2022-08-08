<?php

namespace App\Http\Controllers;

use App\Models\Leistung;
use App\Models\Note;
use Illuminate\Database\Eloquent\Builder;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    public function __invoke(): Response 
    {
        $leistungen = Leistung::query()
            ->when(request()->search, fn (Builder $query, string $search) =>             
                $query
                    ->where('schueler_id', 'like', "%{$search}%")
                    ->orWhere('lerngruppe_id', 'like', "%{$search}%")
            )
            ->when(request()->note, fn (Builder $query, string $search) =>             
                $query->where('note_id', 'like', "%{$search}%")
            )
            ->get();

        return Inertia::render('Dashboard', [
            'leistungen' => $leistungen,
            'filters' => request()->only('search'),
            'noten' => Note::get(['id as index', 'kuerzel as label'])->toArray(),
        ]);
    }
}

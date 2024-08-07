<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\{Lerngruppe, Note, Teilleistung, Klasse, Leistung};
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;
use Symfony\Component\HttpFoundation\Response;

class TeilleistungenController extends Controller
{
    /**
     * Initial page data
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        // Per default take first "Klasse"
        //$selected = Klasse::skip(3)->first();
        $selected = Klasse::first();
        $collection = $this->getTeilleistungen($selected);

        $kurse = Lerngruppe::query()
            ->whereNotNull('kursartKuerzel')
            ->distinct()
            ->pluck('kursartKuerzel', 'id')
            ->unique()
            ->values();

        $klassen = Klasse::query()
            ->distinct()
            ->get()
            ->mapWithKeys(fn (Klasse $item): array => [$item->id => $item->kuerzel]);

        return response()->json([
            'filters' => [
                'selected' => $selected,
                'klassen' => $klassen,
                'kurse' => $kurse,
            ],
            'leistungen' => $this->getLeistungen($collection),
            'columns' => $this->getColumns($collection),
        ]);
    }

    /**
     * Get "Leistungen" for given "Klasse"
     *
     * @param Klasse $klasse
     * @return JsonResponse
     */
    public function getKlasse(Klasse $klasse): JsonResponse
    {
        $collection = $this->getTeilleistungen($klasse);

        return response()->json([
            'leistungen' => $this->getLeistungen($collection),
            'columns' => $this->getColumns($collection)
        ]);
    }

    /**
     * Get "Leistungen" for given "Kurs"
     *
     * @param string $kurs
     * @return JsonResponse
     */
    public function getKurs(string $kurs): JsonResponse
    {
        $collection = $this->getTeilleistungen($kurs);

        return response()->json([
            'leistungen' => $collection,
            'columns' => $this->getColumns($collection)
        ]);
    }

    /**
     * Get "Leistungen" formatted for frontend
     *
     * @param Collection $collection
     * @return array
     */
    private function getLeistungen(Collection $collection): array
    {
        // TODO: Teilnoten
        $leistungen = [];
        foreach ($collection as $leistung) {
            $leistungen[] = [
                'id' => $leistung->id,
                'name' => "{$leistung->schueler->nachname}, {$leistung->schueler->vorname}",
                'fach' => $leistung->lerngruppe->fach->kuerzel,
                'kurs' => $leistung->lerngruppe->kursartKuerzel,
                'note' => $leistung->note?->id,
                'quartal' => null,
            ];
        }

        return $leistungen;
    }

    /**
     * Update the "Note" for given "Teilleistung"
     *
     * @param Teilleistung $teilleistung
     * @param Note $note
     * @return JsonResponse
     */
    public function updateNote(Teilleistung $teilleistung, Note $note): JsonResponse
    {
        try {
            $teilleistung->note()->associate($note);
            $teilleistung->tsNote = now();
            $teilleistung->save();

            return response()->json('success');
        } catch (QueryException $e) {
            return response()->json([
                'error' => 'Database query error',
                'message' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch (Exception $e) {
            return response()->json([
                'error' => 'An error occurred',
                'message' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Get Teilleistungen for "Klasse" or "Kurs" depending on the type of the $item
     *
     * @param Klasse|string $item
     * @return Collection
     */
    private function getTeilleistungen(Klasse|string $item): Collection
    {
        return Leistung::query()
            ->whereHas('teilleistungen')
            ->with([
                'schueler', 'note', 'teilleistungen' => [
                    'note', 'teilleistungsart',
                ],
            ])
            ->whereHas(
                'lerngruppe',
                fn (Builder $query): Builder => $query->when(
                    $item instanceof Klasse,
                    fn (Builder $query): Builder => $query->whereBelongsTo($item),
                    fn (Builder $query): Builder => $query->where('kursartKuerzel', '=', $item),
                )
            )
            ->get();
    }

    /**
     * Get columns to render in frontend
     *
     * @param Collection $collection
     * @return array
     */
    private function getColumns(Collection $collection): array
    {
        $array = [];
        foreach ($collection as $leistung) {
            foreach($leistung->teilleistungen as $teilleistung) {
                $array[] = [
                    'id' => $teilleistung->id,
                    'bezeichnung' => $teilleistung->teilleistungsart->bezeichnung,
                    'sortierung' => $teilleistung->teilleistungsart->sortierung,
                ];
            }
        }

        // Sort first by 'sortierung' then by 'id' ascending
        usort($array, function(array $a, array $b) {
            if ($a['sortierung'] === $b['sortierung']) {
                return $a['id'] <=> $b['id'];
            }

            return $a['sortierung'] <=> $b['sortierung'];
        });

        return $array;
    }
}

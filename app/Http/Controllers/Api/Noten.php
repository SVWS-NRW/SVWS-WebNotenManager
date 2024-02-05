<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Leistung;
use App\Models\Note;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

/**
 * Defining the Noten controller
 */
class Noten extends Controller
{
    /**
     * @param Leistung $leistung
     * @return JsonResponse
     */
    public function __invoke(Leistung $leistung): JsonResponse
	{
        // Check if the class Klasse of the Schueler related to the Leistung is allowed to have editable Noten.

        abort_unless($leistung->schueler->klasse->editable_noten, Response::HTTP_FORBIDDEN);

        // If the requested note is an empty string, call updateNote method without a specific note.
        if (request()->note == '') {
			return $this->updateNote($leistung);
		}

        // Retrieve the Note model based on the requested note's 'kuerzel'.
        $note = Note::query()
            ->where('kuerzel', '=', (string) request()->note)
            ->firstOrFail();

        // Call updateNote method with the retrieved note's ID.
        return $this->updateNote($leistung, $note->id);
    }

    /**
     * @param Leistung $leistung
     * @param string|null $note
     * @return JsonResponse
     */
    private function updateNote(Leistung $leistung, string|null $note = null): JsonResponse
	{
        // Updating the resource with an additional timestamp
        $leistung->update([
			'note_id' => $note,
			'tsNote' => now()->format('Y-m-d H:i:s.u'),
		]);

        // Returning a JSON response with a 204 No Content status.
		return response()->json(Response::HTTP_NO_CONTENT);
	}
}

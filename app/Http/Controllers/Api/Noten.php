<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Leistung;
use App\Models\Note;
use App\Models\Setting;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class Noten extends Controller
{
	public function __invoke(Leistung $leistung): JsonResponse
	{
		abort_unless($leistung->schueler->klasse->editable_noten, Response::HTTP_FORBIDDEN);

		if (request()->note == '') {
			return $this->updateNote($leistung);
		}

			$note = Note::query()
				->where('kuerzel', '=', (string) request()->note)
				->firstOrFail();
//		try {
//		} catch (ModelNotFoundException $e) {
//			return response()->json(
//				[
//					'message' => $e->getMessage(),
//					'note' => $leistung->note?->kuerzel
//				],
//				Response::HTTP_UNPROCESSABLE_ENTITY
//			);
//		}

		return $this->updateNote($leistung, $note->id);
    }

	private function updateNote(Leistung $leistung, string|null $note = null): JsonResponse
	{
		$leistung->update(attributes: [
			'note_id' => $note,
			'tsNote' => now()->format('Y-m-d H:i:s.u'),
		]);

		return response()->json(Response::HTTP_NO_CONTENT);
	}
}

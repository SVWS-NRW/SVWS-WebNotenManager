<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Leistung;
use App\Models\Note;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class Noten extends Controller
{
	public function __invoke(Leistung $leistung): JsonResponse
	{
		try {
			$note = Note::query()
				->where(
					column: 'kuerzel',
					operator: '=',
					value: (string) request()->note
				)
				->firstOrFail();

		} catch (ModelNotFoundException $e) {
			return response()->json(
				data: [
					'message' => $e->getMessage(),
					'note' => $leistung->note?->kuerzel
				],
				status: Response::HTTP_UNPROCESSABLE_ENTITY
			);
		}

		$leistung->update(attributes: [
			'note_id' => $note->id,
			'tsNote' => now()->format(format: 'Y-m-d H:i:s.u'),
		]);

		return response()->json(
			data: ['note' => $note->kuerzel],
			status: Response::HTTP_OK
		);
    }
}

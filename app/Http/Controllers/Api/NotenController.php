<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\NoteResource;
use App\Models\Leistung;
use App\Models\Note;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Symfony\Component\HttpFoundation\Response;

class NotenController extends Controller
{
	public function get(): AnonymousResourceCollection
	{
		return NoteResource::collection(Note::all());
	}

	public function set(Leistung $leistung): JsonResponse
	{
		try {
			$note = Note::query()
				->where(['kuerzel' => (string) request()->note])
				->firstOrFail();

			$leistung->update(['note_id' => $note->id]);

			return response()->json(['note' => $note->kuerzel], Response::HTTP_OK);
		} catch (ModelNotFoundException $e) {
			return response()->json([
				'message' => $e->getMessage(),
				'note' => $leistung->note
			], Response::HTTP_UNPROCESSABLE_ENTITY);
		}
    }
}

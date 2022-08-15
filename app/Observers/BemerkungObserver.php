<?php

namespace App\Observers;

use App\Models\Bemerkung;
use App\Models\Leistung;

class BemerkungObserver
{
	public function created(Bemerkung $bemerkung): void
	{
		$this->updateLeistungNormalized($bemerkung);
	}

    public function updated(Bemerkung $bemerkung): void
    {

		$this->updateLeistungNormalized($bemerkung);
    }

	private function updateLeistungNormalized(Bemerkung $bemerkung): void
	{
		$bemerkung->schueler->leistungen->each(fn (Leistung $leistung) =>
			$leistung->leistungNormalized()->update([
				'asv' => (bool) $bemerkung->asv,
				'aue' => (bool) $bemerkung->aue,
				'zb' => (bool) $bemerkung->zb,
			])
		);
	}
}

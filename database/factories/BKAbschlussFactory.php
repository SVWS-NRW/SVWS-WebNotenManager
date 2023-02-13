<?php

namespace Database\Factories;

use App\Models\BKAbschluss;
use App\Models\Note;
use App\Models\Schueler;
use Illuminate\Database\Eloquent\Factories\Factory;

class BKAbschlussFactory extends Factory
{
    protected $model = BKAbschluss::class;

    public function definition(): array
    {
        return [
            'schueler_id' => Schueler::factory(), 
            'notePraktischePruefung' => Note::factory(),
            'noteKolloqium' => Note::factory(),
            'themaAbschlussarbeit' => $this->faker->paragraph(),
            'noteFachpraxis' => Note::factory(),
        ];
    }   

    public function withHatZulassung(): Factory
    {
        return $this->state(fn (): array => [
			'hatZulassung' => true,
		]);
    }

    public function withHatBestanden(): Factory
    {
        return $this->state(fn (): array => [
			'hatBestanden' => true,
		]);
    }

    public function withHatZulassungErweiterteBeruflicheKenntnisse(): Factory
    {
        return $this->state(fn (): array => [
			'hatZulassungErweiterteBeruflicheKenntnisse' => true,
		]);
    }

    public function withHatErworbenErweiterteBeruflicheKenntnisse(): Factory
    {
        return $this->state(fn (): array => [
			'hatErworbenErweiterteBeruflicheKenntnisse' => true,
		]);
    }

    public function withHatZulassungBerufsabschlusspruefung(): Factory
    {
        return $this->state(fn (): array => [
			'hatZulassungBerufsabschlusspruefung' => true,
		]);
    }

    public function withHatBestandenBerufsabschlusspruefung(): Factory
    {
        return $this->state(fn (): array => [
			'hatBestandenBerufsabschlusspruefung' => true,
		]);
    }

    public function withIstVorhandenBerufsabschlusspruefung(): Factory
    {
        return $this->state(fn (): array => [
			'istVorhandenBerufsabschlusspruefung' => true,
		]);
    }

    public function withIstFachpraktischerTeilAusreichend(): Factory
    {
        return $this->state(fn (): array => [
			'istFachpraktischerTeilAusreichend' => true,
		]);
    }
}

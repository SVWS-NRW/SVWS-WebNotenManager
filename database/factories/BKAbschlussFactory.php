<?php

namespace Database\Factories;

use App\Models\BKAbschluss;
use App\Models\Note;
use App\Models\Schueler;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\BKAbschluss>
 */
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
        return $this->state(fn () => ['hatZulassung' => true]);
    }

    public function withHatBestanden(): Factory
    {
        return $this->state(fn () => ['hatBestanden' => true]);
    }

    public function withHatZulassungErweiterteBeruflicheKenntnisse(): Factory
    {
        return $this->state(fn () => ['hatZulassungErweiterteBeruflicheKenntnisse' => true]);
    }

    public function withHatErworbenErweiterteBeruflicheKenntnisse(): Factory
    {
        return $this->state(fn () => ['hatErworbenErweiterteBeruflicheKenntnisse' => true]);
    }

    public function withHatZulassungBerufsabschlusspruefung(): Factory
    {
        return $this->state(fn () => ['hatZulassungBerufsabschlusspruefung' => true]);
    }

    public function withHatBestandenBerufsabschlusspruefung(): Factory
    {
        return $this->state(fn () => ['hatBestandenBerufsabschlusspruefung' => true]);
    }

    public function withIstVorhandenBerufsabschlusspruefung(): Factory
    {
        return $this->state(fn () => ['istVorhandenBerufsabschlusspruefung' => true]);
    }

    public function withIstFachpraktischerTeilAusreichend(): Factory
    {
        return $this->state(fn () => ['istFachpraktischerTeilAusreichend' => true]);
    }
}

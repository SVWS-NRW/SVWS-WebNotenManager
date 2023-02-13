<?php

namespace Database\Factories;

use App\Models\BKAbschluss;
use App\Models\BKFach;
use App\Models\Fach;
use App\Models\Note;
use App\Models\Lehrer;
use Illuminate\Database\Eloquent\Factories\Factory;

class BKFachFactory extends Factory
{
    protected $model = BKFach::class;

    public function definition(): array
    {
        return [
            'bkabschluss_id' => BKAbschluss::factory(),
            'fach_id' => Fach::factory(),
            'lehrer_id' => Lehrer::factory(),
            'vornote' => Note::factory(),
            'noteSchriftlichePruefung' => Note::factory(),        
            'noteMuendlichePruefung' => Note::factory(),
            'noteBerufsabschluss' => Note::factory(),
            'abschlussnote' => Note::factory(),
        ];
    }

    public function withIstSchriftlich(): Factory
    {
        return $this->state(fn (): array => [
			'istSchriftlich' => true,
		]);
    }
   
    public function withMuendlichePruefung(): Factory
    {
        return $this->state(fn (): array => [
			'muendlichePruefung' => true,
		]);
    }    

    public function withMuendlichePruefungFreiwillig(): Factory
    {
        return $this->state(fn (): array  => [
			'muendlichePruefungFreiwillig' => true,
		]);
    }    

    public function withIstSchriftlichBerufsabschluss(): Factory
    {
        return $this->state(fn (): array  => [
			'istSchriftlichBerufsabschluss' => true,
		]);
    }
}

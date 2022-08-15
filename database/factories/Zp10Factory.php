<?php

namespace Database\Factories;

use App\Models\Fach;
use App\Models\Note;
use App\Models\Schueler;
use App\Models\Zp10;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Zp10>
 */
class Zp10Factory extends Factory
{    
    protected $model = Zp10::class;
    
    public function definition(): array
    {
        return [
            'schueler_id' => Schueler::factory(),
            'fach_id' => Fach::factory(),
            'vornote' => Note::factory(),
            'noteSchriftlichePruefung' => Note::factory(),
            'noteMuendlichePruefung' => Note::factory(),
            'abschlussnote' => Note::factory(),
        ];
    }    
   
    public function withMuendlichePruefung(): Factory
    {
        return $this->state(fn () => ['muendlichePruefung' => true]);
    }    

    public function withMuendlichePruefungFreiwillig(): Factory
    {
        return $this->state(fn () => ['muendlichePruefungFreiwillig' => true]);
    }    
}

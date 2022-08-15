<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LeistungNormalized extends Model
{
    use HasFactory;

	protected $table = 'leistung_normalized';

	protected $fillable = [
		'leistung_id',
		'klasse',
		'vorname',
		'nachname',
		'geschlecht',
		'jahrgang',
		'fach',
		'lehrer',
		'kurs',
		'note',
        'istGemahnt',
		'fs',
		'ufs',
		'asv',
		'aue',
		'zb',
	];

	protected $casts = [
		'istGemahnt' => 'bool',
		'asv' => 'bool',
		'aue' => 'bool',
		'zb' => 'bool',
	];

	public function leistung(): BelongsTo
	{
		return $this->belongsTo(Leistung::class);
	}
}

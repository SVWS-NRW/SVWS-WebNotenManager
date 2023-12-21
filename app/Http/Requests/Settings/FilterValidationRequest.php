<?php

namespace App\Http\Requests\Settings;

use Illuminate\Foundation\Http\FormRequest;

class FilterValidationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'filters_meinunterricht.mahnungen' => ['required', 'boolean'],
            'filters_meinunterricht.bemerkungen' => ['required', 'boolean'],
            'filters_meinunterricht.fehlstunden' => ['required', 'boolean'],
            'filters_meinunterricht.teilleistungen' => ['required', 'boolean'],
            'filters_meinunterricht.kurs' => ['required', 'boolean'],
            'filters_meinunterricht.note' => ['required', 'boolean'],
            'filters_meinunterricht.fach' => ['required', 'boolean'],

            'filters_leistungsdatenuebersicht.mahnungen' => ['required', 'boolean'],
            'filters_leistungsdatenuebersicht.fachlehrer' => ['required', 'boolean'],
            'filters_leistungsdatenuebersicht.bemerkungen' => ['required', 'boolean'],
            'filters_leistungsdatenuebersicht.fehlstunden' => ['required', 'boolean'],
            'filters_leistungsdatenuebersicht.teilleistungen' => ['required', 'boolean'],
            'filters_leistungsdatenuebersicht.kurs' => ['required', 'boolean'],
            'filters_leistungsdatenuebersicht.note' => ['required', 'boolean'],
            'filters_leistungsdatenuebersicht.fach' => ['required', 'boolean'],
        ];
    }

    public function attributes()
    {
        return [
            'filters_meinunterricht.mahnungen' => 'Mein Unterricht Mahnungen',
            'filters_meinunterricht.bemerkungen' => 'Mein Unterricht Bemerkungen',
            'filters_meinunterricht.fehlstunden' => 'Mein Unterricht Fehlstunden',
            'filters_meinunterricht.teilleistungen' => 'Mein Unterricht Teilleistungen',
            'filters_meinunterricht.kurs' => 'Mein Unterricht Kurs',
            'filters_meinunterricht.note' => 'Mein Unterricht Note',
            'filters_meinunterricht.fach' => 'Mein Unterricht Fach',

            'filters_leistungsdatenuebersicht.mahnungen' => 'Leistungsdatenuebersicht Mahnungen',
            'filters_leistungsdatenuebersicht.fachlehrer' => 'Leistungsdatenuebersicht Fachlehrer',
            'filters_leistungsdatenuebersicht.bemerkungen' => 'Leistungsdatenuebersicht Bemerkungen',
            'filters_leistungsdatenuebersicht.fehlstunden' => 'Mein Unterricht Fehlstunden',
            'filters_leistungsdatenuebersicht.teilleistungen' => 'Leistungsdatenuebersicht Teilleistungen',
            'filters_leistungsdatenuebersicht.kurs' => 'Leistungsdatenübersicht Kurs',
            'filters_leistungsdatenuebersicht.note' => 'Leistungsdatenübersicht Note',
            'filters_leistungsdatenuebersicht.fach' => 'Leistungsdatenübersicht Fach',
        ];
    }
}
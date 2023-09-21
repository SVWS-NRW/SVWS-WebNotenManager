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
            'filters_leistungsdatenuebersicht.mahnungen' => ['required', 'boolean'],
            'filters_leistungsdatenuebersicht.fachlehrer' => ['required', 'boolean'],
            'filters_leistungsdatenuebersicht.bemerkungen' => ['required', 'boolean'],
            'filters_leistungsdatenuebersicht.teilleistungen' => ['required', 'boolean'],

            'filters_mein_unterricht.mahnungen' => ['required', 'boolean'],
            'filters_mein_unterricht.bemerkungen' => ['required', 'boolean'],
            'filters_mein_unterricht.fehlstunden' => ['required', 'boolean'],
            'filters_mein_unterricht.teilleistungen' => ['required', 'boolean'],
        ];
    }

    public function attributes()
    {
        return [
            'filters_leistungsdatenuebersicht.mahnungen' => 'Leistungsdatenuebersicht Mahnungen',
            'filters_leistungsdatenuebersicht.fachlehrer' => 'Leistungsdatenuebersicht Fachlehrer',
            'filters_leistungsdatenuebersicht.bemerkungen' => 'Leistungsdatenuebersicht Bemerkungen',
            'filters_leistungsdatenuebersicht.teilleistungen' => 'Leistungsdatenuebersicht Teilleistungen',

            'filters_mein_unterricht.mahnungen' => 'Mein Unterricht Mahnungen',
            'filters_mein_unterricht.bemerkungen' => 'Mein Unterricht Bemerkungen',
            'filters_mein_unterricht.fehlstunden' => 'Mein Unterricht Fehlstunden',
            'filters_mein_unterricht.teilleistungen' => 'Mein Unterricht Teilleistungen',
        ];
    }
}

<?php

namespace App\Http\Requests\Settings;

use App\Models\Bemerkung;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class MailSendCredentialsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()->is_administrator;
    }

    public function rules(): array
    {
        return [
            'MAIL_MAILER' => [
                'required', 'string',
            ],
            'MAIL_HOST' => [
                'required', 'string',
            ],
            'MAIL_PORT' => [
                'required', 'integer',
            ],
            'MAIL_USERNAME' => [
                'required', 'string',
            ],
            'MAIL_PASSWORD' => [
                'required', 'string',
            ],
            'MAIL_ENCRYPTION' => [
                'required', 'string',
            ],
            'MAIL_FROM_ADDRESS' => [
                'required', 'string', 'email:rfc,dns'
            ],
            'MAIL_FROM_NAME' => [
                'nullable', 'string',
            ],
        ];
    }

    public function attributes(): array
    {
        return [
            'MAIL_MAILER' => 'Mailer',
            'MAIL_HOST' => 'Host',
            'MAIL_PORT' => 'Port',
            'MAIL_USERNAME' => 'Benutzername',
            'MAIL_PASSWORD' => 'Kennwort',
            'MAIL_ENCRYPTION' => 'Verschlüsselung',
            'MAIL_FROM_ADDRESS' => 'Absender E-Mail Adresse',
            'MAIL_FROM_NAME' => 'Absender Name',
        ];
    }
}

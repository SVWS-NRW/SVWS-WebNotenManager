<?php

namespace App\Console\Commands;

use App\Services\EnvService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Validator;

class Install extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Lorem ipsum dolor sit amet';

    /**
     * Class constructor
     *
     * @param EnvService $service
     */
    public function __construct(private EnvService $service)
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(): int
    {
        $this->singleQuestion(
            fn (): string => $this->ask('APP URL', config('app.url')),
            'App Url',
            'APP_URL',
            ['required', 'url' , 'regex:/^https:\/\/.+$/'],
        );

        $this->singleQuestion(
            fn (): string => $this->ask('Schulnummer', config('wenom.schulnummer')),
            'Schulnummer',
            'SCHULNUMMER',
            ['required', 'numeric'],
        );

        $this->singleQuestion(
            fn (): string => $this->ask('AES Password', config('wenom.aes_password')),
            'AES Password',
            'AES_PASSWORD',
            ['required', 'string'],
        );

        $this->singleQuestion(
            fn (): string => $this->ask('AES Salt', config('wenom.aes_salt')),
            'AES Salt',
            'AES_SALT',
            ['required', 'string'],
        );

        $this->singleQuestion(
            fn (): string => $this->ask('DB Host', config('wenom.db.host')),
            'DB Host',
            'DB_HOST',
            ['required', 'string'],
        );

        $this->singleQuestion(
            fn (): int => $this->ask('DB PORT', config('wenom.db.port')),
            'DB Port',
            'DB_PORT',
            ['required', 'numeric'],
        );

        $this->singleQuestion(
            fn (): string => $this->ask('DB Database', config('wenom.db.database')),
            'DB Database',
            'DB_DATABASE',
            ['required', 'string'],
        );

        $this->singleQuestion(
            fn (): string => $this->ask('DB Username', config('wenom.db.username')),
            'DB Username',
            'DB_USERNAME',
            ['required', 'string'],
        );

        $this->singleQuestion(
            fn (): string => $this->secret('DB Password', config('wenom.db.password')),
            'DB Password',
            'DB_PASSWORD',
            ['required', 'string'],
        );

        $this->singleQuestion(
            fn (): string => $this->ask('Mail Host', config('wenom.mail_send_credentials.host')),
            'Mail Host',
            'MAIL_HOST',
            ['required', 'string'],
        );

        $this->singleQuestion(
            fn (): int => $this->ask('Mail Port', config('wenom.mail_send_credentials.port')),
            'Mail Port',
            'MAIL_PORT',
            ['required', 'numeric'],
        );

        $this->singleQuestion(
            fn (): string => $this->ask('Mail Username', config('wenom.mail_send_credentials.username')),
            'Mail Username',
            'MAIL_USERNAME',
            ['required', 'string'],
        );

        $this->singleQuestion(
            fn (): string => $this->secret('Mail Passwort', config('wenom.mail_send_credentials.password')),
            'Mail Passwort',
            'MAIL_PASSWORD',
            ['required', 'string'],
        );

        $this->singleQuestion(
            fn (): string => $this->choice(
                'Mail Encryption',
                ['tls', 'ssl'],
                config('wenom.mail_send_credentials.encryption'),
            ),
            'Mail Encryption',
            'MAIL_ENCRYPTION',
            ['required', 'string'],
        );

        $this->singleQuestion(
            fn (): string => $this->ask('Mail From Adresse', config('wenom.mail_send_credentials.from_address')),
            'Mail From Adresse',
            'MAIL_FROM_ADDRESS',
            ['required', 'email:dns,rfc'],
        );

        $this->singleQuestion(
            fn (): string => $this->ask('Mail From Name', config('wenom.mail_send_credentials.from_name')),
            'Mail From NAME',
            'MAIL_FROM_NAME',
            ['required', 'string'],
        );

        return Command::SUCCESS;
    }

    /**
     * Single question validator
     *
     * @param callable $question
     * @param string $title
     * @param string $envField
     * @param array<string, string> $rules
     */
    private function singleQuestion(
        callable $question,
        string $title,
        string $envField,
        array $rules,
    ): void {
        while (true) {
            $ask = $question();
            $key = strtolower($envField);

            $validator = Validator::make([$key => $ask], [$key => $rules], [], [$key => $title]);

            if ($validator->passes()) {
                $this->info("{$title} gültig");
                $this->service->update($envField, $ask);
                break;
            }

            if ($validator->fails()) {
                $this->error("{$title} ungültig");

                foreach ($validator->errors()->all() as $error) {
                    $this->error("- {$error}");
                }
            }
        }
    }
}

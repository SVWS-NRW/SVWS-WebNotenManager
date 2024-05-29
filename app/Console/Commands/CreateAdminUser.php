<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class CreateAdminUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'create:admin-user';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Ersteinrichtung ein technischer Admin';

    /**
     * Validation rules
     *
     * @var array
     */
    private array $validationRules = [
        'email' => ['required', 'email:rfc,dns', 'unique:users,email'],
        'password' => ['required', 'string', 'min:8'],
    ];

    /**
     * Custom attributes
     *
     * @var array<string, string>
     */
    private array $customAttributes = [
        'email' => 'E-Mail-Adresse',
        'password' => 'Passwort',
    ];

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(): int
    {
        // Get input from console
        $email = $this->ask($this->customAttributes['email']);
        $password = $this->secret($this->customAttributes['password']);

        // Validate input
        $validator = Validator::make(
            data: ['email' => $email, 'password' => $password],
            rules: $this->validationRules,
            customAttributes: $this->customAttributes,
        );

        // Show error messages if validator fails
        if ($validator->fails()) {
            $this->error('Technischer Admin koennte nicht erstellt werden:');

            foreach ($validator->errors()->all() as $error) {
                $this->error("- {$error}");
            }

            return Command::FAILURE;
        }

        // Create the user
        User::factory()->administrator()->create(['email' => $email, 'password' => Hash::make($password)]);

        // Display the success message
        $this->info('Technischer Admin wurde erfolgreich angelegt');
        return Command::SUCCESS;
    }
}

<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Passport\ClientRepository;
use Tests\TestCase;

class SecureTransferControllerTest extends TestCase
{
    use RefreshDatabase;

    private function obtainToken(): string
    {
        $clientRepository = new ClientRepository();

        $client = $clientRepository->createPersonalAccessClient(
            null,
            'Test Personal Access Client',
            config('app.url')
        );

        $response = $this->postJson(route('passport.token'), [
            'grant_type' => 'client_credentials',
            'client_id' => $client->id,
            'client_secret' => $client->secret,
            'scope' => '',
        ]);

        return json_decode($response->getContent(), true)['access_token'];
    }

    public function testItTruncatesDatabases(): void
    {
        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $this->obtainToken()])
            ->postJson(route('secure.truncate'))
            ->assertOk();
    }

    public function testItProvidesProperResponseStructure(): void
    {
        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $this->obtainToken()])
            ->postJson(route('secure.truncate'))
            ->assertJsonStructure([
                'message' => [
                    'tables' => [
                        'truncated', 'kept', 'kept_tables',
                    ],
                    'users' => [
                        'deleted', 'kept',
                    ]
                ],
            ]);
    }

    public function testItDeletesLehrerAccountsButNotAdministrators(): void
    {
        $lehrerCount = 10;
        $adminCount = 2;

        User::factory($lehrerCount)->lehrer()->create();
        User::factory($adminCount)->administrator()->create();

        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $this->obtainToken()])
            ->postJson(route('secure.truncate'))
            ->assertJsonPath('message.users.deleted', $lehrerCount)
            ->assertJsonPath('message.users.kept', $adminCount);
    }

    public function testItTruncatesTablesExceptEssentialOnes(): void
    {
        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $this->obtainToken()])
            ->postJson(route('secure.truncate'))
            ->assertJsonPath('message.tables.truncated', 31)
            ->assertJsonPath('message.tables.kept', 4)
            ->assertJsonPath('message.tables.kept_tables', [
                'migrations', 'users', 'oauth_clients', 'settings',
            ]);
    }
}

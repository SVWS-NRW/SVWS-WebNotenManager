<?php

namespace App\Http\Controllers;

use App\Http\Requests\PassportRequest;
use Illuminate\Http\JsonResponse;
use Laravel\Passport\Client;
use Laravel\Passport\ClientRepository;
use Symfony\Component\HttpFoundation\Response;

/**
 * Passport client controller
 */
class PassportController extends Controller
{
    /**
     * List all OAuth clients.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        // Querying the Client model to get a list of clients with specific fields.
        $clients = Client::query()
            ->select('id', 'name', 'secret', 'created_at')
            ->get()
            ->each(fn (Client $client): Client => $client->makeVisible('secret'));

        return response()->json($clients);
    }

    /**
     * Create a new OAuth client.
     *
     * @param PassportRequest $request
     * @return JsonResponse
     */
    public function store(PassportRequest $request): JsonResponse
    {
        // Querying the Client model to get a list of clients with specific fields.
        $client = (new ClientRepository)->create(null, $request->get('name'), '');

        return response()->json([
            'id' => $client->id,
            'name' => $client->name,
            'secret' => $client->secret,
            'created_at' => $client->created_at,
        ], Response::HTTP_CREATED);
    }

    /**
     * Method to delete an OAuth client.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        $client = new Client();
        $client = $client->find($id);
        $client->delete();

        return response()->json(status: Response::HTTP_NO_CONTENT);
    }
}

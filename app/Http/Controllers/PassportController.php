<?php

namespace App\Http\Controllers;

use App\Http\Requests\PassportRequest;
use Illuminate\Http\JsonResponse;
use Laravel\Passport\Client;
use Laravel\Passport\ClientRepository;
use Symfony\Component\HttpFoundation\Response;

class PassportController extends Controller
{
    public function index(): JsonResponse
    {
        $clients = Client::query()
            ->select('id', 'name', 'secret', 'created_at')
            ->get()
            ->each(fn (Client $client): Client => $client->makeVisible('secret') );

        return response()->json($clients);
    }

    public function store(PassportRequest $request): JsonResponse
    {
        $client = (new ClientRepository)->create(null, $request->get('name'), '');

        return response()->json([
            'id' => $client->id,
            'name' => $client->name,
            'secret' => $client->secret,
            'created_at' => $client->created_at,
        ], Response::HTTP_CREATED);
    }

    //public function destroy(Client $client): JsonResponse
    public function destroy(int $client_id)
    {
        $client = new Client();
        $client = $client->find($client_id);
        $client->delete();
        
        return response()->json(status: Response::HTTP_NO_CONTENT);
    }
}

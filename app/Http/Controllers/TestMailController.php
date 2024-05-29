<?php

namespace App\Http\Controllers;

use App\Mail\TestMail;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Mail;

class TestMailController extends Controller
{
    public function sendTestMail(): void
    {
        $title = 'WeNoM - Testmail';
        $body = 'Dies ist eine Testmail von WeNoM';
        $username = config("wenom.mail_send_credentials.username");

        Mail::to($username)->send(new TestMail($title, $body));

        return;
    }
}

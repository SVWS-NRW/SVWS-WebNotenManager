<?php

namespace App\Http\Controllers;

use App\Notifications\TestNotification;
use Notification;

class TestMailController extends Controller
{
    /**
     * Send test email
     *
     * @return void
     */
    public function sendTestMail(): void
    {
        Notification::route('mail', auth()->user()->email)
            ->notify(new TestNotification);
    }
}

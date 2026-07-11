<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ContactController extends Controller
{
    public function send(Request $request)
    {
        $data = $request->validate([
            'nom' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'sujet' => ['required', 'string', 'max:255'],
            'message' => ['required', 'string'],
        ]);

        $line = json_encode([
            'date' => now()->toDateTimeString(),
            'nom' => $data['nom'],
            'email' => $data['email'],
            'sujet' => $data['sujet'],
            'message' => $data['message'],
        ], JSON_UNESCAPED_UNICODE);

        Storage::append('messages_en_attente.txt', $line);

        return redirect()->route('login', ['contact' => 'queued'])->withFragment('contact');
    }

    public function sendPending()
    {
        if (! Storage::exists('messages_en_attente.txt') || trim(Storage::get('messages_en_attente.txt')) === '') {
            return redirect()->route('login', ['contact' => 'no_pending'])->withFragment('contact');
        }

        return redirect()->route('login', ['contact' => 'pending_failed'])->withFragment('contact');
    }
}

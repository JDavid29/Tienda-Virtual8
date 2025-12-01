<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Mail\ContactFormMail;

class ContactController extends Controller
{
    /**
     * Handle non-JS form submission (POST fallback).
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|min:2',
            'email' => 'required|email',
            'subject' => 'nullable|string|min:3',
            'message' => 'required|string|min:10',
        ]);

        try {
            // Basic SMTP connectivity attempt (best-effort)
            if (method_exists(Mail::getSwiftMailer(), 'getTransport')) {
                try {
                    $transport = Mail::getSwiftMailer()->getTransport();
                    if (method_exists($transport, 'start')) {
                        $transport->start();
                    }
                } catch (\Throwable $transportEx) {
                    Log::error('SMTP transport start failed (post fallback): ' . $transportEx->getMessage());
                    return back()->withInput()->with('error', 'No se puede conectar al servidor SMTP. Verifica la configuración.');
                }
            }

            $to = env('MAIL_TO_ADDRESS', config('mail.from.address'));
            Mail::to($to)->send(new ContactFormMail($data));

            return redirect()->back()->with('success', 'Mensaje enviado correctamente.');
        } catch (\Throwable $e) {
            Log::error('Contact POST fallback failed: ' . $e->getMessage(), ['payload' => $data]);
            return back()->withInput()->with('error', 'Error al enviar el mensaje. Intenta más tarde.');
        }
    }
}

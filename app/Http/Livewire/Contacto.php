<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Mail;
use App\Mail\ContactFormMail;
use Illuminate\Support\Facades\Log;

class Contacto extends Component
{
    public $name = '';
    public $email = '';
    public $subject = '';
    public $message = '';

    protected $rules = [
        'name' => 'required|string|min:2',
        'email' => 'required|email',
        'subject' => 'nullable|string|min:3',
        'message' => 'required|string|min:10',
    ];

    public function updated($propertyName)
    {
        // If the application key is missing, Livewire requests can return
        // full HTML/error pages and cause the client to replace the whole DOM.
        // Skip incremental validation when APP_KEY is not set to avoid
        // producing responses that break the page. The real fix is to
        // generate the APP_KEY and clear caches on the server.
        if (empty(config('app.key'))) {
            Log::warning('Skipping Livewire validateOnly in Contacto because APP_KEY is missing.');
            return;
        }

        try {
            $this->validateOnly($propertyName);
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Set the error bag explicitly so Livewire doesn't throw an uncaught exception
            $this->setErrorBag($e->validator->getMessageBag());
        }
    }

    public function send()
    {
        try {
            $validated = $this->validate();
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Keep the component rendered and show validation messages without replacing the whole page
            $this->setErrorBag($e->validator->getMessageBag());
            $this->dispatchBrowserEvent('contact-notification', [
                'type' => 'error',
                'message' => 'Por favor corrige los errores del formulario.'
            ]);
            return;
        }
        // Verificar conexión SMTP antes de intentar enviar
        try {
            // Si la app usa SwiftMailer (Laravel <9), intentamos arrancar el transport
            if (method_exists(Mail::getSwiftMailer(), 'getTransport')) {
                try {
                    $transport = Mail::getSwiftMailer()->getTransport();
                    if (method_exists($transport, 'start')) {
                        $transport->start();
                    }
                } catch (\Throwable $transportEx) {
                    // Si falla el start, lanzamos una excepción controlada para informar al usuario
                    Log::error('SMTP transport start failed: ' . $transportEx->getMessage());
                    session()->flash('error', 'No se puede conectar al servidor SMTP. Verifica la configuración (host/puerto/usuario).');
                    return;
                }
            } else {
                // Fallback: comprobar conexión TCP básica al host:port
                $host = config('mail.host');
                $port = config('mail.port');
                $connected = false;
                try {
                    $fp = @fsockopen($host, $port, $errno, $errstr, 5);
                    if ($fp) {
                        fclose($fp);
                        $connected = true;
                    }
                } catch (\Throwable $sockEx) {
                    Log::error('SMTP socket check failed: ' . $sockEx->getMessage());
                }

                if (! $connected) {
                    Log::error("SMTP TCP connection to {$host}:{$port} failed");
                    session()->flash('error', 'No se puede conectar al servidor SMTP. Verifica la configuración (host/puerto).');
                    return;
                }
            }

            // Si la verificación pasó, intentamos enviar
            $to = env('MAIL_TO_ADDRESS', config('mail.from.address'));
            Mail::to($to)->send(new ContactFormMail($validated));

            session()->flash('success', 'Mensaje enviado correctamente.');
            $this->dispatchBrowserEvent('contact-notification', [
                'type' => 'success',
                'message' => 'Mensaje enviado correctamente.'
            ]);

            // reset fields
            $this->reset(['name', 'email', 'subject', 'message']);
        } catch (\Throwable $e) {
            Log::error('Contact form send failed: ' . $e->getMessage(), ['payload' => $validated ?? null]);
            session()->flash('error', 'Error al enviar el mensaje. Intenta más tarde.');
            $this->dispatchBrowserEvent('contact-notification', [
                'type' => 'error',
                'message' => 'Error al enviar el mensaje. Intenta más tarde.'
            ]);
        }
    }

    public function render()
    {
        return view('livewire.contacto');
    }
}

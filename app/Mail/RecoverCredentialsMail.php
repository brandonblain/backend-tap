<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class RecoverCredentialsMail extends Mailable
{
    use Queueable, SerializesModels;

    private string $name;
    private string $temporaryPassword;

    public function __construct(string $name, string $temporaryPassword)
    {
        $this->name = $name;
        $this->temporaryPassword = $temporaryPassword;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Recuperación de Credenciales - TAP Terminal',
        );
    }

    public function content(): Content
    {
        return new Content(
            html: "<h2>Hola, {$this->name}</h2>" .
                  "<p>Se ha solicitado la recuperación de tus credenciales para acceder a <strong>TAP Terminal</strong>.</p>" .
                  "<p>Tu nueva contraseña de acceso temporal es:</p>" .
                  "<div style='background: #f4f4f4; padding: 10px; display: inline-block; font-family: monospace; font-size: 16px; font-weight: bold; color: #d9534f;'>#IF_PASSWORD#</div>" .
                  "<p>Por seguridad, inicia sesión con esta clave y actualízala en cuanto ingreses.</p>"
        );
    }

    public function build()
    {
        return $this->html(str_replace('#IF_PASSWORD#', $this->temporaryPassword, $this->content()->html));
    }
}
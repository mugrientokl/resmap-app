<?php

namespace App\Notifications;

use App\Models\SolicitudWeb;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class SolicitudWebRecibida extends Notification
{
    use Queueable;

    public function __construct(public SolicitudWeb $solicitud) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'tipo' => 'solicitud', 'titulo' => 'Nueva solicitud web',
            'mensaje' => 'Solicitud #'.$this->solicitud->id_solicitud.' de '.$this->solicitud->cliente->nombre,
            'url' => route('solicitudes.index'),
        ];
    }
}

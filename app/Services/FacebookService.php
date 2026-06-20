<?php

namespace App\Services;

use App\Models\Vehiculo;
use Illuminate\Support\Facades\Http;

class FacebookService
{
    protected $accessToken;
    protected $pageId;

    public function __construct()
    {
        $this->accessToken = config('services.facebook.access_token');
        $this->pageId = config('services.facebook.page_id');
    }

    public function publicar(Vehiculo $vehiculo)
    {
        $mensaje = $this->generarMensaje($vehiculo);
        
        $response = Http::post("https://graph.facebook.com/v18.0/{$this->pageId}/feed", [
            'message' => $mensaje,
            'access_token' => $this->accessToken
        ]);

        if ($response->successful()) {
            $vehiculo->publicacionesSociales()->create([
                'plataforma' => 'Facebook',
                'fecha_programada' => now(),
                'estado' => 'Publicado'
            ]);
            return ['success' => true, 'post_id' => $response->json('id')];
        }

        return ['success' => false, 'error' => $response->json('error.message')];
    }

    private function generarMensaje(Vehiculo $vehiculo)
    {
        $mensaje = "🚗 " . strtoupper($vehiculo->modelo->marca->nombre) . " " . $vehiculo->modelo->nombre . "\n";
        $mensaje .= "📅 Año: " . $vehiculo->ano . "\n";
        $mensaje .= "📏 Kilometraje: " . number_format($vehiculo->kilometraje, 0, ',', '.') . " km\n";
        $mensaje .= "⚡ " . $vehiculo->transmision . " | " . $vehiculo->combustible . "\n";
        $mensaje .= "💰 $" . number_format($vehiculo->precio_venta, 0, ',', '.') . "\n\n";
        $mensaje .= "📞 +56 9 4974 1680\n";
        $mensaje .= "📍 Mendoza 856, Los Ángeles\n";
        $mensaje .= "#BRAutos #VentaDeAutos #Chile";
        
        return $mensaje;
    }
}
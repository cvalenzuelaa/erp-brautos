<?php

namespace App\Services;

use App\Models\Vehiculo;
use Illuminate\Support\Facades\Http;

class InstagramService
{
    protected $accessToken;
    protected $userId;

    public function __construct()
    {
        $this->accessToken = config('services.instagram.access_token');
        $this->userId = config('services.instagram.user_id');
    }

    public function publicar(Vehiculo $vehiculo)
    {
        // Obtener imagen principal
        $imagen = $vehiculo->imagenes()->where('es_principal', true)->first();
        
        if (!$imagen) {
            return ['success' => false, 'error' => 'No hay imagen principal'];
        }

        // 1. Subir la imagen a Instagram
        $uploadResponse = Http::post("https://graph.facebook.com/v18.0/{$this->userId}/media", [
            'image_url' => $imagen->ruta_imagen,
            'caption' => $this->generarCaption($vehiculo),
            'access_token' => $this->accessToken
        ]);

        if (!$uploadResponse->successful()) {
            return ['success' => false, 'error' => $uploadResponse->json('error.message')];
        }

        $creationId = $uploadResponse->json('id');

        // 2. Publicar la imagen
        $publishResponse = Http::post("https://graph.facebook.com/v18.0/{$this->userId}/media_publish", [
            'creation_id' => $creationId,
            'access_token' => $this->accessToken
        ]);

        if ($publishResponse->successful()) {
            $vehiculo->publicacionesSociales()->create([
                'plataforma' => 'Instagram',
                'fecha_programada' => now(),
                'estado' => 'Publicado'
            ]);
            return ['success' => true, 'post_id' => $publishResponse->json('id')];
        }

        return ['success' => false, 'error' => $publishResponse->json('error.message')];
    }

    private function generarCaption(Vehiculo $vehiculo)
    {
        $caption = "🚗 " . strtoupper($vehiculo->modelo->marca->nombre) . " " . $vehiculo->modelo->nombre . "\n";
        $caption .= "📅 " . $vehiculo->ano . " | 📏 " . number_format($vehiculo->kilometraje, 0, ',', '.') . " km\n";
        $caption .= "⚡ " . $vehiculo->transmision . " | " . $vehiculo->combustible . "\n";
        $caption .= "💰 $" . number_format($vehiculo->precio_venta, 0, ',', '.') . "\n\n";
        $caption .= "📞 +56 9 4974 1680\n";
        $caption .= "📍 Mendoza 856, Los Ángeles\n";
        $caption .= "#BRAutos #VentaDeAutos #Chile";
        
        return $caption;
    }
}
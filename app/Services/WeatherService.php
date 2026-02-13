<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class WeatherService
{
    protected $apiKey;
    protected $baseUrl;

    public function __construct()
    {
        $this->apiKey = config('services.openweather.key');
        $this->baseUrl = config('services.openweather.url');
    }

    /**
     * Mengambil data cuaca (Bisa via Kota atau Koordinat)
     * @param array $params ['q' => 'Jakarta'] atau ['lat' => -3.4, 'lon' => 114.8]
     */
    public function getWeather(array $params)
    {
        // Merge parameter default dengan parameter input
        $queryParams = array_merge([
            'appid' => $this->apiKey,
            'units' => 'metric',
            'lang' => 'id'
        ], $params);

        $response = Http::get($this->baseUrl . 'weather', $queryParams);

        if ($response->failed()) {
            return null;
        }

        $data = $response->json();
        
        return [
            'city' => $data['name'],
            'temp' => round($data['main']['temp']),
            'desc' => $data['weather'][0]['description'],
            'main' => $data['weather'][0]['main'],
            'meta' => $this->getRecommendation($data['weather'][0]['main']),
        ];
    }

    private function getRecommendation(string $weatherType): array
    {
        // URL Gambar Anime Aesthetic (High Res)
        // Di masa depan, sebaiknya simpan gambar ini di public folder Anda sendiri.
        $backgrounds = [
            'clear' => 'https://w.wallhaven.cc/full/ox/wallhaven-oxkdd9.png', // Cerah/Langit Biru Anime
            'clouds' => 'https://w.wallhaven.cc/full/0q/wallhaven-0q6qw7.jpg', // Berawan/Mendung Aesthetic
            'rain' => 'https://w.wallhaven.cc/full/4x/wallhaven-4x8kdd.jpg', // Hujan Moody Anime
            'thunderstorm' => 'https://w.wallhaven.cc/full/49/wallhaven-49v3yx.jpg', // Badai/Gelap Anime
            'snow' => 'https://w.wallhaven.cc/full/45/wallhaven-45e1p5.jpg', // Salju/Winter Anime
            'default' => 'https://w.wallhaven.cc/full/6k/wallhaven-6krxr6.jpg' // Netral/Kabut
        ];

        return match (strtolower($weatherType)) {
            'clear' => [
                'text' => 'Cuaca cerah bersinar! Waktunya beraktivitas di luar dengan semangat.',
                'icon' => '☀️',
                'image' => $backgrounds['clear']
            ],
            'clouds' => [
                'text' => 'Langit berawan yang tenang. Suasana yang pas untuk bersantai.',
                'icon' => '☁️',
                'image' => $backgrounds['clouds']
            ],
            'rain', 'drizzle' => [
                'text' => 'Hujan turun membasahi bumi. Jangan lupa payungmu jika harus keluar.',
                'icon' => '🌧️',
                'image' => $backgrounds['rain']
            ],
            'thunderstorm' => [
                'text' => 'Cuaca sedang buruk di luar. Tetap aman di dalam ruangan ya!',
                'icon' => '🌩️',
                'image' => $backgrounds['thunderstorm']
            ],
            'snow' => [
                'text' => 'Dunia menjadi putih karena salju. Kenakan pakaian hangatmu.',
                'icon' => '❄️',
                'image' => $backgrounds['snow']
            ],
            default => [
                'text' => 'Tetap jaga kondisi tubuh di cuaca yang sedang tidak menentu ini.',
                'icon' => '🌡️',
                'image' => $backgrounds['default']
            ],
        };
    }
}
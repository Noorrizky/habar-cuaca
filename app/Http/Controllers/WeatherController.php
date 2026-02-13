<?php

namespace App\Http\Controllers;

use App\Services\WeatherService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class WeatherController extends Controller
{
    protected $weatherService;

    // Dependency Injection: Laravel otomatis memasukkan Service yang sudah kita buat
    public function __construct(WeatherService $weatherService)
    {
        $this->weatherService = $weatherService;
    }

    public function index(Request $request): View
    {
        $request->validate([
            'city' => 'nullable|string|max:255',
            'lat'  => 'nullable|numeric',
            'lon'  => 'nullable|numeric',
        ]);

        $weather = null;
        $error = null;

        // Logika Prioritas: Koordinat dulu -> baru Nama Kota
        if ($request->filled(['lat', 'lon'])) {
            $weather = $this->weatherService->getWeather([
                'lat' => $request->lat,
                'lon' => $request->lon
            ]);
        } 
        elseif ($request->filled('city')) {
            $weather = $this->weatherService->getWeather([
                'q' => $request->city
            ]);
        }

        // Error handling jika API gagal
        if (($request->filled('city') || $request->filled('lat')) && !$weather) {
            $error = 'Lokasi tidak ditemukan atau gangguan layanan.';
        }

        return view('weather', compact('weather', 'error'));
    }

    
}
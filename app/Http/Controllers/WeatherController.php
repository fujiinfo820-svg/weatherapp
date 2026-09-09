<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class WeatherController extends Controller
{
    public function index(Request $request)
    {
        // 検索フォームから値が届かなければ、初期表示は「Tokyo」
        $city = $request->input('city', 'Tokyo');
        $weatherData = null;
        $errorMessage = null;

        if ($city) {
            $apiKey = config('services.openweather.key');

            // OpenWeatherMap APIへリクエスト送信
            $response = Http::get("https://api.openweathermap.org/data/2.5/weather", [
                'q' => $city,
                'appid' => $apiKey,
                'units' => 'metric', // 摂氏（℃）表記
                'lang' => 'ja',      // 日本語の天気概要
            ]);

            if ($response->successful()) {
                $weatherData = $response->json();
            } else {
                $errorMessage = '都市が見つからないか、APIリクエストに失敗しました。';
            }
        }

        return view('weather', compact('weatherData', 'errorMessage', 'city'));
    }
}
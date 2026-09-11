<?php

namespace App\Http\Controllers;

use App\Models\Favorite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class WeatherController extends Controller
{
    public function index(Request $request)
    {
        $city = $request->input('city', 'Tokyo');
        $apiKey = config('services.openweather.key');
        $weatherData = null;
        $errorMessage = null;

        // OpenWeatherMap APIへリクエスト送信
        $response = Http::get("https://api.openweathermap.org/data/2.5/weather", [
            'q' => $city,
            'appid' => $apiKey,
            'units' => 'metric',
            'lang' => 'ja',
        ]);

        if ($response->successful()) {
            $weatherData = $response->json();
        } else {
            $errorMessage = '都市が見つからないか、APIリクエストに失敗しました。';
        }

        // ログイン中ならユーザーのお気に入り一覧を取得
        $favorites = [];
        $isFavorite = false;

        if (Auth::check()) {
            $favorites = Auth::user()->favorites()->latest()->get();
            // 現在表示中の都市がお気に入りに登録済みかチェック
            $isFavorite = Auth::user()->favorites()->where('city_name', $city)->exists();
        }

        return view('weather', compact('weatherData', 'errorMessage', 'city', 'favorites', 'isFavorite'));
    }

    // お気に入り追加
    public function store(Request $request)
    {
        $request->validate([
            'city_name' => 'required|string|max:255',
        ]);

        Auth::user()->favorites()->firstOrCreate([
            'city_name' => $request->city_name,
        ]);

        return back()->with('status', 'お気に入り都市に追加しました！');
    }

    // お気に入り削除
    public function destroy(Favorite $favorite)
    {
        // 自分の所有するお気に入りだけ削除許可
        if ($favorite->user_id === Auth::id()) {
            $favorite->delete();
        }

        return back()->with('status', 'お気に入り都市から削除しました。');
    }
}
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>天気予報アプリ</title>
    <style>
        body { font-family: sans-serif; max-width: 500px; margin: 50px auto; padding: 20px; text-align: center; }
        input { padding: 8px; font-size: 16px; width: 60%; }
        button { padding: 8px 16px; font-size: 16px; cursor: pointer; }
        .card { border: 1px solid #ccc; border-radius: 8px; padding: 20px; margin-top: 20px; background: #f9f9f9; }
        .error { color: red; margin-top: 20px; }
        .temp { font-size: 2.5em; font-weight: bold; }
    </style>
</head>
<body>
    <h1>天気予報アプリ</h1>

    <!-- 都市検索フォーム -->
    <form action="{{ route('weather.index') }}" method="GET">
        <input type="text" name="city" placeholder="都市名を英語で入力 (例: Osaka)" value="{{ $city }}">
        <button type="submit">検索</button>
    </form>

    <!-- エラー表示 -->
    @if ($errorMessage)
        <p class="error">{{ $errorMessage }}</p>
    @endif

    <!-- 天気情報カード -->
    @if ($weatherData)
        <div class="card">
            <h2>{{ $weatherData['name'] }}, {{ $weatherData['sys']['country'] }}</h2>
            <img src="https://openweathermap.org/img/wn/{{ $weatherData['weather'][0]['icon'] }}@2x.png" alt="天気アイコン">
            <p>{{ $weatherData['weather'][0]['description'] }}</p>
            <div class="temp">{{ round($weatherData['main']['temp']) }}℃</div>
            <p>湿度: {{ $weatherData['main']['humidity'] }}% / 体感: {{ round($weatherData['main']['feels_like']) }}℃</p>
        </div>
    @endif
</body>
</html>
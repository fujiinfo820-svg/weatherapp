<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>お天気アプリ</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-50 text-slate-800 min-h-screen p-6">
    <div class="max-w-xl mx-auto bg-white p-8 rounded-xl shadow-md">

        <!-- ヘッダー -->
        <div class="flex justify-between items-center mb-6 pb-4 border-b">
            <h1 class="text-2xl font-bold text-slate-700"><a href="/">天気予報</a></h1>
            <div>
                @auth
                    <span class="text-sm text-gray-600 mr-2">{{ Auth::user()->name }} さん</span>
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="text-xs text-red-500 hover:underline">ログアウト</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="text-sm text-blue-500 hover:underline mr-3">ログイン</a>
                    <a href="{{ route('register') }}" class="text-sm text-blue-500 hover:underline">新規登録</a>
                @endauth
            </div>
        </div>

        <!-- ステータスメッセージ -->
        @if (session('status'))
            <div class="mb-4 text-sm text-green-600 bg-green-50 p-3 rounded">
                {{ session('status') }}
            </div>
        @endif

        <!-- 検索フォーム -->
        <form action="{{ route('weather.index') }}" method="GET" class="flex gap-2 mb-6">
            <input type="text" name="city" value="{{ $city }}" placeholder="都市名 (Tokyo, Osakaなど)"
                   class="flex-1 border border-gray-300 px-4 py-2 rounded-lg focus:outline-none focus:border-blue-500">
            <button type="submit" class="bg-blue-600 text-white px-5 py-2 rounded-lg font-semibold hover:bg-blue-700">検索</button>
        </form>

        <!-- 天気表示カード -->
        @if ($weatherData)
            <div class="bg-blue-50 p-6 rounded-lg text-center mb-6 relative">
                <h2 class="text-xl font-bold">{{ $weatherData['name'] }}</h2>
                <img src="https://openweathermap.org/img/wn/{{ $weatherData['weather'][0]['icon'] }}@2x.png" alt="天気" class="mx-auto">
                <p class="text-gray-600 mb-2">{{ $weatherData['weather'][0]['description'] }}</p>
                <p class="text-4xl font-extrabold text-slate-800 mb-2">{{ round($weatherData['main']['temp']) }}℃</p>
                <p class="text-xs text-gray-500 mb-4">湿度: {{ $weatherData['main']['humidity'] }}% / 体感: {{ round($weatherData['main']['feels_like']) }}℃</p>

                <!-- お気に入り追加ボタン（ログイン時のみ表示） -->
                @auth
                    @if ($isFavorite)
                        <span class="inline-block text-xs bg-yellow-100 text-yellow-800 font-semibold px-3 py-1 rounded-full">★ お気に入り登録済み</span>
                    @else
                        <form action="{{ route('favorites.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="city_name" value="{{ $weatherData['name'] }}">
                            <button type="submit" class="text-sm bg-yellow-500 text-white px-4 py-1.5 rounded-lg font-semibold hover:bg-yellow-600">
                                ★ お気に入りに追加
                            </button>
                        </form>
                    @endif
                @endauth
            </div>
        @endif

        <!-- お気に入り一覧（ログイン時のみ表示） -->
        @auth
            <div class="mt-8 pt-6 border-t">
                <h3 class="text-lg font-bold mb-3">★ お気に入り都市一覧</h3>
                @if ($favorites->isEmpty())
                    <p class="text-sm text-gray-500">お気に入りに登録された都市はありません。</p>
                @else
                    <ul class="divide-y divide-gray-100">
                        @foreach ($favorites as $favorite)
                            <li class="py-2 flex justify-between items-center">
                                <a href="{{ route('weather.index', ['city' => $favorite->city_name]) }}" class="text-blue-600 hover:underline font-medium">
                                    {{ $favorite->city_name }}
                                </a>
                                <form action="{{ route('favorites.destroy', $favorite) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-xs text-red-500 hover:text-red-700">削除</button>
                                </form>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>
        @endauth

    </div>
</body>
</html>
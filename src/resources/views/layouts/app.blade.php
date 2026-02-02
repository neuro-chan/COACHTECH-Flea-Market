<!doctype html>
<html lang="ja">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', config('app.name'))</title>

    <!-- CSS -->
    <link rel="stylesheet" href="{{ asset('asset/css/layouts/sanitize.css') }}">
    <link rel="stylesheet" href="{{ asset('asset/css/layouts/common.css') }}">
    @stack('styles')
</head>

<body>
    <header class="header">
        <div class="header__inner">
            <a href="{{ url('/') }}" class="header__logo">
                <img src="{{ asset('asset/images/COACHTECH-header-logo.png') }}" alt="COACHTECH"
                    class="header__logo-image">
            </a>
            @auth
                <form class="header__search" role="search" method="GET">
                    <input id="q" name="q" type="search" placeholder="なにをお探しですか？"
                        value="{{ request('q') }}">
                </form>

                <nav class="header__actions" aria-label="ユーザーメニュー">
                    <form method="POST" action="{{ route('logout') }}" class="header__logout-form">
                        @csrf
                        <button type="submit" class="header__link">ログアウト</button>
                    </form>

                    <a href="" class="header__link">マイページ</a>

                    <a href="" class="header__button">出品</a>
                </nav>
            @endauth
        </div>
    </header>

    <main class="main">
        @yield('content')
    </main>

</body>

</html>

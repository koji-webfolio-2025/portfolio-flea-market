<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>フリマサイト</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@400;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    @yield('head')
    <style>
      body {
        font-family: 'Noto Sans JP', sans-serif;
      }
    </style>
</head>
<body>
    @if (request()->is('login') || request()->is('register'))
    {{-- ログイン・新規登録ページ用ヘッダー（ロゴだけ） --}}
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="/"><!--img src="{{ asset('images/logo.svg') }}" alt="ロゴ" height="24"-->CodeShift</a>
        </div>
    </nav>
    @else
    {{-- 通常ヘッダー --}}
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="/"><!--img src="{{ asset('images/logo.svg') }}" alt="ロゴ" height="24"-->CodeShift</a>
            {{-- トグルボタン --}}
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent" aria-controls="navbarContent" aria-expanded="false" aria-label="ナビゲーション切り替え">
                <span class="navbar-toggler-icon"></span>
            </button>
            {{-- トグル対象 --}}
            <div class="collapse navbar-collapse" id="navbarContent">
                <form action="{{ request()->is('mylist') ? route('items.mylist') : route('items.index') }}" method="GET">
                    <input type="text" name="keyword" class="form-control me-2" placeholder="なにをお探しですか？" value="{{ request('keyword') }}">
                </form>
                <ul class="navbar-nav ms-auto align-items-center">
                    @auth
                        <li class="nav-item">
                            <form method="POST" action="{{ route('logout') }}" style="display:inline;">
                                @csrf
                                <button type="submit" class="nav-link btn btn-link" style="padding:0; border:none; background:none; color:#fff; text-decoration:none;">
                                    ログアウト
                                </button>
                            </form>
                        </li>
                        <li class="nav-item"><a href="/mypage" class="nav-link">マイページ</a></li>
                        <li class="nav-item"><a href="/sell" class="btn btn-light ms-2">出品</a></li>
                    @else
                        <li class="nav-item"><a href="/login" class="nav-link">ログイン</a></li>
                        <li class="nav-item"><a href="/register" class="nav-link">新規登録</a></li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>
    @endif
    <main class="container mt-4">
        @if (session('success'))
        <div class="alert alert-success text-center mt-3 flash-message">
        {{ session('success') }}
        </div>
        @endif

        @if (session('error'))
        <div class="alert alert-danger text-center mt-3 flash-message">
        {{ session('error') }}
        </div>
        @endif

        <script>
        // 3秒後にフラッシュメッセージをフェードアウト
        setTimeout(() => {
            const flashMessages = document.querySelectorAll('.flash-message');
            flashMessages.forEach(msg => {
                msg.style.transition = 'opacity 0.5s';
                msg.style.opacity = '0';
                setTimeout(() => msg.remove(), 500);
            });
        }, 3000);
        </script>
        @yield('content')
    </main>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
@yield('scripts')
</body>
</html>

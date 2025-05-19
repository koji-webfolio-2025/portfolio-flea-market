@extends('layouts.app')

@section('title', 'ログイン')

@section('content')
<div class="container-sm mt-5">
    <h1 class="text-center">ログイン</h1>

    @if (session('error'))
        <div class="text-danger mb-3 text-center">{{ session('error') }}</div>
    @endif

    <form action="{{ route('login') }}" method="post">
        @csrf
        <div class="mb-3">
            <label for="email" class="form-label">メールアドレス</label>
            <input type="email" name="email" id="email" class="form-control" value="{{ old('email') }}">
            @error('email')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">パスワード</label>
            <input type="password" name="password" id="password" class="form-control">
            @error('password')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
        <button type="submit" class="btn btn-danger w-100">ログインする</button>
    </form>
    <div class="text-center mt-3">
        <a href="{{ route('register') }}">会員登録はこちら</a>
    </div>
</div>
@endsection

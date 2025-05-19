@extends('layouts.app')

@section('content')
<div class="container text-center mt-5">
    <h2>メール認証が必要です</h2>
    <p class="mt-3">ご登録いただいたメールアドレスに確認用メールを送信しました。</p>
    <p>メール内のリンクをクリックして、認証を完了してください。</p>

    @if (session('resent'))
        <div class="alert alert-success mt-3" role="alert">
            新しい認証メールを送信しました。
        </div>
    @endif

    <form class="mt-4" method="POST" action="{{ route('verification.send') }}">
        @csrf
        <button type="submit" class="btn btn-primary">認証メールを再送信</button>
    </form>
</div>
@endsection

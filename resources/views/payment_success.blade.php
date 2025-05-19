<!-- resources/views/payment_success.blade.php -->
@extends('layouts.app')

@section('title', '購入完了')

@section('content')
<div class="container mt-5">
    <h1>購入が完了しました！</h1>
    <p>ご利用ありがとうございました。</p>
    <a href="{{ route('items.index') }}" class="btn btn-danger mt-3">商品一覧に戻る</a>
</div>
@endsection

@extends('layouts.app')

@section('title', 'マイページ')
@section('head')
    <link rel="stylesheet" href="{{ asset('css/profile.css') }}">
@endsection

@section('content')
<div class="container-sm mt-4">
    <h2>マイページ</h2>
    <div class="card mb-4">
        <div class="card-body d-flex">
            <img src="{{ $user->image_url ?? 'https://placehold.jp/100x100.png' }}" alt="プロフィール画像" class="rounded-circle me-3" width="100" height="100">
            <div>
                <h4>{{ $user->name }}</h4>
                <p>メール: {{ $user->email }}</p>
                <a href="/mypage/profile" class="btn btn-secondary mt-2">プロフィールを編集する</a>
            </div>
        </div>
    </div>

    <!-- タブ -->
    <ul class="nav nav-tabs mb-4">
        <li class="nav-item">
            <a class="nav-link {{ $tab === 'sell' ? 'active' : '' }}" href="/mypage?tab=sell">出品した商品</a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ $tab === 'buy' ? 'active' : '' }}" href="/mypage?tab=buy">購入した商品</a>
        </li>
    </ul>

    @if ($tab === 'sell')
        <div class="row">
            @forelse ($soldItems as $item)
                <div class="col-md-4 mb-3">
                    <a href="{{ route('items.show', $item->id) }}" class="item-card-link d-block text-center text-decoration-none text-dark">
                        <div class="item-card">
                            <div class="image-wrapper position-relative">
                                <img src="{{ $item->image_url }}" class="img-fluid mb-2" alt="商品画像" style="object-fit: cover;">
                                @if ($item->is_sold)
                                    <span class="badge bg-danger position-absolute top-0 start-0 m-2">SOLD</span>
                                @endif
                            </div>
                            <div class="card-body">
                                <h5 class="card-title">{{ $item->title }}</h5>
                            </div>
                        </div>
                    </a>
                </div>
            @empty
                <p>出品履歴はありません。</p>
            @endforelse
        </div>
    @elseif ($tab === 'buy')
        <div class="row">
            @forelse ($purchasedItems as $item)
                <div class="col-md-4 mb-3">
                    <a href="{{ route('items.show', $item->id) }}" class="item-card-link d-block text-center text-decoration-none text-dark">
                        <div class="item-card">
                            <div class="image-wrapper position-relative">
                                <img src="{{ $item->image_url }}" alt="商品画像" class="img-fluid mb-2" style="object-fit: cover;">
                                @if ($item->is_sold)
                                <span class="badge bg-danger position-absolute top-0 start-0 m-2">SOLD</span>
                                @endif
                            </div>
                            <div class="card-body">
                                <h5 class="card-title">{{ $item->title }}</h5>
                            </div>
                        </div>
                    </a>
                </div>
            @empty
                <p>購入履歴はありません。</p>
            @endforelse
        </div>
    @endif
</div>
@endsection

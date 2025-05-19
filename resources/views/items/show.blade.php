@extends('layouts.app')

@section('title', '商品詳細')

@section('head')
    <link rel="stylesheet" href="{{ asset('css/show.css') }}">
@endsection

@section('content')
<div class="container mt-5">
    <div class="row">
        {{-- 左：商品画像 --}}
        <div class="col-md-6 position-relative d-flex justify-content-center align-items-start">
            <div class="image-wrapper position-relative" style="width: 80%;">
                <img src="{{ $item->image_url }}" alt="商品画像" class="img-fluid border" style="max-width: 80%; height: auto; object-fit: cover;">
                @if($item->is_sold)
                    <span class="badge bg-danger position-absolute top-0 start-0 m-2">SOLD</span>
                @endif
            </div>
        </div>
        {{-- 右：商品情報 --}}
        <div class="col-md-6">
            <h2 class="fw-bold">{{ $item->title }}</h2>
            <p class="text-muted">ブランド名: {{ $item->brand_name ?? '未設定' }}</p>
            <h4 class="text-danger">¥{{ number_format($item->price) }} <small class="text-muted">(税込)</small></h4>

            <div class="d-flex align-items-center my-3">
                @auth
                {{-- ログイン時：押せるボタン --}}
                <button
                    class="btn btn-like-toggle p-0 border-0 bg-transparent me-3"
                    data-item-id="{{ $item->id }}"
                    data-liked="{{ $item->likes->contains('user_id', auth()->id()) ? 'true' : 'false' }}"
                >
                    @if ($item->likes->contains('user_id', auth()->id()))
                        <i class="bi bi-star-fill text-warning"></i>
                    @else
                        <i class="bi bi-star-fill text-secondary"></i>
                    @endif
                        <span class="like-count ms-1">{{ $item->likes->count() }}</span>
                </button>
                @else
                    {{-- 未ログイン時：見た目だけで押せない --}}
                    <span class="me-3">
                        <i class="bi-star-fill text-secondary"></i>
                        <span class="like-count ms-1">{{ $item->likes->count() }}</span>
                    </span>
                @endauth

                {{-- コメント数 --}}
                <span>💬 {{ $item->comments->count() }}</span>
            </div>

            @if (!$item->is_sold)
                @if ($item->user_id !== Auth::id())
                    <a href="{{ route('checkout', ['item_id' => $item->id]) }}" class="btn btn-danger w-100 mb-4">購入手続きへ</a>
                @else
                    <p class="text-muted">※これはあなたが出品した商品です</p>
                @endif
            @else
                <p class="text-danger fw-bold">この商品は売り切れです。</p>
            @endif

            <h5>商品説明</h5>
            <p>{{ $item->description }}</p>

            <h5>商品情報</h5>
            <p>
                カテゴリー:
                @foreach ($item->categories as $category)
                    <span class="badge bg-secondary">{{ $category->name }}</span>
                @endforeach
            </p>
            <p><strong>商品の状態:</strong> {{ $item->condition }}</p>
            <div>
                <h4>コメント ({{ $item->comments->count() }})</h4>
                @foreach ($item->comments as $comment)
                    <div class="rounded p-2 mb-2 d-flex align-items-start">
                        <img src="{{$comment->user->image_url}}" alt="プロフィール画像" class="rounded-circle me-2" style="width: 40px; height: 40px; object-fit: cover;">
                        <div class="rounded p-2 mb-2">
                            <strong>{{ $comment->user->name }}</strong>
                            <p class="mb-0">{{ $comment->comment }}</p>
                        </div>
                    </div>
                @endforeach

                @auth
                    <form action="{{ route('item.comment', $item->id) }}" method="POST" class="mt-3">
                        @csrf
                        <div class="mb-3">
                            <textarea name="comment" class="form-control" rows="3" placeholder="商品のコメントを入力"></textarea>
                            @error('comment')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <button type="submit" class="btn btn-danger w-100">コメントを送信する</button>
                    </form>
                @endauth
            </div>
        </div>
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const likeButtons = document.querySelectorAll('.btn-like-toggle');

        likeButtons.forEach(button => {
            button.addEventListener('click', async function () {
                const itemId = this.dataset.itemId;
                const liked = this.dataset.liked === 'true';
                const url = liked ? `/item/${itemId}/unlike` : `/item/${itemId}/like`;

                const res = await fetch(url, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json',
                    },
                });

                if (res.ok) {
                    this.dataset.liked = liked ? 'false' : 'true';

                    const icon = this.querySelector('i');
                    // まず一旦 class を全部消す
                    icon.className = 'bi';

                    // 押した直後（いいね追加 or 取り消し）にクラスを再構築
                    if (this.dataset.liked === 'true') {
                        icon.classList.add('bi-star-fill', 'text-warning');
                    } else {
                        icon.classList.add('bi-star-fill', 'text-secondary');
                    }

                    const countSpan = this.querySelector('.like-count');
                    const currentCount = parseInt(countSpan.textContent);
                    countSpan.textContent = liked ? currentCount - 1 : currentCount + 1;
                } else {
                    alert('通信エラーが発生しました');
                }
            });
        });
    });
</script>
@endsection

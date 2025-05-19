@extends('layouts.app')

@section('title', 'プロフィール編集')

@section('content')
<div class="container mt-4">
    <h2>プロフィール編集</h2>
    <form action="/mypage/profile" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="mb-3">
            <label for="profile_image" class="form-label">プロフィール画像</label>
            <input type="file" name="profile_image" class="form-control">
            @error('profile_image')
            <div class="text-danger">{{ $message }}</div>
            @enderror
            @if ($user->profile_image)
                <div class="mt-2">
                    <img src="{{ $user->image_url }}" width="100" class="rounded-circle" alt="現在の画像">
                </div>
            @endif
        </div>
        <div class="mb-3">
            <label for="name" class="form-label">ユーザー名</label>
            <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}">
            @error('name')
            <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3">
            <label for="postal_code" class="form-label">郵便番号</label>
            <input type="text" name="postal_code" class="form-control" value="{{ old('postal_code', $address->postal_code ?? '') }}">
            @error('postal_code')
            <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="address_line1" class="form-label">住所</label>
            <input type="text" name="address_line1" class="form-control" value="{{ old('address_line1', $address->address_line1 ?? '') }}">
            @error('address_line1')
            <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="address_line2" class="form-label">建物名・部屋番号</label>
            <input type="text" name="address_line2" class="form-control" value="{{ old('address_line2', $address->address_line2 ?? '') }}">
            @error('address_line2')
            <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
        <button type="submit" class="btn btn-danger w-100">更新する</button>
    </form>
</div>
@endsection

@extends('layouts.app')

@section('title', '住所変更')

@section('content')
<div class="container-sm mt-5">
    <h2 class="fw-bold text-center mb-4">住所の変更</h2>

    <form action="/purchase/address/{{ $item_id }}" method="POST">
        @csrf

        <div class="mb-3">
            <label for="postal_code" class="form-label">郵便番号</label>
            <input type="text" name="postal_code" id="postal_code" class="form-control" value="{{ old('postal_code', $address->postal_code ?? '') }}" placeholder="例: 123-4567" required>
            @error('postal_code')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="address_line1" class="form-label">住所</label>
            <input type="text" name="address_line1" id="address_line1" class="form-control" value="{{ old('address_line1', $address->address_line1 ?? '') }}" placeholder="例: 東京都渋谷区◯◯1-2-3" required>
            @error('address_line1')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="address_line2" class="form-label">建物名・部屋番号</label>
            <input type="text" name="address_line2" id="address_line2" class="form-control" value="{{ old('address_line2', $address->address_line2 ?? '') }}" placeholder="例: コーチテックマンション101号室">
            @error('address_line2')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn btn-danger w-100 mt-3">更新する</button>
    </form>
</div>
@endsection

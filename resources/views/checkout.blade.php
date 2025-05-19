@extends('layouts.app')

@section('title', '決済確認')

@section('content')
<div class="container mt-5">
    <h2 class="fw-bold mb-4">決済確認</h2>
    <div class="row">
        {{-- 左側：商品情報・支払い方法・配送先 --}}
        <div class="col-md-8">
            <div class="d-flex mb-4">
                <img src="{{ $item->image_url }}" alt="商品画像" class="me-3" style="width: 120px; height: 120px; object-fit: cover;">
                <div>
                    <h4>{{ $item->title }}</h4>
                    <p class="text-danger fs-5">¥{{ number_format($item->price) }}</p>
                </div>
            </div>

            <form action="{{ route('payment') }}" method="POST">
                @csrf
                <input type="hidden" name="item_id" value="{{ $item->id }}">

                {{-- 支払い方法 --}}
                <div class="mb-4">
                    <label for="payment_method" class="form-label">支払い方法</label>
                    <select name="payment_method" id="payment_method" class="form-select">
                        <option value="">選択してください</option>
                        <option value="クレジットカード" {{ old('payment_method', Auth::user()->payment_method) == 'クレジットカード' ? 'selected' : '' }}>クレジットカード</option>
                        <option value="コンビニ払い" {{ old('payment_method', Auth::user()->payment_method) == 'コンビニ払い' ? 'selected' : '' }}>コンビニ払い</option>
                    </select>
                    @error('payment_method')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                {{-- 配送先 --}}
                <div class="mb-4">
                    <label class="form-label">配送先</label>
                    @if($address)
                        <p>〒{{ $address->postal_code }}<br>{{ $address->address_line1 }} {{ $address->address_line2 }}</p>
                        <a href="/purchase/address/{{ $item->id }}" class="text-primary">変更する</a>
                    @else
                        <p>住所情報が登録されていません。</p>
                        <a href="/purchase/address/{{ $item->id }}" class="text-primary">住所を登録する</a>
                    @endif
                </div>
        </div>

        {{-- 右側：確認表と購入ボタン --}}
        <div class="col-md-4">
            <table class="table mb-4">
                <tr>
                    <th>商品代金</th>
                    <td class="text-end">¥{{ number_format($item->price) }}</td>
                </tr>
                <tr>
                    <th>支払い方法</th>
                    <td class="text-end" id="selected-payment">選択なし</td>
                </tr>
            </table>
            <button type="submit" class="btn btn-danger w-100">購入する</button>
            </form>
        </div>
    </div>
</div>

<script>
    // 選択された支払い方法を表に反映
    document.getElementById('payment_method').addEventListener('change', function () {
        document.getElementById('selected-payment').textContent = this.value || '選択なし';
    });
</script>
@endsection

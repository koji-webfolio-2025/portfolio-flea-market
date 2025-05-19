@extends('layouts.app')

@section('title', '商品出品')

@section('content')
<div class="container-sm mt-4">
    <h2>商品の出品</h2>
    <form action="/sell" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="mb-3">
            <label for="image_url" class="form-label">商品画像</label>
            <input type="file" name="image_url" class="form-control" id="imageInput">
            @error('image_url')
                <div class="text-danger">{{ $message }}</div>
            @enderror
            {{-- プレビューエリア --}}
            <div class="mt-3">
                <img id="preview" src="#" alt="プレビュー" class="img-thumbnail" style="width: 400px; height: 400px; object-fit: cover; display: none;">
            </div>
        </div>
        <div class="mb-3">
            <label class="form-label">カテゴリー（複数選択可）</label>
            <div class="d-flex flex-wrap gap-2">
            @foreach ($categories as $category)
                <input type="checkbox" class="btn-check" id="category{{ $category->id }}" name="categories[]" value="{{ $category->id }}">
                <label class="btn btn-outline-danger" for="category{{ $category->id }}">{{ $category->name }}</label>
            @endforeach
            </div>
            @error('categories')
                <div class="text-danger mt-2">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3">
            <label for="condition" class="form-label">商品の状態</label>
            <select name="condition" class="form-select">
                <option value="">選択してください</option>
                <option value="新品・未使用">新品・未使用</option>
                <option value="目立った傷や汚れなし">目立った傷や汚れなし</option>
                <option value="やや傷や汚れあり">やや傷や汚れあり</option>
            </select>
            @error('condition')
                <div class="text-danger mt-2">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3">
            <label for="title" class="form-label">商品名</label>
            <input type="text" name="title" class="form-control">
            @error('title')
                <div class="text-danger mt-2">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3">
            <label for="brand_name" class="form-label">ブランド名</label>
            <input type="text" name="brand_name" class="form-control">
        </div>
        <div class="mb-3">
            <label for="description" class="form-label">商品説明</label>
            <textarea name="description" rows="4" class="form-control"></textarea>
            @error('description')
                <div class="text-danger mt-2">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3">
            <label for="price" class="form-label">販売価格（円）</label>
            <div class="input-group">
                <span class="input-group-text">¥</span>
                <input type="number" name="price" class="form-control" min="0">
            </div>
            @error('price')
                <div class="text-danger mt-2">{{ $message }}</div>
            @enderror
        </div>
        <button type="submit" class="btn btn-danger w-100">出品する</button>
    </form>
</div>
@endsection
@section('scripts')
<script>
    document.getElementById('imageInput').addEventListener('change', function(event) {
        const preview = document.getElementById('preview');
        const file = event.target.files[0];

        if (file) {
            const reader = new FileReader();

            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.style.display = 'block';
            };

            reader.readAsDataURL(file);
        } else {
            preview.src = '#';
            preview.style.display = 'none';
        }
    });
</script>
@endsection

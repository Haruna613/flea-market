@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/mypage-profile.css') }}">
@endsection

@section('content')
<div class="main__inner">
    <form action="{{ route('profile.settings.update') }}" method="POST" enctype="multipart/form-data">
    @csrf
        <h2 class="main__inner-title">
            プロフィール設定
        </h2>
        <div class="main__content">
            <div class="main__content-item">
                <div class="image-preview" id="image-preview-area">
                    <img class="image-preview__img" src="{{ Auth::user()->profile_image_path ? Storage::url(Auth::user()->profile_image_path) : '' }}" id="preview-image" alt="プロフィール画像" style="display: {{ Auth::user()->profile_image_path ? 'block' : 'none' }};">
                </div>
                <div class="image-preview">
                    <input type="file" name="profile_image" id="profile_image" accept="image/jpeg, image/png" style="display: none;">
                    <label for="profile_image" class="image-preview__label">
                        画像を選択する
                    </label>
                </div>
            </div>
            <div class="main__content-item">
                <label class="item__name" for="username">
                    ユーザー名
                </label>
                <input class="item__input" type="text" name="username" id="username" value="{{ old('username', Auth::user()->username ?? Auth::user()->name) }}">
            </div>
            <div class="form__error">
                @error('username')
                {{ $message }}
                @enderror
            </div>
            <div class="main__content-item">
                <label for="postal_code" class="item__name">
                    郵便番号
                </label>
                <input class="item__input" type="text" name="postal_code" id="postal_code" value="{{ old('postal_code', Auth::user()->postal_code) }}">
            </div>
            <div class="form__error">
                @error('postal_code')
                {{ $message }}
                @enderror
            </div>
            <div class="main__content-item">
                <label for="address" class="item__name">
                    住所
                </label>
                <input class="item__input" type="text" name="address" id="address" value="{{ old('address', Auth::user()->address) }}">
            </div>
            <div class="form__error">
                @error('address')
                {{ $message }}
                @enderror
            </div>
            <div class="main__content-item">
                <label for="building_name" class="item__name">
                    建物名
                </label>
                <input class="item__input" type="text" name="building_name" id="building_name" value="{{ old('building_name', Auth::user()->building_name) }}">
            </div>
            <div class="form__error">
                @error('building_name')
                {{ $message }}
                @enderror
            </div>
            <div class="form__button">
                <button class="form__button-submit" type="submit">
                    更新する
                </button>
            </div>
        </div>
    </form>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        $('#profile_image').on('change', function(e) {
            var reader = new FileReader();
            reader.onload = function(e) {
                $('#preview-image').attr('src', e.target.result).show();
                $('#preview-text').hide();
            }
            reader.readAsDataURL(e.target.files[0]);
        });
    });
</script>
@endsection
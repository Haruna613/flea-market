@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/mypage.css') }}">
@endsection

@section('content')
<div class="main__inner">
    <div class="user">
        <div class="user__image-preview" id="image-preview-area">
            <img class="user__image-preview__img"
                    src="{{ Auth::user()->profile_image_path ? Storage::url(Auth::user()->profile_image_path) : '' }}"
                    id="preview-image"
                    alt="プロフィール画像"
                    style="display: {{ Auth::user()->profile_image_path ? 'block' : 'none' }};">
        </div>
        <div class="user__name">
            <p>{{ Auth::user()->username }}</p>
        </div>
        <div class="user__profile-update">
            <a class="update__link" href="/mypage/profile">
                プロフィールを編集
            </a>
        </div>
    </div>
    <div class="tabs">
        <a href="/mypage?page=sell" class="tab-button @if(request('page') == 'sell' || !request('page')) is-active @endif">
            出品した商品
        </a>
        <a href="/mypage?page=buy" class="tab-button @if(request('page') == 'buy') is-active @endif">
            購入した商品
        </a>
    </div>
    @if(request('page') == 'sell')
    <div id="listed" class="tab-content">
        @if($listedItems->isEmpty())
        <p class="tab-content__message">
            出品した商品はありません
        </p>
        @else
        <ul class="tab-content__item">
            @foreach($listedItems as $item)
            <li class="tab-content__item-list">
                @if($item->image_path)
                    @if (Str::startsWith($item->image_path, 'https://'))
                    <img class="tab-content__item--img" src="{{ $item->image_path }}" alt="{{ $item->name }}" width="100">
                    @else
                    <img class="tab-content__item--img" src="{{ Storage::url($item->image_path) }}" alt="{{ $item->name }}" width="100">
                    @endif
                @endif
                <p>{{ $item->name }}</p>
            </li>
            @endforeach
        </ul>
        @endif
    </div>
    @endif
    @if(request('page') == 'buy' || !request('page'))
    <div id="purchased" class="tab-content">
        @if($purchasedItems->isEmpty())
        <p class="tab-content__message">
            購入した商品はありません
        </p>
        @else
        <ul class="tab-content__item">
            @foreach($purchasedItems as $item)
            <li class="tab-content__item-list">
                @if($item->image_path)
                    @if (Str::startsWith($item->image_path, 'https://'))
                    <img class="tab-content__item--img" src="{{ $item->image_path }}" alt="{{ $item->name }}" width="100">
                    @else
                    <img class="tab-content__item--img" src="{{ Storage::url($item->image_path) }}" alt="{{ $item->name }}" width="100">
                    @endif
                @endif
                <p>{{ $item->name }}</p>
            </li>
            @endforeach
        </ul>
        @endif
    </div>
    @endif
</div>
@endsection
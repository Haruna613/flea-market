@extends('layouts.app')

@php
use Illuminate\Support\Str;
@endphp

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
            <p class="user__name-text">{{ Auth::user()->name }}</p>
            @php $avgRating = Auth::user()->averageRating(); @endphp
            @if($avgRating)
                <div class="user__rating">
                    <span class="stars">
                        @for ($i = 1; $i <= 5; $i++)
                            @if ($i <= $avgRating)
                                <span class="star-filled" style="color: #ffca08;">★</span>
                            @else
                                <span class="star-empty" style="color: #ccc;">★</span>
                            @endif
                        @endfor
                    </span>
                </div>
            @endif
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
        <a href="/mypage?page=trade" class="tab-button @if(request('page') == 'trade') is-active @endif">
            取引中の商品
            @if($unreadCount > 0)
                <span class="unread-badge" style="margin-left: 10px; background-color: #ff0000; color: white; padding: 4px 6px; border-radius: 50%; font-size: 14px;">{{ $unreadCount }}</span>
            @endif
        </a>
    </div>
    @if(request('page') == 'sell'|| !request('page'))
    <div id="listed" class="tab-content">
        @if($listedItems->isEmpty())
        <p class="tab-content__message">
            出品した商品はありません
        </p>
        @else
        <ul class="tab-content__item">
            @foreach($listedItems as $item)
            <li class="tab-content__item-list">
                <a href="{{ route('item.detail', ['item_id' => $item->id]) }}" style="text-decoration: none;">
                    <div class="item-image-wrapper">
                        @if($item->image_path)
                        <img class="tab-content__item--img" src="{{ Str::startsWith($item->image_path, 'https://') ? $item->image_path : Storage::url($item->image_path) }}" alt="{{ $item->name }}">
                        @endif
                        @if($item->isSold())
                        <div class="sold-label"><span>SOLD</span></div>
                        @endif
                    </div>
                    <p class="tab-content__item-name">{{ $item->name }}</p>
                </a>
            </li>
            @endforeach
        </ul>
        @endif
    </div>
    @endif
    @if(request('page') == 'buy')
    <div id="purchased" class="tab-content">
        @if($purchasedItems->isEmpty())
        <p class="tab-content__message">
            購入した商品はありません
        </p>
        @else
        <ul class="tab-content__item">
            @foreach($purchasedItems as $item)
            <li class="tab-content__item-list">
                <a href="{{ route('item.detail', ['item_id' => $item->id]) }}" style="text-decoration: none;">
                    <div class="item-image-wrapper">
                        @if($item->image_path)
                        <img class="tab-content__item--img" src="{{ Str::startsWith($item->image_path, 'https://') ? $item->image_path : Storage::url($item->image_path) }}" alt="{{ $item->name }}">
                        @endif
                        <div class="sold-label"><span>SOLD</span></div>
                    </div>
                    <p class="tab-content__item-name">{{ $item->name }}</p>
                </a>
            </li>
            @endforeach
        </ul>
        @endif
    </div>
    @endif
    @if(request('page') == 'trade')
    <div id="trading" class="tab-content">
        @if($tradingItems->isEmpty())
        <p class="tab-content__message">
            取引中の商品はありません
        </p>
        @else
        <ul class="tab-content__item">
            @foreach($tradingItems as $item)
            <li class="tab-content__item-list">
                <a href="{{ route('chat.show', ['item_id' => $item->id]) }}" style="text-decoration: none;">
                    <div class="item-image-wrapper">
                        @if($item->image_path)
                        <img class="tab-content__item--img" src="{{ Str::startsWith($item->image_path, 'https://') ? $item->image_path : Storage::url($item->image_path) }}" alt="{{ $item->name }}">
                        @endif
                        @if($item->individual_unread_count > 0)
                            <span class="unread-badge-individual">
                                {{ $item->individual_unread_count }}
                            </span>
                        @endif
                    </div>
                    <p class="tab-content__item-name">{{ $item->name }}</p>
                </a>
            </li>
            @endforeach
        </ul>
        @endif
    </div>
    @endif
</div>
@endsection
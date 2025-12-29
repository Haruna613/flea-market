@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/index.css') }}">
@endsection

@section('content')
<div class="tabs">
    <a href="/?tab=all{{ $keyword ? '&keyword=' . $keyword : '' }}"
    class="tab-button @if(!$currentTab || $currentTab === 'all') is-active @endif">
        おすすめ
    </a>
    <a href="/?tab=mylist{{ $keyword ? '&keyword=' . $keyword : '' }}"
    class="tab-button @if($currentTab === 'mylist') is-active @endif">
        マイリスト
    </a>
</div>
<div class="item">
    @foreach($items as $item)
    <div class="item__img">
        <a class="tab-content" href="{{ route('item.detail', ['item_id' => $item->id]) }}">
            <li class="tab-content__item-list">
                <div class="tab-content__item-list-inner">
                    <div class="item-image-wrapper">
                        @if($item->image_path)
                            @if (Str::startsWith($item->image_path, 'https://'))
                            <img class="tab-content__item--img" src="{{ $item->image_path }}" alt="{{ $item->name }}">
                            @else
                            <img class="tab-content__item--img" src="{{ Storage::url($item->image_path) }}" alt="{{ $item->name }}">
                            @endif
                        @endif

                        @if($item->isSold())
                        <div class="sold-label">
                            <span>SOLD</span>
                        </div>
                        @endif
                    </div>
                </div>
                <p class="tab-content__item-name">
                    {{ $item->name }}
                </p>
            </li>
        </a>
    </div>
    @endforeach
</div>
@endsection
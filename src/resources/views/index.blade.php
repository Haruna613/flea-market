@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/index.css') }}">
    @if($currentTab)
    <input type="hidden" name="tab" value="{{ $currentTab }}">
    @endif
@endsection

@section('content')
<div class="tabs">
    <a href="{{ route('top', ['tab' => 'all', 'keyword' => $keyword]) }}" class="tab-button @if(!$currentTab || $currentTab === 'all') is-active @endif">
        おすすめ
    </a>
    @if (Auth::check())
    <a href="{{ route('top', ['tab' => 'mylist', 'keyword' => $keyword]) }}" class="tab-button @if($currentTab === 'mylist') is-active @endif">
        マイリスト
    </a>
    @endif
</div>
<div class="item">
    @foreach($items as $item)
    <div class="item__img">
        <a class="tab-content" href="{{ route('item.detail', ['item_id' => $item->id]) }}">
            <li class="tab-content__item-list">
                @if($item->image_path)
                    @if (Str::startsWith($item->image_path, 'https://'))
                    <img class="tab-content__item-img" src="{{ $item->image_path }}" alt="{{ $item->name }}" width="100">
                    @else
                    <img class="tab-content__item-img" src="{{ Storage::url($item->image_path) }}" alt="{{ $item->name }}" width="100">
                    @endif
                @endif
                <p class="tab-content__item-name">
                    {{ $item->name }}
                </p>
            </li>
        </a>
    </div>
    @endforeach
</div>
@endsection
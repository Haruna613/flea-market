@extends('layouts.app')

@php
use Illuminate\Support\Str;
@endphp


@section('css')
<link rel="stylesheet" href="{{ asset('css/purchase-address.css') }}">
@endsection

@section('content')

<form action="{{ route('purchase.address.update', ['item_id' => $item->id]) }}" method="POST">
@csrf
    <div class="form__inner">
        <div class="form__inner-header">
            <h2 class="form__inner-header--title">
                住所の変更
            </h2>
        </div>
        <div class="form__inner-item">
            <span class="form__inner-item--title">郵便番号</span>
            <input class="form__inner-item--input" type="text" name="postal_code" value="{{ old('postal_code', $shippingInfo['postal_code'] ?? '') }}">
        </div>
        <div class="error-message">
            @error('postal_code')
                {{ $message }}
            @enderror
        </div>
        <div class="form__inner-item">
            <span class="form__inner-item--title">住所</span>
            <input class="form__inner-item--input" type="text" name="address" value="{{ old('address', $shippingInfo['address'] ?? '') }}">
        </div>
        <div class="error-message">
            @error('address')
                {{ $message }}
            @enderror
        </div>
        <div class="form__inner-item">
            <span class="form__inner-item--title">建物名</span>
            <input class="form__inner-item--input" type="text" name="building_name" value="{{ old('building_name', $shippingInfo['building_name'] ?? '') }}">
        </div>
        <div class="form__button">
            <button class="form__button-submit" type="submit">更新する</button>
        </div>
    </div>
</form>
@endsection
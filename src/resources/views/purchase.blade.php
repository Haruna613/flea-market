@extends('layouts.app')

@php
use Illuminate\Support\Str;
@endphp

@section('css')
<link rel="stylesheet" href="{{ asset('css/purchase.css') }}">
@endsection

@section('content')
<form id="purchase-form">
@csrf
    <div class="purchase-form">
        <div class="purchase-form__container">
            <div class="purchase-form__item">
                <div class="item-img">
                    @if($item->image_path)
                        @if (Str::startsWith($item->image_path, 'https://'))
                            <img class="item__img-visual" src="{{ $item->image_path }}" alt="{{ $item->name }}" width="100">
                        @else
                            <img class="item__img-visual" src="{{ Storage::url($item->image_path) }}" alt="{{ $item->name }}" width="100">
                        @endif
                    @endif
                </div>
                <div class="item-info">
                    <div class="item-info__name">
                        <p class="item-info__name-inner">{{ $item->name }}</p>
                    </div>
                    <div class="item-info__price">
                        <span class="item-info__price-currency-symbol">¥</span>
                        <span class="item-info__price-inner">{{ number_format($item->price) }}</span>
                    </div>
                </div>
            </div>
            <div class="purchase-form__item">
                <h2 class="item-payment__title">支払い方法</h2>
                <select  class="item-payment__method" name="payment_method" id="payment-method-select">
                    <option value="" disabled selected>選択してください</option>
                    <option value="convenience">コンビニ払い</option>
                    <option value="card">カード支払い</option>
                </select>
                <div class="error-message" id="payment-method-error">
                    @error('payment_method')
                        {{ $message }}
                    @enderror
                </div>
            </div>
            <div class="purchase-form__item">
                <div class="item-shipping__header">
                    <h2 class="item-shipping__title">配送先</h2>
                    <a  class="item-shipping__update" href="{{ route('purchase.address.show', ['item_id' => $item->id]) }}">変更する</a>
                </div>
                <div class="item-shipping__inner">
                    <div class="item-shipping__inner-postal-code">
                        <span class="item-shipping__post-symbol">〒</span>
                        <p class="item-shipping__info">{{ $shippingInfo['postal_code'] }}</p>
                    </div>
                    <div class="item-shipping__inner-address">
                        <p class="item-shipping__info">{{ $shippingInfo['address'] }}</p>
                        <p class="item-shipping__info">{{ $shippingInfo['building_name'] }}</p>
                    </div>
                </div>
            </div>
            <div style="display: none;">
                <input type="hidden" name="postal_code" value="{{ $shippingInfo['postal_code'] ?? '' }}">
                <input type="hidden" name="address" value="{{ $shippingInfo['address'] ?? '' }}">
                <input type="hidden" name="building_name" value="{{ $shippingInfo['building_name'] ?? '' }}">
            </div>
            <div class="error-message" id="address-error">
                @error('postal_code')
                    {{ $message }}
                @enderror
                @error('address')
                    {{ $message }}
                @enderror
            </div>
        </div>
        <div class="purchase-form__container">
            <div class="purchase-form__detail">
                <div class="purchase-form__detail-price">
                    <p class="detail__price-title">商品代金</p>
                </div>
                <div class="purchase-form__detail-price">
                    <span class="detail__price-item">¥</span>
                    <span class="detail__price-item">{{ number_format($item->price) }}</span>
                </div>
            </div>
            <div class="purchase-form__detail">
                <div class="purchase-form__detail-shipping">
                    <p class="detail__shipping-title">支払い方法</p>
                </div>
                <div class="purchase-form__detail-shipping">
                    <span class="detail__shipping-item" id="selected-payment-method-display">コンビニ払い</span>
                </div>
            </div>
            <div class="purchase-form__button">
                @if($item->isSold())
                    <p style="color: red; text-align: center;">この商品は既に売り切れました。</p>
                    <button type="button" class="purchase-form__button-inner" disabled style="background-color: #888;">
                        売り切れ
                    </button>
                @else
                    <button id="checkout-button" type="button" class="purchase-form__button-inner">
                        購入する
                    </button>
                @endif
            </div>
        </div>
    </div>
</form>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {

        $('#payment-method-select').on('change', function() {
            var selectedText = $('#payment-method-select option:selected').text();
            $('#selected-payment-method-display').text(selectedText);
        });

    });
</script>
<script src="https://js.stripe.com/v3/"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const stripe = Stripe("{{ config('services.stripe.key') }}");
    const checkoutButton = document.getElementById('checkout-button');

    checkoutButton.addEventListener('click', function () {
        const paymentMethod = document.getElementById('payment-method-select').value;

        $('.error-message').text('');

        fetch("{{ route('purchase.create-checkout-session', $item->id) }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": "{{ csrf_token() }}",
                "Accept": "application/json",
            },
            body: JSON.stringify({
                payment_method: paymentMethod,
                postal_code: "{{ $shippingInfo['postal_code'] ?? '' }}",
                address: "{{ $shippingInfo['address'] ?? '' }}",
                building_name: "{{ $shippingInfo['building_name'] ?? '' }}"
            }),
        })
        .then(async res => {
            const data = await res.json();

            if (res.status === 422) {
                if (data.errors) {
                    if (data.errors.payment_method) {
                        $('#payment-method-error').text(data.errors.payment_method[0]);
                    }
                    if (data.errors.postal_code || data.errors.address) {
                        $('#address-error').text('配送先情報を設定してください');
                    }
                }
                return;
            }

            if (!res.ok) {
                alert(data.error || 'エラーが発生しました');
                return;
            }

            return stripe.redirectToCheckout({ sessionId: data.id });
        })
        .catch(err => console.error('Error:', err));
    });
});
</script>

@endsection
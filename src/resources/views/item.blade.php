@extends('layouts.app')

@php
use Illuminate\Support\Str;
@endphp


@section('css')
<link rel="stylesheet" href="{{ asset('css/item.css') }}">
@endsection

@section('content')
<div class="item">
    <div class="item__img">
    @if($item->image_path)
        @if (Str::startsWith($item->image_path, 'https://'))
            <img class="item__img-visual" src="{{ $item->image_path }}" alt="{{ $item->name }}" width="100">
        @else
            <img class="item__img-visual" src="{{ Storage::url($item->image_path) }}" alt="{{ $item->name }}" width="100">
        @endif
    @endif
    </div>
    <div class="item__info">
        <p class="item__info-name">
            {{ $item->name }}
        </p>
        <p class="item__info-brand-name">
            {{ $item->brand_name }}
        </p>
        <p class="item__info-price">
            <span class="currency-symbol">¥</span>
            <span class="price-value">{{ number_format($item->price) }}</span>
            <span class="tax-info">(税込)</span>
        </p>
        <div class="item__actions">
            <div class="item__action--like">
            @php
                $isLiked = $item->isLikedBy(Auth::id());
            @endphp
                <button class="like-button" data-item-id="{{ $item->id }}">
                @if ($isLiked)
                    <img class="like-icon" id="like-icon" src="{{ asset('images/ハートロゴ_ピンク.png') }}" alt="いいね解除">
                @else
                    <img class="like-icon" id="like-icon" src="{{ asset('images/ハートロゴ_デフォルト.png') }}" alt="いいね">
                @endif
                </button>
                <span id="like-count">
                    {{ $item->likes_count ?? 0 }}
                </span>
            </div>
            <div class="item__action--comment">
                <img class="comment-icon" src="{{ asset('images/ふきだしロゴ.png') }}" alt="コメント">
                <span id="comment-count">
                    {{ $item->comments_count ?? 0 }}
                </span>
            </div>
        </div>
        <div class="item__purchase">
            @if($item->isSold())
                <button class="item__purchase-button item__purchase-button--sold" disabled style="background-color: #888; cursor: not-allowed;">
                    SOLD OUT
                </button>
            @else
                <a class="item__purchase-button" href="/purchase/{{ $item->id }}">
                    購入手続きへ
                </a>
            @endif
        </div>
        <div class="item__details">
            <h2 class="item__details-title">
                商品説明
            </h2>
            <p class="item__details-description">{{ $item->description }}</p>
            <h2 class="item__details-title">
                商品の情報
            </h2>
            <div  class="item__details-content">
                <span class="item__details-content--title">
                    カテゴリー
                </span>
                <p class="item__details-content--categories">
                @foreach($item->categories as $category)
                    <span class="category-tag">
                        {{ $category->name }}
                    </span>
                @endforeach
                </p>
            </div>
            <div  class="item__details-content">
                <span class="item__details-content--title">
                    商品の状態
                </span>
                <p class="item__details-content--condition">
                    {{ $item->condition->name ?? '設定なし' }}
                </p>
            </div>
        </div>
        <div class="item__comments">
            <div class="item__comments-header">
                <span class="item__comments-header--inner">
                    コメント({{ $item->comments_count ?? 0 }})
                </span>
            </div>
            <div class="item__comments-latest">
            @if($item->latestComment)
                <div class="item__comments-latest--inner">
                    <div class="comment-user-info">
                        <img class="comment-user-img" src="{{ $item->latestComment->user->profile_image_path ? Storage::url($item->latestComment->user->profile_image_path) : asset('images/default-icon.png') }}" alt="ユーザーアイコン">
                        <span class="comment-username">
                            {{ $item->latestComment->user->name }}
                        </span>
                    </div>
                    <p class="comment-body">
                        {{ $item->latestComment->comment_body }}
                    </p>
                </div>
            @else
                <div class="item__comments-latest--inner">
                    <p>まだコメントはありません</p>
                </div>
            @endif
            </div>
            <form class="form__comment" action="{{ route('item.comment.store', $item->id) }}" method="POST">
            @csrf
                <h3 class="form__comment-title">
                    商品へのコメント
                </h3>
                <textarea class="form__comment-textarea" name="comment_body" rows="4"></textarea>
                <div class="form__comment-error">
                    @error('comment_body')
                        {{ $message }}
                    @enderror
                </div>
                <button class="form__comment-button" type="submit">
                    コメントを送信する
                </button>
            </form>
        </div>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        $('.like-button').on('click', function() {
            var itemId = $(this).data('item-id');
            var icon = $('#like-icon');
            var countSpan = $('#like-count');
            var currentCount = parseInt(countSpan.text());

            $.ajax({
                url: '/items/' + itemId + '/like',
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    var countSpan = $('#like-count');
                    var currentCount = parseInt(countSpan.text());

                    if (response.status === 'liked') {
                        icon.attr('src', '{{ asset('images/ハートロゴ_ピンク.png') }}');
                        countSpan.text(currentCount + 1);
                    } else if (response.status === 'unliked') {
                        icon.attr('src', '{{ asset('images/ハートロゴ_デフォルト.png') }}');
                        countSpan.text(currentCount - 1);
                    }
                },
                error: function(xhr) {
                    if (xhr.status === 401) {
                        window.location.href = '/login';
                    } else {
                        alert('いいね処理に失敗しました。');
                    }
                }
            });
        });
    });
</script>
@endsection
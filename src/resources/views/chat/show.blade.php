<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Flea-Market</title>
    <link rel="stylesheet" href="{{ asset('css/show.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}" />
</head>
<body>
    <header class="header">
        <div class="header__inner">
            <a href="/" class="header__inner-logo">
                <img class="header__inner-logo--img" src="{{ asset('images/COACHTECHヘッダーロゴ.png') }}" alt="サイトロゴ">
            </a>
        </div>
    </header>
    <main class="main">
        <div class="other-trades">
            <div class="other-trades__title">その他の取引</div>
            <div class="other-trades__list">
                @foreach($otherItems as $otherItem)
                    <a href="{{ route('chat.show', ['item_id' => $otherItem->id]) }}" class="trade-button">
                        <span class="trade-item-name">{{ $otherItem->name }}</span>
                    </a>
                @endforeach
            </div>
        </div>
        <div class="chat">
            <div class="chat-header">
                <h1 class="chat-header__title">
                    <div class="image-preview">
                        @if($isSeller)
                            <img class="image-preview__img" src="{{ $item->order->user->profile_image_path ? Storage::url($item->order->user->profile_image_path) : '' }}" id="preview-image" alt="" style="display: {{ $item->order->user->profile_image_path ? 'block' : 'none' }};">
                            <p class="image-preview__name">「{{ $item->order->user->name }}」さんとの取引画面</p>
                        @else
                            <img class="image-preview__img" src="{{ $item->user->profile_image_path ? Storage::url($item->user->profile_image_path) : '' }}" id="preview-image" alt="" style="display: {{ $item->user->profile_image_path ? 'block' : 'none' }};">
                            <p class="image-preview__name">「{{ $item->user->name }}」さんとの取引画面</p>
                        @endif
                    </div>
                </h1>
                <div class="chat-header__title-button">
                    @if($isBuyer && $item->order->status === 'pending')
                        <button type="button" onclick="openReviewModal()" class="btn-complete">取引を完了する</button>
                    @elseif($item->order->status === 'completed')
                        <span class="badge-completed">取引完了</span>
                    @endif
                </div>
                @php
                    $showAutoModal = ($isSeller && $item->order->status === 'awaiting_review');
                    $targetName = ($isBuyer) ? '出品者' : '購入者';
                @endphp
                <div id="reviewModal" class="modal" style="display: {{ $showAutoModal ? 'flex' : 'none' }};">
                    <div class="modal-content">
                        <p class="modal-title">取引が完了しました。</p>
                        <form action="{{ route('chat.review', $item->order->id) }}" method="POST">
                            @csrf
                            <p class="modal-subtitle">今回の取引相手はどうでしたか？</p>
                            <div class="star-rating">
                                @for ($i = 5; $i >= 1; $i--)
                                    <input type="radio" id="star{{ $i }}" name="rating" value="{{ $i }}" required>
                                    <label for="star{{ $i }}">★</label>
                                @endfor
                            </div>
                            <div class="modal-buttons">
                                <button type="submit" class="btn-submit">送信する</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="chat-item">
                <div class="chat-item__image">
                    @if($item->image_path)
                        <img class="chat-item__image-area" src="{{ Str::startsWith($item->image_path, 'https://') ? $item->image_path : Storage::url($item->image_path) }}" alt="{{ $item->name }}">
                    @endif
                </div>
                <div class="chat-item__info">
                    <p class="chat-item__info-name">{{ $item->name }}</p>
                    <p class="chat-item__info-price">¥{{ number_format($item->price) }}</p>
                </div>
            </div>
            <div class="chat-messages">
                @foreach($item->messages as $message)
                    <div class="message-row {{ $message->user_id === Auth::id() ? 'my-message' : 'other-message' }}">
                        <div class="content-wrapper">
                            <div class="user-info">
                                @if($message->user->profile_image_path)
                                    <img src="{{ Storage::url($message->user->profile_image_path) }}" class="user-icon">
                                @else
                                    <div class="user-icon-default"></div>
                                @endif
                                <p class="user-name">{{ $message->user->name }}</p>
                            </div>
                            <div id="display-{{ $message->id }}">
                                <div class="message-bubble">
                                    <div id="display-{{ $message->id }}">
                                        @if($message->image_path)
                                            <img src="{{ Storage::url($message->image_path) }}" class="chat-image">
                                        @endif
                                        <p class="message-body">{{ $message->message_body }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="message-time">
                                @if($message->user_id === Auth::id())
                                    <div id="edit-form-{{ $message->id }}" style="display: none;">
                                        <form action="{{ route('chat.update', $message->id) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <textarea name="message_body" class="edit-textarea">{{ $message->message_body }}</textarea>
                                            <div class="edit-buttons">
                                                <button type="submit" class="btn-update">更新</button>
                                                <button type="button" onclick="toggleEdit({{ $message->id }})" class="btn-cancel">キャンセル</button>
                                            </div>
                                        </form>
                                    </div>

                                    <div class="message-actions" id="actions-{{ $message->id }}">
                                        <button type="button" onclick="toggleEdit({{ $message->id }})" class="btn-action--edit">編集</button>
                                        <form action="{{ route('chat.destroy', $message->id) }}" method="POST"style="display: inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn-action--delete">削除</button>
                                        </form>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="chat-input-area">
                @error('message_body')
                    <p class="error-message">{{ $message }}</p>
                @enderror
                @error('image_path')
                    <div class="error-message">{{ $message }}</div>
                @enderror
                <form class="chat-input-area__form" action="{{ route('chat.store', $item->id) }}" method="POST" enctype="multipart/form-data" id="chat-form">
                    @csrf
                    <input class="chat-input-area__form-input" id="chat-input-{{ $item->id }}" type="text" name="message_body" placeholder="取引メッセージを記入してください" autocomplete="off">
                    <div class="chat-input-area__form-buttons">
                        <label for="image-upload" class="image-label">画像を追加</label>
                        <input type="file" name="image" id="image-upload" style="display:none;">
                        <button class="chat-submit-button" type="submit">
                            <img class="chat-submit-button--image" src="{{ asset('images/矢印ロゴ.png') }}" alt="矢印ロゴ">
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </main>
</body>
</html>
<script>
    function toggleEdit(messageId) {
        const displayDiv = document.getElementById(`display-${messageId}`);
        const editForm = document.getElementById(`edit-form-${messageId}`);
        const actionsDiv = document.getElementById(`actions-${messageId}`);

        if (editForm.style.display === "none") {
            displayDiv.style.display = "none";
            actionsDiv.style.display = "none";
            editForm.style.display = "block";
        } else {
            displayDiv.style.display = "block";
            actionsDiv.style.display = "block";
            editForm.style.display = "none";
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        const inputField = document.getElementById('chat-input-{{ $item->id }}');
        const storageKey = 'chat_draft_{{ $item->id }}';

        const savedDraft = localStorage.getItem(storageKey);
        if (savedDraft) {
            inputField.value = savedDraft;
        }

        inputField.addEventListener('input', function() {
            localStorage.setItem(storageKey, inputField.value);
        });

        const chatForm = document.getElementById('chat-form');
        chatForm.addEventListener('submit', function() {
            localStorage.removeItem(storageKey);
        });
    });

    function openReviewModal() {
        document.getElementById('reviewModal').style.display = 'flex';
    }
    function closeReviewModal() {
        document.getElementById('reviewModal').style.display = 'none';
    }

    window.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') closeReviewModal();
    });
</script>
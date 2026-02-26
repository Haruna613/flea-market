# flea-market

## 環境構築

**Docker ビルド**

1. `git clone git@github.com:Haruna613/flea-market.git`
2. DockerDesktop アプリを立ち上げる
3. `docker-compose up -d --build`

> _Mac の M1・M2 チップの PC の場合、`no matching manifest for linux/arm64/v8 in the manifest list entries`のメッセージが表示されビルドができないことがあります。
> エラーが発生する場合は、docker-compose.yml ファイルの「mysql」内に「platform」の項目を追加で記載してください_

```bash
mysql:
   platform: linux/amd64
   image: mysql:8.0.26
   environment:
```

**Laravel 環境構築**

1. `docker-compose exec php bash`
2. `composer install`
3. 「.env.example」ファイルを 「.env」ファイルに命名を変更。または、新しく.env ファイルを作成
4. .env に以下の環境変数を追加

```text
DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=laravel_db
DB_USERNAME=laravel_user
DB_PASSWORD=laravel_pass
```

> [!IMPORTANT] > **作業ディレクトリに関する注意**
> 本プロジェクトのソースコードは `src` ディレクトリ配下にあります。
> Docker コンテナ内での操作や `php artisan` コマンドは、プロジェクトルートに移動してから実行してください。

**Stripe 決済の設定**
商品の購入機能を利用するには、Stripe の API キーが必要です。
`.env` ファイルに以下の項目を追加し、ご自身のテスト用キーを設定してください。

```text
STRIPE_PUBLIC_KEY=pk_test_...
STRIPE_SECRET_KEY=sk_test_...
```

5. アプリケーションキーの作成

```bash
php artisan key:generate
```

6. マイグレーションの実行

```bash
php artisan migrate
```

7. ストレージのシンボリックリンク作成

```bash
php artisan storage:link
```

8. シーディングの実行

```bash
php artisan db:seed
```

## 使用技術(実行環境)

- PHP8.4.12
- Laravel8.75
- MySQL8.0.26

## ER 図

```mermaid
erDiagram
    users ||--o{ items : "出品する"
    users ||--o{ comments : "コメントする"
    users ||--o{ likes : "いいねする"
    users ||--o{ orders : "購入する"
    users ||--o{ messages : "メッセージを送る"
    users ||--o{ reviews : "評価するまたは評価される"

    items ||--o{ comments : "コメントがつく"
    items ||--o{ likes : "いいねされる"
    items ||--o{ orders : "注文される"
    items ||--o{ messages : "チャットが行われる"

    orders ||--o{ reviews : "評価がつく"

    conditions ||--o{ items : "商品の状態"

    items ||--o{ item_category : "所属"
    categories ||--o{ item_category : "所属"

    users {
        bigint id PK "NOT NULL"
        varchar name "NOT NULL"
        varchar email UK "NOT NULL"
        timestamp email_verified_at "NULL"
        varchar password "NOT NULL"
        tinyint profile_completed "NOT NULL"
        varchar postal_code "NULL"
        varchar address "NULL"
        varchar building_name "NULL"
        varchar profile_image_path "NULL"
        varchar remember_token "NULL"
        timestamp created_at
        timestamp updated_at
    }

    items {
        bigint id PK "NOT NULL"
        bigint user_id FK "NOT NULL"
        varchar name "NOT NULL"
        text description "NOT NULL"
        varchar image_path "NOT NULL"
        int price "NOT NULL"
        varchar brand_name "NULL"
        bigint condition_id FK "NOT NULL"
        timestamp created_at
        timestamp updated_at
    }

    categories {
        bigint id PK "NOT NULL"
        varchar name "NOT NULL"
        timestamp created_at
        timestamp updated_at
    }

    item_category {
        bigint id PK "NOT NULL"
        bigint item_id "UK, NOT NULL"
        bigint category_id FK "UK, NOT NULL"
        timestamp created_at
        timestamp updated_at
    }

    comments {
        bigint id PK "NOT NULL"
        bigint user_id FK "NOT NULL"
        bigint item_id FK "NOT NULL"
        text comment_body "NOT NULL"
        timestamp created_at
        timestamp updated_at
    }

    conditions {
        bigint id PK "NOT NULL"
        varchar name "NOT NULL"
        timestamp created_at
        timestamp updated_at
    }

    likes {
        bigint id PK "NOT NULL"
        bigint user_id "UK, NOT NULL"
        bigint item_id FK "UK, NOT NULL"
        timestamp created_at
        timestamp updated_at
    }

    orders {
        bigint id PK "NOT NULL"
        bigint user_id FK "NOT NULL"
        bigint item_id FK "NOT NULL"
        int price "NOT NULL"
        varchar status "NOT NULL"
        varchar shipping_address_line1 "NOT NULL"
        varchar shipping_building_name "NULL"
        varchar shipping_postal_code "NOT NULL"
        timestamp created_at
        timestamp updated_at
    }

    messages {
        bigint id PK "NOT NULL"
        bigint item_id FK "NOT NULL"
        bigint user_id FK "NOT NULL"
        text message_body "NOT NULL"
        timestamp read_at "NULL"
        varchar image_path "NULL"
        timestamp created_at
        timestamp updated_at
    }

    reviews {
        bigint id PK "NOT NULL"
        bigint order_id FK "NOT NULL"
        bigint reviewer_id FK "NOT NULL"
        bigint reviewee_id FK "NOT NULL"
        int rating "NOT NULL"
        text comment "NULL"
        timestamp created_at
        timestamp updated_at
    }
```

## URL

- 開発環境：http://localhost/
- phpMyAdmin:：http://localhost:8080/

## メール確認環境（MailHog）

本プロジェクトでは、メール送信テスト用に MailHog を導入しています。
会員登録時のメール認証、商品取引完了時の出品者への通知などの確認が可能です。

- **MailHog UI**: [http://localhost:8026/](http://localhost:8026/)

## テストの実行

Feature テストおよび Unit テストを実装しています。
以下のコマンドで全テストを実行し、品質を担保しています。

```bash
docker-compose exec php php artisan test
```

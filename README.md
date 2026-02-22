# COACHTECHフリマ

## 環境構築

#### 1. リポジトリをクローン
```bash
git clone git@github.com:neuro-chan/COACHTECH-Flea-Market.git
cd coachtech-flea-market
```

#### 2. 初期セットアップ
```bash
make init #プロジェクトルートで実行
```
`make init` では以下の処理が自動で実行されます
- Dockerイメージのビルド
- コンテナ起動
- .env（.env.example → .env）の配置
- Composerインストール
- アプリケーションキー生成
- DBのマイグレーション・初期データのシーディング
- ストレージのシンボリックリンク作成

#### トラブルシューティング
`make init` 実行時に `Access denied` エラーが発生した場合は、以下のコマンドでボリュームを削除してから再実行してください。
```bash
>docker compose down -v
>make init
```

&nbsp;
## MailHogの設定
必要な設定は `.env.example` に含まれているため、追加の設定は不要です。
Featureテストでは `Notification::fake()` を使用しているため、テスト実行時にMailHogは不要です。

&nbsp;
## Stripeの設定
ブラウザでの動作確認をする場合は `.env` に以下の設定が必要です。
テスト用APIキーは [Stripeダッシュボード](https://dashboard.stripe.com/test/apikeys) から取得してください。

```env
STRIPE_KEY=your_stripe_publishable_key
STRIPE_SECRET=your_stripe_secret_key
```
購入機能のFeatureテストではStripeをスキップして直接購入データを作成する方式としているため、テスト実行時にStripe APIキーは不要です。

&nbsp;
## 使用技術
- バックエンド：Laravel 12 / PHP 8.3
- フロントエンド：HTML/ CSS/ JavaScript
- データベース：MySQL 8.0
- 開発環境：Docker / Nginx / phpMyAdmin
- バージョン管理：Git / GitHub
- メール（開発環境）：MailHog
- テスト：PHPUnit（Featureテスト）

&nbsp;
## 動作確認用URL

- 動作確認URL: http://localhost/
- ログインページ: http://localhost/login
- ユーザー登録ページ: http://localhost/register
- Mailhog管理画面: http://localhost:8025/

&nbsp;
## ER図

&nbsp;
## Featureテスト（PHPUnit）
テストにはSQLiteのインメモリDBを使用します。
設定は `phpunit.xml` に定義されておりテスト時に自動で読み込まれます。
テストコードでは用途に応じて以下を使い分けています。

- **RefreshDatabase** : テストごとにDBをリセット
- **Factory** : テスト用ダミーデータの生成
- **Storage::fake()** : ファイルアップロードのテスト
- **Notification::fake()** : メール送信のテスト

---

### テスト実行方法

```bash
php artisan test
```

### 特定のファイルのみ実行

```bash
php artisan test tests/Feature/Http/Requests/RegisterRequestTest.php
```
---

### テストファイルの構成

```
tests/Feature/Http/
├── Auth/
│   ├── RegisterRequestTest.php       # ID1:  会員登録機能
│   ├── LoginRequestTest.php          # ID2:  ログイン機能
│   ├── LogoutTest.php                # ID3:  ログアウト機能
│   └── EmailVerificationTest.php     # ID16: メール認証機能
└── Controllers/
    ├── Item/
    │   ├── ItemControllerListTest.php  # ID4:  商品一覧取得
    │   │                               # ID5:  マイリスト一覧取得
    │   │                               # ID6:  商品検索機能
    │   ├── ItemControllerShowTest.php  # ID7:  商品詳細情報取得
    │   └── ItemControllerStoreTest.php # ID15: 出品商品情報登録
    ├── LikeControllerTest.php          # ID8:  いいね機能
    ├── CommentControllerTest.php       # ID9:  コメント送信機能
    ├── PurchaseControllerTest.php      # ID10: 商品購入機能
    ├── MypageControllerTest.php        # ID13: ユーザー情報取得
    └── ProfileControllerTest.php       # ID14: ユーザー情報変更
```

### 備考
テストID1:「全ての項目が入力されている場合、会員情報が登録され、プロフィール設定画面に遷移される」のテスト項目は実装と合わない処理なので、実装に合わせて「全ての項目が入力されている場合、会員情報が登録され、メール認証画面に遷移される」テストに変更しています。
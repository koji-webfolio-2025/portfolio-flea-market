# フリマアプリ（Blade/Laravel・Stripe決済対応）

## 概要
- Laravel（Blade）製のフリマアプリです。
- Stripeテスト決済に対応し、**商品購入フロー→SOLD反映→履歴管理**まで実装済み。
- 本番VPS（さくら）上で公開運用中。
- パーミッション地獄やキャッシュ・ログ・エラーハンドリングも含めて本番運用ノウハウを蓄積。

## デモURL
[https://frima.codeshift-lab.com](https://frima.codeshift-lab.com)

## 👤 テストアカウント（閲覧用）
- Email: user@example.com  
- Password: password123

## 技術スタック
- Laravel 8 / PHP 7.4
- Blade（SSR）
- MySQL 8.0
- Stripe（テスト決済）
- Nginx / Docker / さくらVPS

※ 開発当初はPHP 7.4環境でしたが、現在はPHP 8.3でも安定動作しています  
※ Laravel 10 / PHP 8.2～8.3の案件・移行も対応可能です

## 主な機能
- ユーザー登録／ログイン／メール認証（Mailtrap）
- 商品出品・編集・削除
- 商品購入（Stripeテスト決済対応）
- SOLD表示・購入履歴
- マイページ（出品／購入履歴）

## Stripeテスト決済（デモ用）
- テストカード番号：`4242-4242-4242-4242`
- CVC：任意の3桁、有効期限：未来日付
- このカード情報で商品購入→**実際に決済完了画面まで進みます**

## 特筆事項・アピールポイント
- VPS本番環境で**パーミッション/キャッシュ/ログ問題**を自力解決し、安定運用に到達
- Stripeテスト決済連携も**本番同等環境**でクリア
- エラーログ解析、nginx・php-fpm権限トラブルにも実務レベルで対応

## ローカル開発手順（例）
```bash
git clone https://github.com/koji-webfolio-2025/portfolio-flea-market.git
cd portfolio-flea-market
cp .env.example .env
composer install
php artisan key:generate
php artisan migrate --seed
php artisan serve

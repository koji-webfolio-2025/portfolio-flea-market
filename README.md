# フリマアプリ（Laravel）

## 🛒 概要
出品・購入・商品検索など、基本的なフリマアプリ機能を備えたLaravel製のポートフォリオアプリです。

- Laravel 10 + Bladeテンプレート
- 商品のCRUD機能（登録・編集・削除・一覧）
- カート機能・購入履歴（必要に応じて）
- 認証機能（Fortify）

## 🔗 デモURL
- https://frima.codeshift-lab.com  
（ログイン可能なテストアカウントは下記参照）

## 👤 テストアカウント（閲覧用）
- Email: user@example.com  
- Password: password123

## ⚙️ 使用技術
- PHP 8.2 / Laravel 10.x
- MySQL 8.0
- Docker（開発環境）
- Nginx / Let's Encrypt（本番）

## 🛠 主な機能
- 商品一覧／詳細ページ
- 商品の出品／編集／削除
- ログイン／新規登録（Fortify）
- カート機能（実装している場合）
- 管理者用画面（実装している場合）

## 🖥 ローカル開発環境セットアップ

```bash
git clone git@github.com:koji-webfolio-2025/portfolio-flea-market.git
cd portfolio-flea-market
cp .env.example .env
composer install
php artisan key:generate
php artisan migrate --seed
php artisan serve

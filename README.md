<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

## 📝 About

本リポジトリは、「Vue3 + TypeScript + Laravel9」で構築した動物病院の予約システムです。

## 📝 機能一覧

本システムでは、以下の機能を利用できます。

- ユーザー管理
  - ユーザー登録、ログイン
  - パスワードの変更、リセット
  - ユーザー情報の変更、アカウント削除
- 予約管理
  - 予約の追加（初診か再診かを選択し、メニュー、予約日時、ペット情報、飼い主情報を入力し予約を作成）
  - 予約の一覧表示, 予約のキャンセル
- メール通知機能（会員登録時、予約時、予約キャンセル時にメールを送信）

## 📝 認証について

認証にはJWT認証を使用しています。
- ログイン処理
- ログインユーザーの取得  
を中心に実装しています。

## 📝環境構築

このリポジトリをローカル環境で動かすには、以下の手順に従ってください。

<https://github.com/newyee/animal-hospital/tree/main/docs>

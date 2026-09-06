=== Consultation Connector ===
Contributors: (法人のWordPress.orgユーザー名)
Tags: events, rest-api, nonprofit
Requires at least: 6.0
Tested up to: 6.7
Requires PHP: 7.4
Stable tag: 0.1.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

相談会の日程を管理し、REST API経由でアプリへ配信するプラグイン。

== Description ==

相談会の日程(開催日時・場所・受付ステータス・予約URL)を管理するカスタム投稿タイプを提供します。
予約フォームや予約データの管理は行わず、既存の予約システムへのリンクを日程データに持たせる形で連携します。

== Installation ==

1. プラグインを有効化する
2. 管理画面の「相談会日程」から日程を登録する
3. `/wp-json/wp/v2/consultation_event` からデータを取得できる

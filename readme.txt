=== Consultation Connector ===
Contributors: J-KEI
Tags: events, rest-api, nonprofit
Requires at least: 6.0
Tested up to: 7.1
Requires PHP: 7.4
Stable tag: 0.1.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

相談会の日程を管理し、REST API経由でアプリへ配信するプラグイン。

== Description ==

相談会の日程(開催日時・場所・受付ステータス・予約URL)を管理するカスタム投稿タイプを提供します。
予約フォームや予約データの管理は行わず、既存の予約システムへのリンクを日程データに持たせる形で連携します。

== Installation ==

1. `wp-content/plugins/consultation-connector/` にプラグインを配置する
2. WordPress 管理画面の「プラグイン」から Consultation Connector を有効化する
3. 管理画面の「相談会日程」から日程を登録する
4. 必要に応じて「相談会日程一覧」ブロックを固定ページやテンプレートに配置する

== Usage ==

=== 日程の登録 ===

「相談会日程」>「新規追加」からタイトルと「相談会 詳細情報」を入力して公開します。

* 開催日・並び順: 日付と同日内の並び順を決める日時。時刻は表示には使用しません
* 時間帯: 表示用の自由記述
* 場所の名称: 会場名
* 住所: 開催場所の住所
* 地図: 住所から生成した Google マップへのリンク
* ステータス: 受付中、満席、終了
* 予約URL: 外部予約システムの URL

複数の時間枠は、1つの日程の「時間帯」に自由記述で入力します。予約データや定員は管理しません。

=== Webページへの表示 ===

ブロックエディターで「相談会日程一覧」ブロックを追加します。日程は開催日時の昇順で表示され、ステータスが「受付中」の場合だけ予約URLへのリンクが表示されます。

ブロックの設定で、一行表示、表形式、カード形式を選択できます。カード形式では、日付を左、相談会情報を中央、アイキャッチ画像を右に表示します。日付は曜日付きで、`2026年9月6日（日）`、`2026/09/06（日）`、`9月6日（日）` の形式から選択できます。時間帯は「時間帯」項目の自由記述を表示します。詳細リンクと、受付中の日程の予約 URL リンクの表示・非表示も設定できます。

=== REST API ===

公開済みの日程は、次の標準 REST API で取得できます。

`/wp-json/wp/v2/consultation_event`

カスタム項目はレスポンスの `meta` に `start_at`、`time_note`、`location_name`、`location_address`、`status`、`reservation_url` として含まれます。Google マップのリンクは `location_address` から表示時に生成します。

== Changelog ==

= 0.1.0 =
* 初回リリース。相談会日程のカスタム投稿タイプ、REST API公開、日程一覧ブロック、個別記事への詳細情報自動追記に対応。

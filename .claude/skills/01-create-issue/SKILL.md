---
name: 01-create-issue
description: GitHub Issue を作成する。Issue タイトルを受け取り、既存コードと仕様を確認し、重複確認後に受入条件付きの Issue を登録する。
disable-model-invocation: true
argument-hint: "Issue タイトル"
---

`$ARGUMENTS` を Issue タイトルとして扱い、[docs/DEVELOPMENT_WORKFLOW.md](../../../docs/DEVELOPMENT_WORKFLOW.md) の正規形式で GitHub Issue を 1 件作成してください。

## 作成前

- タイトルが空なら作成せず、入力を求める。
- `git remote -v`、`git status --short`、`docs/SPECIFICATION.md`、関連コード、ドキュメントを確認する。Issue 作成のためにファイルを変更しない。
- `gh issue list --state all --search "$ARGUMENTS"` で重複候補を確認する。重複の可能性が高ければ作成せず候補を報告する。
- 要求が曖昧、複数要求が矛盾、受入条件を検証可能にできない場合は作成せず確認を求める。
- タイトルは必要な場合だけ `<type>: <具体的な変更または解決したい状態>` に正規化する。意味を変えない。

## 本文

次の見出しを順番どおりに作成し、省略しない。該当しない項目は `N/A` と記載する。

- `## 要約`
- `## 種別`（Type / Scope / Priority）
- `## 背景`
- `## 現状と課題`
- `## 目的`
- `## 対象範囲`（対象 / 対象外）
- `## 対応方針`
- `## 受入条件`
- `## 検証方法`（ID、条件、方法、合格基準の表）
- `## 依存関係`
- `## 未決事項`
- `## 参考`

受入条件は対象・操作または入力・期待結果・例外または影響を含む、観測可能な条件にする。実装が完了したことではなく、利用者またはシステムの結果を書く。

## 作成と報告

- `gh auth status` を確認し、認証されていなければ作成を停止する。資格情報を取得・表示しない。
- PowerShell の標準入力または一時本文ファイルを使い、`gh issue create --title ... --body-file -` で登録する。シェル引数へ本文全体を直接埋め込まない。
- Issue 番号、タイトル、URL、分類、判断した事項、未決事項を報告する。

コミット、ブランチ作成、PR 作成、Issue の自動クローズ・コメントは行わない。機密情報、個人情報、ローカル固有の絶対パスを Issue に含めない。

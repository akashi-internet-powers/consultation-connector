---
name: 11-implement-issues
description: 指定した GitHub Issue の要求を確認し、関連するコード・仕様・テストを更新して検証する。
disable-model-invocation: true
argument-hint: "Issue 番号。例: 12 または #12 #15"
---

`$ARGUMENTS` から Issue 番号を抽出し、[docs/DEVELOPMENT_WORKFLOW.md](../../../docs/DEVELOPMENT_WORKFLOW.md) の `/11` 仕様に従って実装してください。空白、`,`、`、` 区切りを許可し、`#` は除去します。

## 開始前

- 番号がない、不正な文字がある場合は変更せず停止する。
- `git status --short`、現在のブランチ、`git diff` を確認し、既存の未コミット変更を保護する。
- 各 Issue を `gh issue view <number> --json number,title,body,state,labels,url` で取得する。取得不能な Issue は変更せず報告する。
- Issue の `## 目的`、`## 対象範囲`、`## 受入条件`、`## 検証方法`、`## 未決事項` を確認する。
- 複数 Issue の共通目的、依存関係、競合、対象ファイルを整理する。
- 未決事項が実装判断に影響する、受入条件を検証可能にできない、Issue 同士が矛盾する場合は確認を求める。
- Issue の実装に着手する前に、作業ツリーがクリーンであることを確認する。未コミット変更がある場合は、変更を破棄・退避・コミットせず停止する。
- 現在のブランチがデフォルトブランチの場合は、`git fetch origin` と `git pull --ff-only origin <default>` で最新化してから、`issue/<number>-<slug>` ブランチを作成して切り替える。複数Issueの場合は番号を連結したブランチ名にする。
- 既にIssue用ブランチにいる場合は、対象Issueに対応するブランチであることと作業ツリーがクリーンであることを確認して再利用する。無関係なブランチの場合は停止する。
- ブランチ作成または再利用の完了を確認してから、最初のファイル編集を行う。

## 実装

- `CLAUDE.md`、`docs/SPECIFICATION.md`、関連実装、テストを確認する。
- 既存設計と WordPress 標準 API を優先し、Issue に必要な最小範囲だけを編集する。
- 仕様変更が必要なら仕様書も更新する。
- ユーザーの未コミット変更を破棄・上書きしない。

## 検証

- 関連する診断、テスト、lint、typecheck を実行する。
- `git diff --check` を実行する。
- Issue の受入条件を次の表で判定する。

| Issue | 条件 ID | 結果 | 根拠 |
| --- | --- | --- | --- |
| #12 | AC-01 | PASS / FAIL / BLOCKED | ファイル、画面、テスト、コマンド |

`FAIL` または `BLOCKED` がある場合、実装完了とは報告しない。無関係な既存エラーは修正せず報告する。

## 報告

- 実装と検証が完了したら、`gh issue comment <number> --body-file -` で対象 Issue に実装結果をコメントする。
- コメントには、実装内容、変更ファイル、受入条件の判定、検証結果、未解決事項を含める。
- コメント投稿に失敗した場合は、実装結果の報告に失敗理由を記載する。

次の順序で報告する。

```markdown
## 実装結果
### 対象 Issue
### 変更ファイル
### 受入条件
### 検証
### 未解決事項
```

コミット、PR 作成、Issue の自動クローズは行わない。ファイルを破棄する git コマンドを実行しない。

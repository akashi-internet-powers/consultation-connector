---
name: 21-commit-issue-changes
description: 指定した GitHub Issue に関係する変更だけを検証してコミットする。無関係な変更は混入させない。
disable-model-invocation: true
argument-hint: "Issue 番号。例: 12 または #12 #15"
---

`$ARGUMENTS` から Issue 番号を抽出し、[docs/DEVELOPMENT_WORKFLOW.md](../../../docs/DEVELOPMENT_WORKFLOW.md) の `/21` 仕様に従って、指定 Issue に関係する変更だけをコミットしてください。空白、`,`、`、` 区切りを許可し、`#` は除去します。

## 開始前

- 番号がない、不正な文字がある場合は停止する。
- `git status --short`、現在のブランチ、`git diff`、`git diff --cached` を確認する。
- 原則として Issue 用ブランチで実行する。デフォルトブランチ `main` 上では、明示的な許可がない限りコミットしない。
- 各 Issue を `gh issue view <number> --json number,title,body,state,labels,url` で取得し、受入条件と変更内容を確認する。
- Issue の取得不能、対応不明、受入条件未検証、既存変更との競合がある場合はコミットしない。

## 対象変更の選別

- 変更を Issue 対象、無関係、同一ファイル内で分離不能な混在に分類する。
- 対象ファイルだけを明示して `git add -- <path> ...` する。
- `git add .`、`git add -A`、全ファイル指定、既存変更の破棄を行わない。
- 既にステージ済みの対象外変更がある場合は、変更せず報告する。
- 同一ファイル内の混在を安全に分離できない場合は停止する。

## コミット前検証

- `git diff --cached` を確認する。
- Issue 以外の変更、資格情報、個人情報、デバッグコード、一時ファイルがないことを確認する。
- 関連する診断・テストを実行する。
- `git diff --cached --check` を実行する。失敗したらコミットしない。
- 独立した複数 Issue は分け、強く結合して分離不能な場合だけまとめる。

## コミットと報告

- Conventional Commits 形式で Issue 番号を含める。例: `fix: resolve detail links (#12)`。
- コミット後に `git status --short` と `git log -1 --oneline` を確認する。
- 次の順序で報告する。

```markdown
## コミット結果
### 対象 Issue
### コミット
### 対象変更
### 検証
### 残った未コミット変更
```

コミットしなかった場合は「コミット停止理由」として、不足条件、対象ファイル、次に必要な判断を報告する。ブランチ作成、PR 作成、Issue の自動クローズ・コメントは行わない。ユーザーの既存変更を破棄・上書きしない。

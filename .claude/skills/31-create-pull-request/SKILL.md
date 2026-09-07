---
name: 31-create-pull-request
description: 指定した GitHub Issue のコミット済み変更を確認し、Issue 用ブランチから Pull Request を作成する。
disable-model-invocation: true
argument-hint: "Issue 番号。例: 12 または #12 #15"
---

`$ARGUMENTS` から Issue 番号を抽出し、指定した Issue に対応するコミット済み変更を Pull Request として提出してください。空白、`,`、`、` 区切りを許可し、`#` は除去します。

## 開始前

- 番号がない、不正な文字がある場合は停止する。
- `git status --short`、現在のブランチ、`git log`、リモートとの差分を確認する。
- 各 Issue を `gh issue view <number> --json number,title,body,state,labels,url` で取得する。取得不能な Issue は対象にしない。
- Issue の受入条件と直近の実装コメントを確認し、PR本文に反映する。
- 現在のブランチが `main` またはデフォルトブランチの場合は停止する。
- 未コミット変更がある場合は停止する。変更を破棄、退避、勝手にコミットしてはならない。
- Issue に対応するコミットが現在のブランチに含まれ、ベースブランチより先行していることを確認する。
- 同じ head ブランチまたは同じ Issue のオープンなPRがある場合は新規作成せず報告する。

## PR作成

- ベースブランチはリポジトリのデフォルトブランチを使用する。
- head ブランチがリモートにない、またはローカルが先行している場合は、確認後に `git push --set-upstream origin <branch>` または `git push` で公開する。
- PRタイトルは変更内容を表す短い命令形にする。
- PR本文には、概要、変更内容、検証結果、`Fixes #<number>` を含める。
- 本文は一時ファイルまたは標準入力で渡し、シェル引数へ本文全体を直接埋め込まない。
- `gh pr create --base <base> --head <branch> --title ... --body-file -` でPRを作成する。
- ドラフト指定がなければ通常のPRとして作成する。

## 検証

- PR作成前に対象コミットの差分、`git diff <base>...HEAD --check`、関連する診断・テストを確認する。
- PR作成後にPR番号、URL、base、head、作業ツリーの状態を確認する。
- PR作成に失敗した場合は、変更を戻さず失敗理由と次の対応を報告する。

## 報告

次の順序で報告する。

```markdown
## PR作成結果
### 対象 Issue
### PR
### 対象ブランチ
### 検証
### 未解決事項
```

コミット、ブランチ作成、Issueの自動クローズ以外のIssue更新は行わない。既存の変更を破棄・上書きしない。

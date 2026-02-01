# 雷轟（Raigo） データベース設計定義書

## 1. 駒管理テーブル (`piece`)
| カラム名 | 型 | 説明 |
| :--- | :--- | :--- |
| `piece_id` | INT | |
| `piece_type` | INT | |
| `piece_face` | ENUM | `'front'`, `'back'`. |
| `player_id` | INT | 所有プレイヤー。隠駒の場合は特殊なIDまたは属性で管理。 |
| `piece_container` | VARCHAR(50) | |
| `piece_position` | INT | |

## 2. ゲーム状態管理 (`gamestate_labels`)
フェーズを跨いで保持すべき数値属性。
- `hand3_limit`: 各プレイヤーの `hand3` の現在の最大定員（3または1）。
- `player1_point`, `player2_point`: プレイヤーの得点（初期値: **2点**）。選フェーズの減点等で使用。

## 3. 特殊ロジックのデータ定義
- **開フェーズの副作用:** 
  - `hand3_limit` を 3 -> 1 に更新。
  - 残存駒の `piece_container` を `exclusion` へ一括UPDATE。
- **選フェーズの減点:** 
  - 空き容量チェック後の `player_point` への減算処理。
- **隠駒 (On-goma):** 
  - `piece_type` が特殊（例: 0番）なレコードとして管理し、場所を交互に移動させる。

## 4. コンテナ分類
- **定員固定:** `inside`(3), `hand1,2`(1), `hand3`(可変), `head`(1), `oumon`(6).
- **無制限:** `record`, `kumo`, `kyoukoku`, `deck`, `exclusion`.

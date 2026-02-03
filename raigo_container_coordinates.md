# Raigo UI コンテナ座標・サイズ一覧 (2026-02-04 現在)

現在の `raigo.css` に基づく主要コンテナのサイズと配置座標です。
基準となるボードサイズは **1230px × 580px** です。

## 1. ベースレイアウト

| 要素名 | クラス/ID | Width | Height | 備考 |
| :--- | :--- | :--- | :--- | :--- |
| **ルート** | `#raigo-root` | **1270px** | 620px | 画面全体の外枠、余白含む |
| **ボード** | `#raigo-board` | **1230px** | 580px | ゲーム盤面エリア |

## 2. 主要コンテナ配置

ボード中心 (`x: 615px`) を基準とした相対座標 (`calc`) と、実効的な左端座標 (`Left Edge`) の概算値です。

### 中央ライン (Center Line)
除外エリアや中央のグラデーション線を含むコンテナ。
*   **Selector**: `.row-container`
*   **Width**: `980px`
*   **Height**: `35px`
*   **CSS Left**: `50%` (transform: translateX(-50%))
    *   中心位置: ボード中央 (左右対称)
*   **実効 Left**: `125px`
*   **実効 Right**: `1105px`

### デッキ (Deck) / プレイマット (Playmat)
山札や手札を置くエリア。
*   **Selector**: `.deck-container`, `.playmat-container`
*   **Width**: `630px`
*   **Height**: `70px` (Deck), `140px` (Playmat)
*   **CSS Left**: `calc(50% - 437.5px)`
*   **実効 Left**: `177.5px`

### フィールド全体 (Pieces Container)
駒が配置されるメインエリア。
*   **Selector**: `#pieces-container`
*   **Width**: `945px`
*   **Height**: `270px`
*   **CSS Left**: `52.5px` (position: relative)
    *   Flex配置による中央 (`142.5px`) から `+52.5px` シフト。
*   **実効 Left**: `195px` (概算)

## 3. サイドパーツ配置

### 除外ゾーン (Exclusion)
*   **Selector**: `.exclusion-container`
*   **Width**: `35px`
*   **Height**: `35px`
*   **CSS Left**: `10px` (Top: 50%)

### 隠駒パネル (Phase Panel)
「積/発」などを表示するパネル。
*   **Selector**: `#raigo-phase-panel`
*   **Width**: `85px`
*   **Height**: `85px`
*   **CSS Left**: `47px`
*   **CSS Top**: `12px` (初期値) / `calc(50% + 55px)` (Myself) / `calc(50% - 105px)` (Rival)

### 山札パネル (Phase Panel Top/Bottom)
*   **Selector**: `#raigo-phase-panel-top`, `#raigo-phase-panel-bottom`
*   **Width**: `90px`
*   **Height**: `90px`
*   **CSS Left**: `12px`

## 4. CSS定義 (抜粋)

```css
/* 中央線 */
.row-container {
  width: 980px;
  left: calc(50% + 140px); /* 70px(前回) + 70px(今回) */
}

/* デッキ・プレイマット */
.deck-container, .playmat-container {
  width: 630px;
  left: calc(50% - 402.5px); /* -472.5px + 70px */
}

/* フィールド */
#pieces-container {
  width: 945px;
  left: 70px;
}
```

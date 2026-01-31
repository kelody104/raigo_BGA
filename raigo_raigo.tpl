{OVERALL_GAME_HEADER}

<div id="raigo-root">
  <!-- 新規マーカーパネル（コメントアウト）
  <div id="raigo-phase-panel-top">
    <div id="raigo-phase-title-top" style="display: none;">フェーズ</div>
    <div id="raigo-phase-name-top">-</div>
    <div id="raigo-phase-sub-top">-</div>

    <button id="raigo-next-phase" type="button">-</button>
  </div>

  <div id="raigo-phase-panel-bottom">
    <div id="raigo-phase-title-bottom" style="display: none;">フェーズ</div>
    <div id="raigo-phase-name-bottom">-</div>
    <div id="raigo-phase-sub-bottom">-</div>
  </div>
  -->

  <div id="raigo-phase-panel">
    <div id="raigo-phase-title">フェーズ</div>
    <div id="raigo-phase-name">-</div>
    <div id="raigo-phase-sub">-</div>

    <button id="raigo-next-phase" type="button">-</button>
  </div>

  <!-- デバッグパネル（コメントアウト）
  <div id="raigo-debug-panel" style="position: absolute; top: 200px; left: 12px; z-index: 9; background: rgba(0,0,0,0.65); color: #fff; padding: 12px; border-radius: 8px; font-size: 12px;">
    <div style="margin-bottom: 8px; font-weight: 600;">デバッグパネル</div>
    <div style="margin-bottom: 8px;">
      <label style="display: block; margin-bottom: 4px; cursor: pointer;">
        <input id="raigo-debug-mode-flip" type="radio" name="debug-mode" value="flip" style="margin-right: 4px;">
        駒めくり
      </label>
      <label style="display: block; margin-bottom: 4px; cursor: pointer;">
        <input id="raigo-debug-mode-move" type="radio" name="debug-mode" value="move" style="margin-right: 4px;">
        駒移動
      </label>
      <label style="display: block; cursor: pointer;">
        <input id="raigo-debug-mode-none" type="radio" name="debug-mode" value="none" checked style="margin-right: 4px;">
        無効
      </label>
    </div>
  </div>
  -->

  <div id="raigo-board">
    <div id="raigo-center-line"></div>
    <div id="exclusion" class="exclusion-container"></div>
    <div id="kyoukokuA" class="row-container row-top"></div>
    <div id="kyoukokuB" class="row-container row-bottom"></div>

    <div id="towerA" class="block-container block-top"></div>
    <div id="towerB" class="block-container block-bottom"></div>

    <div id="towerA1" class="tower-column tower-column-pos-1"></div>
    <div id="towerA2" class="tower-column tower-column-pos-2"></div>
    <div id="towerA3" class="tower-column tower-column-pos-3"></div>
    <div id="towerA4" class="tower-column tower-column-pos-4"></div>
    <div id="towerA5" class="tower-column tower-column-pos-5"></div>
    <div id="towerA6" class="tower-column tower-column-pos-6"></div>
    <div id="towerA7" class="tower-column tower-column-pos-7"></div>
    <div id="towerB1" class="tower-column tower-column-pos-1"></div>
    <div id="towerB2" class="tower-column tower-column-pos-2"></div>
    <div id="towerB3" class="tower-column tower-column-pos-3"></div>
    <div id="towerB4" class="tower-column tower-column-pos-4"></div>
    <div id="towerB5" class="tower-column tower-column-pos-5"></div>
    <div id="towerB6" class="tower-column tower-column-pos-6"></div>
    <div id="towerB7" class="tower-column tower-column-pos-7"></div>

    <div id="deckA" class="deck-container deck-top"></div>
    <div id="deckB" class="deck-container deck-bottom"></div>
    <div id="playmatA" class="playmat-container playmat-top">
      <div id="moonA" class="moon-container moon-a"></div>
      <div id="handA1" class="hand-container hand-a hand-a1"></div>
      <div id="insideA3" class="inside-container inside-a inside-a3"></div>
      <div id="insideA2" class="inside-container inside-a inside-a2"></div>
      <div id="insideA1" class="inside-container inside-a inside-a1"></div>
      <div id="handA2" class="hand-container hand-a hand-a2"></div>
      <div id="oumonA1" class="oumon-container oumon-a oumon-a1">
        <div id="oumoncircleA1" class="oumon-inner"></div>
      </div>
      <div id="oumonA2" class="oumon-container oumon-a oumon-a2">
        <div id="oumoncircleA2" class="oumon-inner"></div>
      </div>
      <div id="oumonA3" class="oumon-container oumon-a oumon-a3">
        <div id="oumoncircleA3" class="oumon-inner"></div>
      </div>
    </div>
    <div id="playmatB" class="playmat-container playmat-bottom">
      <div id="moonB" class="moon-container moon-b"></div>
      <div id="handB1" class="hand-container hand-b hand-b1"></div>
      <div id="insideB1" class="inside-container inside-b inside-b1"></div>
      <div id="insideB2" class="inside-container inside-b inside-b2"></div>
      <div id="insideB3" class="inside-container inside-b inside-b3"></div>
      <div id="handB2" class="hand-container hand-b hand-b2"></div>
      <div id="oumonB1" class="oumon-container oumon-b oumon-b1">
        <div id="oumoncircleB1" class="oumon-inner"></div>
      </div>
      <div id="oumonB2" class="oumon-container oumon-b oumon-b2">
        <div id="oumoncircleB2" class="oumon-inner"></div>
      </div>
      <div id="oumonB3" class="oumon-container oumon-b oumon-b3">
        <div id="oumoncircleB3" class="oumon-inner"></div>
      </div>
    </div>
  </div>
</div>

{OVERALL_GAME_FOOTER}

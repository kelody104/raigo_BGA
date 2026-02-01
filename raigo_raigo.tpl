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
    <div id="kyoukoku_rival" class="row-container row-top"></div>
    <div id="kyoukoku_myself" class="row-container row-bottom"></div>

    <div id="tower_rival" class="block-container block-top"></div>
    <div id="tower_myself" class="block-container block-bottom"></div>

    <div id="tower_rival_1" class="tower-column tower-column-pos-1"></div>
    <div id="tower_rival_2" class="tower-column tower-column-pos-2"></div>
    <div id="tower_rival_3" class="tower-column tower-column-pos-3"></div>
    <div id="tower_rival_4" class="tower-column tower-column-pos-4"></div>
    <div id="tower_rival_5" class="tower-column tower-column-pos-5"></div>
    <div id="tower_rival_6" class="tower-column tower-column-pos-6"></div>
    <div id="tower_rival_7" class="tower-column tower-column-pos-7"></div>
    <div id="tower_myself_1" class="tower-column tower-column-pos-1"></div>
    <div id="tower_myself_2" class="tower-column tower-column-pos-2"></div>
    <div id="tower_myself_3" class="tower-column tower-column-pos-3"></div>
    <div id="tower_myself_4" class="tower-column tower-column-pos-4"></div>
    <div id="tower_myself_5" class="tower-column tower-column-pos-5"></div>
    <div id="tower_myself_6" class="tower-column tower-column-pos-6"></div>
    <div id="tower_myself_7" class="tower-column tower-column-pos-7"></div>

    <div id="deck_rival" class="deck-container deck-top"></div>
    <div id="deck_myself" class="deck-container deck-bottom"></div>
    <div id="playmat_rival" class="playmat-container playmat-top">
      <div id="moon_rival" class="moon-container moon-rival"></div>
      <div id="hand_rival_1" class="hand-container hand-rival hand-rival-1"></div>
      <div id="inside_rival_3" class="inside-container inside-rival inside-rival-3"></div>
      <div id="inside_rival_2" class="inside-container inside-rival inside-rival-2"></div>
      <div id="inside_rival_1" class="inside-container inside-rival inside-rival-1"></div>
      <div id="hand_rival_2" class="hand-container hand-rival hand-rival-2"></div>
      <div id="oumon_rival_1" class="oumon-container oumon-rival oumon-rival-1">
        <div id="oumoncircle_rival_1" class="oumon-inner"></div>
      </div>
      <div id="oumon_rival_2" class="oumon-container oumon-rival oumon-rival-2">
        <div id="oumoncircle_rival_2" class="oumon-inner"></div>
      </div>
      <div id="oumon_rival_3" class="oumon-container oumon-rival oumon-rival-3">
        <div id="oumoncircle_rival_3" class="oumon-inner"></div>
      </div>
    </div>
    <div id="playmat_myself" class="playmat-container playmat-bottom">
      <div id="moon_myself" class="moon-container moon-myself"></div>
      <div id="hand_myself_1" class="hand-container hand-myself hand-myself-1"></div>
      <div id="inside_myself_1" class="inside-container inside-myself inside-myself-1"></div>
      <div id="inside_myself_2" class="inside-container inside-myself inside-myself-2"></div>
      <div id="inside_myself_3" class="inside-container inside-myself inside-myself-3"></div>
      <div id="hand_myself_2" class="hand-container hand-myself hand-myself-2"></div>
      <div id="oumon_myself_1" class="oumon-container oumon-myself oumon-myself-1">
        <div id="oumoncircle_myself_1" class="oumon-inner"></div>
      </div>
      <div id="oumon_myself_2" class="oumon-container oumon-myself oumon-myself-2">
        <div id="oumoncircle_myself_2" class="oumon-inner"></div>
      </div>
      <div id="oumon_myself_3" class="oumon-container oumon-myself oumon-myself-3">
        <div id="oumoncircle_myself_3" class="oumon-inner"></div>
      </div>
    </div>
  </div>
</div>

{OVERALL_GAME_FOOTER}

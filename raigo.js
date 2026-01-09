define(["dojo", "dojo/_base/declare", "ebg/core/gamegui"], function (dojo, declare) {
  return declare("bgagame.raigo", ebg.core.gamegui, {
    constructor: function () {
      this.phaseOrder = ["kai", "gen", "sen", "tsumuHatsu", "in"];
      this.phaseText = {
        kai: { name: "開", sub: "条件を満たさない場合はスキップ" },
        gen: { name: "現", sub: "自分のプレイマット上の駒の移動" },
        sen: { name: "選", sub: "雷山（山札）から駒を引く" },
        tsumuHatsu: { name: "積 / 発", sub: "積：塔に重ねる / 発：峡谷へ置いて効果" },
        in: { name: "隠", sub: "手番を終了する" },
      };
    },

    setup: function (gamedatas) {
      this.gamedatas = gamedatas;

      const btn = dojo.byId("raigo-next-phase");
      if (btn) {
        dojo.connect(btn, "onclick", this, "onNextPhase");
      }

      const initialState = (gamedatas.gamestate && gamedatas.gamestate.name) ? gamedatas.gamestate.name : "";
      this.updatePhaseUI(initialState);

      this.setupNotifications();
    },

    onEnteringState: function (stateName, args) {
      this.updatePhaseUI(stateName);
    },

    onUpdateActionButtons: function (stateName, args) {
      this.updatePhaseUI(stateName);

      if (!this.isCurrentPlayerActive()) {
        return;
      }

      if (!this.isPhaseState(stateName)) {
        return;
      }

      const label = this.getNextButtonLabel(stateName);
      this.addActionButton("btn_next_phase", label, "onNextPhase");
    },

    setupNotifications: function () {},

    onNextPhase: function (evt) {
      if (evt) {
        dojo.stopEvent(evt);
      }

      if (!this.checkAction("nextPhase")) {
        return;
      }

      this.ajaxcall(
        "/raigo/raigo/nextPhase.html",
        { lock: true },
        this,
        function () {},
        function () {}
      );
    },

    isPhaseState: function (stateName) {
      return this.phaseOrder.indexOf(stateName) !== -1;
    },

    getPhaseIndex: function (stateName) {
      return this.phaseOrder.indexOf(stateName);
    },

    getNextButtonLabel: function (stateName) {
      const idx = this.getPhaseIndex(stateName);
      if (idx === -1) return "次へ";
      if (stateName === "in") return "手番終了（次の手番へ）";

      const nextState = this.phaseOrder[idx + 1];
      const nextName = (this.phaseText[nextState] && this.phaseText[nextState].name) ? this.phaseText[nextState].name : "次";
      return `次へ：${nextName}`;
    },

    updatePhaseUI: function (stateName) {
      const nameEl = dojo.byId("raigo-phase-name");
      const subEl = dojo.byId("raigo-phase-sub");
      const btnEl = dojo.byId("raigo-next-phase");

      if (!nameEl || !subEl || !btnEl) {
        return;
      }

      if (!this.isPhaseState(stateName)) {
        nameEl.innerHTML = "-";
        subEl.innerHTML = "-";
        btnEl.innerHTML = "待機中";
        this.setButtonEnabled(btnEl, false);
        return;
      }

      const phase = this.phaseText[stateName];
      const idx = this.getPhaseIndex(stateName);

      nameEl.innerHTML = `${phase.name}（${idx + 1}/5）`;
      subEl.innerHTML = this.isCurrentPlayerActive() ? "あなたの手番です" : "相手の手番です";

      btnEl.innerHTML = this.getNextButtonLabel(stateName);
      this.setButtonEnabled(btnEl, this.isCurrentPlayerActive());
    },

    setButtonEnabled: function (btnEl, enabled) {
      if (enabled) {
        dojo.removeClass(btnEl, "is-disabled");
        dojo.attr(btnEl, "disabled", false);
      } else {
        dojo.addClass(btnEl, "is-disabled");
        dojo.attr(btnEl, "disabled", true);
      }
    },
  });
});

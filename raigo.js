define(["dojo", "dojo/_base/declare", "ebg/core/gamegui"], function (dojo, declare) {
  return declare("bgagame.raigo", ebg.core.gamegui, {
    constructor: function () {
      console.log("constructor called");
      this.phaseOrder = ["setupGame", "kai", "gen", "sen", "tsumuHatsu", "kakure"];
      // ... (逡･)
      this.selectedInsideId = null;
      this.isMovingToNextPlayer = false;
    },

    setup: function (gamedatas) {
      this.gamedatas = gamedatas;

      var btn = dojo.byId("raigo-next-phase");
      if (btn) {
        dojo.connect(btn, "onclick", this, "onNextPhase");
      }

      // 繝輔ぉ繝ｼ繧ｺ繝代ロ繝ｫ縺ｮ繧ｯ繝ｪ繝・け繧､繝吶Φ繝・
      var phasePanel = dojo.byId("raigo-phase-panel");
      if (phasePanel) {
        dojo.connect(phasePanel, "onclick", this, "onPhasePanelClick");
      }

      // 繝・ヰ繝・げ繝懊ち繝ｳ縺ｮ謗･邯・
      var debugBtn = dojo.byId("raigo-debug-generate-piece");
      if (debugBtn) {
        dojo.connect(debugBtn, "onclick", this, "onDebugGeneratePiece");
      }

      // 繧ｵ繝ｼ繝舌・縺九ｉ蜿励￠蜿悶▲縺滄ｧ偵ョ繝ｼ繧ｿ繧定｡ｨ遉ｺ
      console.log("[setup] gamedatas.pieces:", gamedatas.pieces);
      var pieces = gamedatas.pieces;
      if (pieces) {
        for (var i = 0; i < pieces.length; i++) {
          var piece = pieces[i];
          // kyoukoku_rival縺ｮ鬧偵・陦ｨ遉ｺ縺励↑縺・
          if (piece.piece_container.startsWith("kyoukoku")) {
            continue;
          }
          this.displayPiece(piece.piece_container, piece.piece_position, piece.piece_id, piece.piece_type, piece.piece_face);
        }
      }

      var initialState = (gamedatas.gamestate && gamedatas.gamestate.name) ? gamedatas.gamestate.name : "";
      this.updatePhaseUI(initialState);

      // 繝励Ξ繧､繝､繝ｼ隕也せ縺ｫ蠢懊§縺ｦ繝懊・繝峨ｒ蝗櫁ｻ｢・医さ繝｡繝ｳ繝医い繧ｦ繝医＆繧後◆譌ｧ繝ｭ繧ｸ繝・け・・
      /*
      var board = dojo.byId("raigo-board");
      if (board) {
        var currentPlayerId = this.player_id;
        var playerB = gamedatas.players ? Object.values(gamedatas.players).find(p => p.player_color !== "ffffff") : null;
        var playerBId = playerB ? playerB.player_id : null;
        
        // 繝励Ξ繧､繝､繝ｼB縺ｮ蝣ｴ蜷医√・繝ｼ繝峨ｒ180蠎ｦ蝗櫁ｻ｢
        if (currentPlayerId === playerBId) {
          dojo.addClass(board, "board-rotated");
        }
        
        // 繝・ヰ繝・げ: 蜷・さ繝ｳ繝・リ縺ｫ繝ｩ繝ｳ繝繝縺ｪ鬧偵ｒ1縺､縺壹▽驟咲ｽｮ
        // this.debugPlacePieces();
      }
      */

      // 繝懊・繝峨・閾ｪ蜍募屓霆｢縺ｯ辟｡蜉ｹ蛹悶＠縲∵焔蜑・閾ｪ蛻・・隕也せ繧偵・繝・ヴ繝ｳ繧ｰ縺ｧ螳溽樟



      // Create test pieces
      var innerBoard = dojo.byId("raigo-board");
      if (innerBoard) {

        // 1. Upper group: 16 cols ﾃ・2 rows (ura.jpg)
        /* 
        var cols16 = 16;
        var width16 = cols16 * pieceWidth;
        var startLeft16 = (840 - width16) / 2; // Center in 840px container
        for (var row = 0; row < 2; row++) {
          for (var col = 0; col < cols16; col++) {
            var piece = dojo.create("div", { className: "piece", id: `upper-${row}-${col}` }, container);
            piece.style.left = `${startLeft16 + col * pieceWidth}px`;
            piece.style.top = `${currentTop + row * pieceHeight}px`;
          }
        }
        currentTop += 2 * pieceHeight + space;
        */

        // 2. Middle row 1: 24 cols ﾃ・1 row (髮ｷ.jpg)
        /*
        var cols24 = 24;
        var width24 = cols24 * pieceWidth;
        var startLeft24 = (840 - width24) / 2;
        for (var col = 0; col < cols24; col++) {
          var piece = dojo.create("div", { className: "new-piece", id: `middle1-${col}` }, container);
          piece.style.left = `${startLeft24 + col * pieceWidth}px`;
          piece.style.top = `${currentTop}px`;
        }
        currentTop += pieceHeight + space;
        */

        // 3. Middle row 2: 24 cols ﾃ・1 row (髮ｷ.jpg)
        /*
        for (var col = 0; col < cols24; col++) {
          var piece = dojo.create("div", { className: "new-piece", id: `middle2-${col}` }, container);
          piece.style.left = `${startLeft24 + col * pieceWidth}px`;
          piece.style.top = `${currentTop}px`;
        }
        currentTop += pieceHeight + space;
        */

        // 4. Lower group: 16 cols ﾃ・2 rows (ura.jpg)
        /*
        for (var row = 0; row < 2; row++) {
          for (var col = 0; col < cols16; col++) {
            var piece = dojo.create("div", { className: "piece", id: `lower-${row}-${col}` }, container);
            piece.style.left = `${startLeft16 + col * pieceWidth}px`;
            piece.style.top = `${currentTop + row * pieceHeight}px`;
          }
        }
        */
      }

      this.setupNotifications();
    },

    onEnteringState: function (stateName, args) {
      // Add delay to prevent deadlock caused by rapid sequential requests
      setTimeout(() => {
        this.isNextPhaseLocked = false;

        // Ensure buttons are enabled visually if they exist
        var btn = dojo.byId("btn_next_phase");
        var panelBtn = dojo.byId("raigo-next-phase");
        if (btn) this.setButtonEnabled(btn, true);
        if (panelBtn) this.setButtonEnabled(panelBtn, true);
      }, 800); // Increased to 800ms for safety

      if (stateName === "gen" && this.isCurrentPlayerActive()) {
        this.setupGenPhase();
      } else if (stateName === "sen" && this.isCurrentPlayerActive()) {
        this.setupSenPhase();
      } else if (stateName === "tsumuHatsu" && this.isCurrentPlayerActive()) {
        this.setupTsumuHatsuPhase();
      }
    },

    onUpdateActionButtons: function (stateName, args) {
      this.updatePhaseUI(stateName);

      if (!this.isCurrentPlayerActive()) {
        return;
      }

      if (!this.isPhaseState(stateName)) {
        return;
      }

      if (stateName === "gen") {
        return;
      } else if (stateName === "sen") {
        return;
      } else if (stateName === "tsumuHatsu") {
        // TsumuHatsu phase UI is setup by setupTsumuHatsuPhase
        return;
      }

      var label = this.getNextButtonLabel(stateName);
      this.addActionButton("btn_next_phase", label, "onNextPhase");
    },

    setupNotifications: function () {
      dojo.subscribe("piecePlaced", this, "onPiecePlaced");
      dojo.subscribe("pieceMoved", this, "onPieceMoved");
      dojo.subscribe("insideSelected", this, "onInsideSelected");
      dojo.subscribe("yakuCompleted", this, "onYakuCompleted");
      dojo.subscribe("towerCleared", this, "onTowerCleared");
      dojo.subscribe("kakureMoved", this, "onKakureMoved");
      dojo.subscribe("setupPieces", this, "onSetupPieces");
    },

    onSetupPieces: function (notif) {
      // 繝壹・繧ｸ繧偵Μ繝ｭ繝ｼ繝峨＠縺ｦ譛譁ｰ迥ｶ諷九ｒ陦ｨ遉ｺ・域圻螳壼ｯｾ蠢懊よ悽譚･縺ｯ蛟句挨縺ｫ遘ｻ蜍輔い繝九Γ繝ｼ繧ｷ繝ｧ繝ｳ縺輔○繧九・縺梧悍縺ｾ縺励＞・・
      // 縺溘□縺励√％縺ｮ騾夂衍縺梧擂繧九→縺阪・ getAllDatas 縺ｧ繧よ怙譁ｰ縺悟叙繧後ｋ縺ｯ縺壹・
      // 縺吶〒縺ｫ逕ｻ髱｢縺ｫ鬧偵′縺ゅｋ蝣ｴ蜷医・驥崎､・＠縺ｪ縺・ｈ縺・ｳｨ諢上′蠢・ｦ√・
      this.showMessage("繧ｻ繝・ヨ繧｢繝・・螳御ｺ・るｧ偵ｒ驟咲ｽｮ縺励∪縺・..", "info");
      // 邁｡譏鍋噪縺ｫ蜈ｨ鬧貞・謠冗判・域里蟄倥・鬧偵ｒ蜑企勁縺励※縺九ｉ・・
      dojo.query(".piece").forEach(dojo.destroy);
      if (this.gamedatas.pieces) {
        // 豕ｨ: 縺薙・譎らせ縺ｧ縺ｮ gamedatas.pieces 縺ｯ蜿､縺・庄閭ｽ諤ｧ縺後≠繧九◆繧√・
        // 繧ｵ繝ｼ繝舌・縺九ｉ貂｡縺輔ｌ縺・pieces 繝ｪ繧ｹ繝医ｒ蜿肴丐縺吶ｋ縺ｮ縺梧ｭ｣隗｣縲・
        // 縺ｨ繧翫≠縺医★繝ｪ繝ｭ繝ｼ繝峨〒遒ｺ螳溘↓譛譁ｰ繧定｡ｨ遉ｺ縺輔○繧九・
        window.location.reload();
      }
    },

    onYakuCompleted: function (notif) {
      var yakuName = notif.args.yaku_name;
      var score = notif.args.score;
      var towerId = notif.args.towerId;

      this.showMessage(dojo.string.substitute("蠖ｹ螳梧・: ${yaku_name} (${score}轤ｹ)", {
        yaku_name: yakuName,
        score: score
      }), "info");

      // 繧ｨ繝輔ぉ繧ｯ繝医′縺ゅｌ縺ｰ縺薙％縺ｫ霑ｽ蜉・井ｾ・ 蝪斐・逋ｺ蜈峨↑縺ｩ・・
      var tower = dojo.byId(towerId);
      if (tower) {
        dojo.animateProperty({
          node: tower,
          duration: 1000,
          properties: {
            backgroundColor: { start: "#ffff00", end: "transparent" } // 鮟・牡轤ｹ貊・
          }
        }).play();
      }

      // 繧ｹ繧ｳ繧｢縺ｮ譖ｴ譁ｰ
      this.scoreCtrl[notif.args.player_id].toValue(notif.args.new_score);
    },

    onTowerCleared: function (notif) {
      var towerId = notif.args.towerId;
      // 螳滄圀縺ｫ縺ｯonPieceMoved縺ｧexclusion縺ｸ遘ｻ蜍輔☆繧九・縺壹□縺後・
      // 縺薙％縺ｧ迚ｹ螳壹・貍泌・繧・ｮ牙・遲悶→縺励※縺ｮ繧ｯ繝ｪ繧｢繧定｡後≧
      // 莉雁屓縺ｯonPieceMoved縺ｧ縺ｮ遘ｻ蜍輔ｒ菫｡鬆ｼ縺励√％縺薙〒縺ｯ繝ｭ繧ｰ蜃ｺ蜉帙・縺ｿ
      console.log(`Tower cleared: ${towerId}`);
    },

    setupTsumuHatsuPhase: function () {
      // 閾ｪ蛻・・謇区惆・・and_myself_1, hand_myself_2・峨↓縺ゅｋ鬧偵ｒ繧ｯ繝ｪ繝・け蜿ｯ閭ｽ縺ｫ縺吶ｋ
      var handIds = ["hand_myself_1", "hand_myself_2"];

      for (var handId of handIds) {
        var hand = dojo.byId(handId);
        if (hand) {
          var pieces = dojo.query(".piece", hand);
          pieces.forEach((piece) => {
            // 譌｢蟄倥・繝上Φ繝峨Λ縺後≠繧句庄閭ｽ諤ｧ繧定・・縺励※ connect
            dojo.addClass(piece, "selectable-piece");
            var handler = dojo.connect(piece, "onclick", this, (function (pId) {
              return (evt) => {
                dojo.stopEvent(evt);
                this.onHandPieceClick(pId, handId);
              };
            }).call(this, piece.id));
            // 豕ｨ諢・ 繧ｯ繝ｪ繝ｼ繝ｳ繧｢繝・・縺悟ｿ・ｦ√□縺檎ｰ｡譏灘ｮ溯｣・・縺溘ａ逵∫払
            // 譛ｬ譚･縺ｯ phase 邨ゆｺ・凾縺ｫ disconnect 縺吶∋縺・
          });
        }
      }

      var label = this.getNextButtonLabel("tsumuHatsu");
      this.addActionButton("btn_next_phase", label, "onNextPhase");
    },

    onHandPieceClick: function (pieceId, containerId) {
      if (!this.isCurrentPlayerActive()) return;

      this.selectedPieceId = pieceId.split('-')[2] || pieceId;

      // SELECT 縺輔ｌ縺溯ｦ冶ｦ壼柑譫・
      dojo.query(".selected-piece").removeClass("selected-piece");
      dojo.addClass(dojo.byId(pieceId), "selected-piece");

      // 1. 蝪斐ｒ驕ｸ謚槫庄閭ｽ縺ｫ縺吶ｋ (tower_myself_*, tower_rival_*)
      dojo.query(".selectable-tower").removeClass("selectable-tower");
      dojo.query(".tower-column").addClass("selectable-tower");

      var towers = dojo.query(".tower-column");
      towers.forEach((tower) => {
        if (!tower.hasAttribute("data-click-connected")) {
          dojo.connect(tower, "onclick", this, (function (tId) {
            return (evt) => {
              if (dojo.hasClass(tower, "selectable-tower")) {
                dojo.stopEvent(evt);
                this.onTowerClick(tId);
              }
            };
          }).call(this, tower.id));
          dojo.attr(tower, "data-click-connected", "true");
        }
      });

      // 2. 蟲｡隹ｷ繧帝∈謚槫庄閭ｽ縺ｫ縺吶ｋ (kyoukoku_myself)
      // kyoukoku_myself 縺ｯ div#kyoukoku_myself
      var kyoukoku = dojo.byId("kyoukoku_myself");
      if (kyoukoku) {
        dojo.query(".selectable-kyoukoku").removeClass("selectable-kyoukoku"); // Reset any previously selected kyoukoku
        dojo.addClass(kyoukoku, "selectable-kyoukoku");

        if (!kyoukoku.hasAttribute("data-click-connected")) {
          dojo.connect(kyoukoku, "onclick", this, (function (kId) {
            return (evt) => {
              if (dojo.hasClass(kyoukoku, "selectable-kyoukoku")) {
                dojo.stopEvent(evt);
                this.onKyoukokuClick(kId);
              }
            };
          }).call(this, kyoukoku.id));
          dojo.attr(kyoukoku, "data-click-connected", "true");
        }
      }
    },

    onTowerClick: function (towerId) {
      if (!this.selectedPieceId) return;
      var idVal = this.selectedPieceId;

      this.ajaxcall(
        "/raigo/raigo/actTsumu.html",
        {
          pieceId: idVal,
          towerId: towerId
        },
        this,
        function (result) {
          dojo.query(".selected-piece").removeClass("selected-piece");
          dojo.query(".selectable-tower").removeClass("selectable-tower");
          dojo.query(".selectable-kyoukoku").removeClass("selectable-kyoukoku");

          this.showMessage(_("Action completed. Press 'Next Phase' to continue."), "info");
          dojo.addClass("btn_next_phase", "blinking");
        }
      );
    },

    onKyoukokuClick: function (kyoukokuId) {
      if (!this.selectedPieceId) return;
      var idVal = this.selectedPieceId;

      this.ajaxcall(
        "/raigo/raigo/actHatsu.html",
        {
          pieceId: idVal,
          kyoukokuId: kyoukokuId
        },
        this,
        function (result) {
          dojo.query(".selected-piece").removeClass("selected-piece");
          dojo.query(".selectable-tower").removeClass("selectable-tower");
          dojo.query(".selectable-kyoukoku").removeClass("selectable-kyoukoku");

          this.showMessage(_("Action completed. Press 'Next Phase' to continue."), "info");
          dojo.addClass("btn_next_phase", "blinking");
        }
      );
    },

    setupKakurePhase: function () {
      // 閾ｪ蛻・・蝪費ｼ・ower_myself_1..7・峨・縺ｿ驕ｸ謚槫庄閭ｽ縺ｫ縺吶ｋ
      dojo.query(".selectable-tower").removeClass("selectable-tower");

      var towers = dojo.query("[id^='tower_myself_']");
      towers.addClass("selectable-tower");

      towers.forEach((tower) => {
        if (!tower.hasAttribute("data-click-connected")) {
          dojo.connect(tower, "onclick", this, (function (tId) {
            return (evt) => {
              if (dojo.hasClass(tower, "selectable-tower")) {
                dojo.stopEvent(evt);
                this.onKakureTowerClick(tId);
              }
            };
          }).call(this, tower.id));
          dojo.attr(tower, "data-click-connected", "true");
        }
      });

      var label = this.getNextButtonLabel("kakure");
      this.addActionButton("btn_next_phase", label, "onNextPhase");
    },

    onKakureTowerClick: function (towerId) {
      if (!this.isCurrentPlayerActive()) return;

      this.ajaxcall(
        "/raigo/raigo/actMoveKakure.html",
        {
          towerId: towerId
        },
        this,
        function (result) {
          dojo.query(".selectable-tower").removeClass("selectable-tower");

          this.showMessage(_("Marker moved. Press 'Next Phase' to end your turn."), "info");
          dojo.addClass("btn_next_phase", "blinking");
        }
      );
    },

    onKakureMoved: function (notif) {
      console.log("Kakure moved to " + notif.args.pos);

      var towerId = notif.args.towerId;
      var tower = dojo.byId(towerId);
      if (tower) {
        dojo.query(".kakure-marker").forEach(dojo.destroy);
        dojo.create("div", {
          className: "kakure-marker", style: {
            width: "20px", height: "20px", background: "red", borderRadius: "50%",
            position: "absolute", bottom: "-25px", left: "50%", marginLeft: "-10px"
          }
        }, tower);
      }
    },

    onInsideSelected: function (notif) {
      // inside驕ｸ謚樊凾縺ｮ騾夂衍・医け繝ｩ繧､繧｢繝ｳ繝亥・縺ｮ迥ｶ諷区峩譁ｰ・・
      this.selectedInsideId = notif.args.insideId;
      console.log(`Selected inside: ${this.selectedInsideId}`);
    },

    onPieceMoved: function (notif) {
      // 鬧堤ｧｻ蜍募ｮ御ｺ・凾縺ｮ騾夂衍
      var fromContainer = notif.args.fromContainer;
      var toContainer = notif.args.toContainer;
      console.log(`Piece moved from ${fromContainer} to ${toContainer}`);

      // DOM荳翫〒鬧偵ｒ遘ｻ蜍・
      var fromElem = dojo.byId(fromContainer);
      var toElem = dojo.byId(toContainer);
      if (fromElem && toElem) {
        var piece = dojo.query(".piece", fromElem)[0];
        if (piece) {
          dojo.place(piece, toElem);
        }
      }
    },

    onPiecePlaced: function (notif) {
      var containerId = notif.args.container;

      var container = dojo.byId(containerId);
      if (!container) {
        console.error(`繧ｳ繝ｳ繝・リ '${containerId}' 縺瑚ｦ九▽縺九ｊ縺ｾ縺帙ｓ`);
        return;
      }

      // 螳滄圀縺ｮ繧ｳ繝ｳ繝・リ蜀・・鬧呈焚繧偵き繧ｦ繝ｳ繝・
      var existingPieces = dojo.query(".piece", container).length;

      // 鬧棚D繧剃ｸ諢上↓逕滓・
      var pieceId = `piece-${containerId}-${Date.now()}-${existingPieces}`;
      this.displayPiece(containerId, existingPieces, pieceId);
    },

    setupGenPhase: function () {
      console.log("setupGenPhase called");
      var suffix = "myself";
      var insideIds = [
        "inside_" + suffix + "_1",
        "inside_" + suffix + "_2",
        "inside_" + suffix + "_3"
      ];

      for (var i = 0; i < insideIds.length; i++) {
        var id = insideIds[i];
        var elem = dojo.byId(id);
        if (elem) {
          console.log("Connecting click for: " + id);
          dojo.connect(elem, "onclick", this, (function (targetId) {
            return function (evt) {
              console.log("Inside slot clicked: " + targetId);
              dojo.stopEvent(evt);
              this.onInsideClick(targetId);
            };
          }).call(this, id));
        }
      }

      var label = this.getNextButtonLabel("gen");
      this.addActionButton("btn_next_phase", label, "onNextPhase");
    },

    setupSenPhase: function () {
      // deck隕∫ｴ縺ｫ繧ｯ繝ｪ繝・け繝上Φ繝峨Λ繧定ｿｽ蜉
      var deckIds = ["deck_rival", "deck_myself"];

      // Current number of pieces taken from deck in this phase
      var piecesTaken = this.gamedatas.deck_pieces_taken || 0;
      var canTakeMore = piecesTaken < 2;

      if (!canTakeMore) {
        // Already taken 2 pieces, skip deck interaction
        var label = this.getNextButtonLabel("sen");
        this.addActionButton("btn_next_phase", label, "onNextPhase");
        return;
      }

      for (var deckId of deckIds) {
        var elem = dojo.byId(deckId);
        if (elem) {
          dojo.connect(elem, "onclick", this, (function (id) {
            return () => this.onDeckClick("deck"); // 繧ｯ繝ｪ繝・け縺輔ｌ縺溷ｴ謇縺ｫ髢｢繧上ｉ縺壹さ繝ｳ繝・リ縺ｯ 'deck'
          }).call(this, deckId));
        }
      }

      var label = this.getNextButtonLabel("sen");
      this.addActionButton("btn_next_phase", label, "onNextPhase");
    },

    onDeckClick: function (deckId) {
      if (!this.isCurrentPlayerActive()) return;

      // deckId 縺ｯ蟶ｸ縺ｫ "deck" 縺ｨ縺励※貂｡縺輔ｌ繧・
      // 螳滄圀縺ｮDOM隕∫ｴ縺ｯ deck_rival 縺ｾ縺溘・ deck_myself 繧呈Φ螳・
      // 縺薙％縺ｧ縺ｯ繧ｯ繝ｪ繝・け縺輔ｌ縺溯ｦ∫ｴ繧堤音螳壹☆繧句ｿ・ｦ√′縺ｪ縺・◆繧√∵ｱ守畑逧・↑ "deck" 繧剃ｽｿ逕ｨ

      // 鬧偵・蟄伜惠繝√ぉ繝・け縺ｯ繧ｵ繝ｼ繝舌・蛛ｴ縺ｧ陦後≧縺九‥isplayPiece縺ｮ繝ｭ繧ｸ繝・け縺ｧ蛻､譁ｭ
      // var container = dojo.byId(deckId); // 縺薙・ID縺ｮ隕∫ｴ縺ｯ蟄伜惠縺励↑縺・庄閭ｽ諤ｧ縺碁ｫ倥＞
      // if (!container) return;

      // var piece = dojo.query(".piece", container)[0];
      // if (!piece) {
      //   console.log(`No piece in ${deckId}`);
      //   return;
      // }

      // Check if we've already taken 2 pieces
      var piecesTaken = this.gamedatas.deck_pieces_taken || 0;
      if (piecesTaken >= 2) {
        console.log("Already taken 2 pieces from deck");
        return;
      }

      // Clear any previous highlights first
      dojo.query(".available-hand").forEach(function (elem) {
        dojo.removeClass(elem, "available-hand");
      });

      // 蛻ｩ逕ｨ蜿ｯ閭ｽ縺ｪhand/inside繧帝ｻ・ｷ大喧
      var targetIds = ["hand_rival_1", "hand_rival_2", "inside_rival_1", "inside_rival_2", "inside_rival_3"];
      for (var targetId of targetIds) {
        var target = dojo.byId(targetId);
        if (target) {
          var pieces = dojo.query(".piece", target);
          if (pieces.length === 0) {
            // 遨ｺ縺・※縺・ｋ
            dojo.addClass(target, "available-hand");
            // Store handler reference for later cleanup
            var handler = dojo.connect(target, "onclick", this, (function (tId, dId) {
              return () => {
                // Disconnect all target handlers first
                dojo.query(".available-hand").forEach(function (elem) {
                  dojo.removeClass(elem, "available-hand");
                });
                this.onSenTargetClick(tId, dId);
              };
            }).call(this, targetId, deckId));
          }
        }
      }
    },

    onSenTargetClick: function (targetId, deckId) {
      if (!this.isCurrentPlayerActive()) return;

      // Immediately clear highlights to prevent double-click
      dojo.query(".available-hand").forEach(function (elem) {
        dojo.removeClass(elem, "available-hand");
      });

      // 繧ｵ繝ｼ繝舌・縺ｸ遘ｻ蜍輔Μ繧ｯ繧ｨ繧ｹ繝磯∽ｿ｡
      this.ajaxcall(
        "/raigo/raigo/movePieceFromDeck.html",
        {
          fromContainer: "deck", // 繧ｳ繝ｳ繝・リ蜷阪・蟶ｸ縺ｫ 'deck'
          toContainer: targetId
        },
        this,
        (function () {
          // Update piece count after successful move
          var piecesTaken = (this.gamedatas.deck_pieces_taken || 0) + 1;
          this.gamedatas.deck_pieces_taken = piecesTaken;

          // If 2 pieces already taken, disable further deck clicks
          if (piecesTaken >= 2) {
            console.log("2 pieces taken, disabling deck selection");
            // Visually disable decks
            var deckIds = ["deck_rival", "deck_myself"];
            for (var deckId of deckIds) {
              var elem = dojo.byId(deckId);
              if (elem) {
                elem.style.cursor = "default";
                elem.style.opacity = "0.6";
                elem.style.pointerEvents = "none";
              }
            }
          }
        }).bind(this)
      );
    },

    onInsideClick: function (insideId) {
      if (!this.isCurrentPlayerActive()) return;

      var container = dojo.byId(insideId);
      if (!container) return;

      var piece = dojo.query(".piece", container)[0];
      if (!piece) {
        console.log(`No piece in ${insideId}`);
        return;
      }

      this.selectedInsideId = insideId;

      // 蛻ｩ逕ｨ蜿ｯ閭ｽ縺ｪhand・育ｩｺ縺・※縺・ｋ繧ゅ・・峨ｒ鮟・ｷ大喧
      var suffix = "myself";
      var handIds = [
        "hand_" + suffix + "_1",
        "hand_" + suffix + "_2"
      ];
      for (var i = 0; i < handIds.length; i++) {
        var handId = handIds[i];
        var hand = dojo.byId(handId);
        if (hand) {
          var pieces = dojo.query(".piece", hand);
          if (pieces.length === 0) {
            // 遨ｺ縺・※縺・ｋ
            dojo.addClass(hand, "available-hand");
            dojo.connect(hand, "onclick", this, (function (hId, iId) {
              return function (evt) {
                dojo.stopEvent(evt);
                this.onHandClick(hId, iId);
              };
            }).call(this, handId, insideId));
          }
        }
      }
    },

    onHandClick: function (handId, insideId) {
      if (!this.isCurrentPlayerActive()) return;

      // 繧ｵ繝ｼ繝舌・縺ｸ遘ｻ蜍輔Μ繧ｯ繧ｨ繧ｹ繝磯∽ｿ｡
      this.ajaxcall(
        "/raigo/raigo/movePieceToHand.html",
        {
          fromContainer: insideId,
          toContainer: handId
        },
        this,
        (function () {
          dojo.query(".available-hand").forEach((function (hand) {
            dojo.removeClass(hand, "available-hand");
          }).bind(this));
        }).bind(this)
      );
    },

    displayPiece: function (containerId, position, pieceId, type, face) {
      var parentContainerId = containerId;
      var renderPosition = position;

      // deck 繧ｳ繝ｳ繝・リ縺ｮ蝣ｴ蜷医・縲｝osition 縺ｫ蠢懊§縺ｦ rival/myself 縺ｫ謖ｯ繧雁・縺・
      if (containerId === "deck") {
        parentContainerId = (position % 2 === 0) ? "deck_rival" : "deck_myself";
        renderPosition = Math.floor(position / 2);
      } else {
        // deck 莉･螟悶・蝣ｴ蜷医・繝槭ャ繝斐Φ繧ｰ繧定ｩｦ縺ｿ繧・
        var mappedId = this.getMappedContainerId(containerId, position);
        if (mappedId && mappedId !== parentContainerId) {
          parentContainerId = mappedId;
          // inside 繧・moon 縺ｪ縺ｩ縺ｮ蜊倅ｸ譫繧ｳ繝ｳ繝・リ縺ｮ蝣ｴ蜷医・縲√◎縺ｮ荳ｭ縺ｧ縺ｮ菴咲ｽｮ繧・0 縺ｫ蝗ｺ螳・
          if (parentContainerId.indexOf("inside_") === 0 || parentContainerId.indexOf("moon_") === 0) {
            renderPosition = 0;
          }
        }
      }

      var finalContainer = dojo.byId(parentContainerId);
      if (!finalContainer) {
        console.error(`繧ｳ繝ｳ繝・リ '${containerId}' (mapped: '${parentContainerId}') 縺瑚ｦ九▽縺九ｊ縺ｾ縺帙ｓ`);
        return;
      }

      // 鬧偵ｒ繧ｳ繝ｳ繝・リ縺ｮ逶ｴ謗･縺ｮ蟄舌→縺励※逕滓・
      var piece = dojo.create("div", {
        className: "piece",
        id: pieceId
      }, finalContainer);

      piece.style.position = "absolute";

      // 蝓ｺ譛ｬ蟇ｸ豕・
      piece.style.width = "35px";
      piece.style.height = "35px";

      // 陦ｨ陬上↓蠢懊§縺ｦCSS繧ｯ繝ｩ繧ｹ縺ｨ繝・く繧ｹ繝医ｒ莉倅ｸ・
      var t = type;
      var pt = (this.gamedatas && this.gamedatas.piece_types && typeof t !== "undefined") ? this.gamedatas.piece_types[t] : null;
      var name = (pt && pt.name) ? pt.name : "";
      var weight = (pt && pt.weight) ? pt.weight : 1;

      if (face === "front") {
        dojo.addClass(piece, "piece-front");
        piece.setAttribute("data-weight", weight);
        piece.textContent = name;
        piece.style.fontWeight = "700";

        // 荳雁・繧ｳ繝ｳ繝・リ縺ｮ鬧偵・180蠎ｦ蝗櫁ｻ｢縺輔○繧具ｼ・eck髯､螟厄ｼ・
        var topContainers = ["kyoukoku_rival", "hand_rival_1", "hand_rival_2", "inside_rival_1", "inside_rival_2", "inside_rival_3", "oumoncircle_rival_1", "oumoncircle_rival_2", "oumoncircle_rival_3", "moon_rival", "deck_rival", "tower_rival_1", "tower_rival_2", "tower_rival_3", "tower_rival_4", "tower_rival_5", "tower_rival_6", "tower_rival_7"];
        if (topContainers.includes(parentContainerId)) {
          dojo.addClass(piece, "piece-rotated");
        }
      } else {
        dojo.addClass(piece, "piece-back");
        piece.textContent = "";
        piece.style.fontWeight = "700";
      }

      console.log(`[displayPiece] id=${pieceId}, container=${containerId}, type=${type}, face=${face}`);

      // 繧ｳ繝ｳ繝・リ繧ｿ繧､繝励〒逡ｰ縺ｪ繧九Ξ繧､繧｢繧ｦ繝医ｒ驕ｩ逕ｨ
      if (parentContainerId.startsWith("kyoukoku")) {
        piece.style.left = `${renderPosition * 35}px`;
        piece.style.top = "0";
      } else if (parentContainerId.startsWith("deck")) {
        var rowIndex = Math.floor(renderPosition / 2);
        var isTopRow = renderPosition % 2 === 0;
        piece.style.left = `${rowIndex * 35}px`;
        piece.style.top = isTopRow ? "0" : "35px";
      } else if (parentContainerId.startsWith("tower_rival")) {
        // tower_rival: 荳九°繧我ｸ翫↓蜷代°縺｣縺ｦ遨阪∩荳翫′繧具ｼ医Λ繧､繝ｳ蛛ｴ縺九ｉ・・
        piece.style.left = "0";
        piece.style.top = `${210 - (renderPosition + 1) * 35}px`;
      } else if (parentContainerId.startsWith("tower_myself")) {
        // tower_myself: 荳翫°繧我ｸ九↓蜷代°縺｣縺ｦ遨阪∩荳翫′繧具ｼ医Λ繧､繝ｳ蛛ｴ縺九ｉ・・
        piece.style.left = "0";
        piece.style.top = `${renderPosition * 35}px`;
      } else if (parentContainerId === "exclusion") {
        // exclusion: 繧ｹ繧ｿ繝・け・磯㍾縺ｭ縺ｦ陦ｨ遉ｺ・・
        piece.style.left = "0";
        piece.style.top = "0";
      } else {
        piece.style.left = "calc(50% - 17.5px)";
        piece.style.top = `${renderPosition * 35}px`;
      }

      console.log(`鬧偵ｒ陦ｨ遉ｺ縺励∪縺励◆: ${pieceId} in ${containerId} (菴咲ｽｮ: ${position}), left=${piece.style.left}, top=${piece.style.top}, backgroundImage=${piece.style.backgroundImage}`);

    },

    getMappedContainerId: function (dbContainerId, position) {
      if (!dbContainerId) return dbContainerId;

      // 縺吶〒縺ｫDOM縺ｫ蟄伜惠縺吶ｋID縺ｪ繧峨◎縺ｮ縺ｾ縺ｾ霑斐☆
      if (dojo.byId(dbContainerId)) return dbContainerId;

      // inside_p{playerId} -> inside_{myself|rival}_{pos+1}
      if (dbContainerId.indexOf("inside_p") === 0) {
        var parts = dbContainerId.split("_"); // inside, p{playerId}
        var playerId = parts[1].substring(1);
        var suffix = (playerId == this.player_id) ? "myself" : "rival";
        // position 0, 1, 2 -> 1, 2, 3
        var posIndex = (typeof position !== "undefined") ? (parseInt(position) + 1) : 1;
        return `inside_${suffix}_${posIndex}`;
      }

      // hand_p{playerId} -> hand_{myself|rival}_{pos+1}
      if (dbContainerId.indexOf("hand_p") === 0) {
        var parts = dbContainerId.split("_");
        var playerId = parts[1].substring(1);
        var suffix = (playerId == this.player_id) ? "myself" : "rival";
        var posIndex = (typeof position !== "undefined") ? (parseInt(position) + 1) : 1;
        return `hand_${suffix}_${posIndex}`;
      }

      // hand3_p{playerId} -> hand_{myself|rival}_{pos+1} (螂･鄒ｩ鬧・
      if (dbContainerId.indexOf("hand3_p") === 0) {
        return null;
      }

      // moon_p{playerId} -> moon_{myself|rival}
      if (dbContainerId.indexOf("moon_p") === 0) {
        var parts = dbContainerId.split("_");
        var playerId = parts[1].substring(1);
        var suffix = (playerId == this.player_id) ? "myself" : "rival";
        return `moon_${suffix}`;
      }

      return dbContainerId;
    },


    onNextPhase: function (evt) {
      if (evt) {
        dojo.stopEvent(evt);
      }

      if (!this.checkAction("nextPhase")) {
        return;
      }

      this.ajaxcall(
        "/raigo/raigo/nextPhase.html",
        {},
        this,
        function () { },
        function () { }
      );
    },

    isPhaseState: function (stateName) {
      return this.phaseOrder.indexOf(stateName) !== -1;
    },

    getPhaseIndex: function (stateName) {
      return this.phaseOrder.indexOf(stateName);
    },

    getNextButtonLabel: function (stateName) {
      var idx = this.getPhaseIndex(stateName);
      if (idx === -1) return "谺｡縺ｸ";
      if (stateName === "kakure") return "謇狗分邨ゆｺ・ｼ域ｬ｡縺ｮ謇狗分縺ｸ・・;

      var nextState = this.phaseOrder[idx + 1];
      var nextName = (this.phaseText[nextState] && this.phaseText[nextState].name) ? this.phaseText[nextState].name : "谺｡";
      return `谺｡縺ｸ・・{nextName}`;
    },

    updatePhaseUI: function (stateName) {
      var nameEl = dojo.byId("raigo-phase-name");
      var subEl = dojo.byId("raigo-phase-sub");
      var btnEl = dojo.byId("raigo-next-phase");
      var panelEl = dojo.byId("raigo-phase-panel");

      if (!nameEl || !subEl || !btnEl) {
        return;
      }

      if (!this.isPhaseState(stateName)) {
        nameEl.innerHTML = "-";
        subEl.innerHTML = "-";
        btnEl.innerHTML = "蠕・ｩ滉ｸｭ";
        this.setButtonEnabled(btnEl, false);
        if (panelEl) {
          dojo.removeClass(panelEl, "panel-active-turn");
          dojo.addClass(panelEl, "panel-inactive");
        }
        return;
      }

      var shortName = this.phaseShortName[stateName];
      var phaseNum = this.phaseIndexMap[stateName] || "--";

      // 逡ｪ蜿ｷ繧定｡ｨ遉ｺ (raigo.css 蛛ｴ縺ｧ .phase-number 縺ｪ縺ｩ縺ｮ繧ｹ繧ｿ繧､繝ｫ縺悟ｿ・ｦ√°繧ゅ＠繧後∪縺帙ｓ)
      var phaseNumHtml = `<div class="phase-number" style="font-size:10px; opacity:0.6; position:absolute; top:5px; left:5px;">${phaseNum}</div>`;

      if (stateName === "tsumuHatsu") {
        nameEl.innerHTML = phaseNumHtml + '<div class="phase-text-container"><span class="phase-tsumuHatsu-left">遨・/span><span class="phase-tsumuHatsu-right">逋ｺ</span></div>';
      } else {
        nameEl.innerHTML = phaseNumHtml + (shortName || "");
      }

      subEl.innerHTML = this.isCurrentPlayerActive() ? "縺ゅ↑縺溘・謇狗分縺ｧ縺・ : "逶ｸ謇九・謇狗分縺ｧ縺・;

      btnEl.innerHTML = this.getNextButtonLabel(stateName);
      this.setButtonEnabled(btnEl, this.isCurrentPlayerActive());

      // 繧ｿ繝ｼ繝ｳ縺ｫ蠢懊§縺ｦ繝代ロ繝ｫ縺ｮ閭梧勹濶ｲ縺ｨ菴咲ｽｮ繧貞､画峩
      if (panelEl) {
        if (stateName === "tsumuHatsu") {
          dojo.addClass(panelEl, "phase-tsumuHatsu");
        } else {
          dojo.removeClass(panelEl, "phase-tsumuHatsu");
        }

        dojo.removeClass(panelEl, "panel-pos-myself");
        dojo.removeClass(panelEl, "panel-pos-rival");

        if (this.isCurrentPlayerActive()) {
          dojo.removeClass(panelEl, "panel-inactive");
          dojo.addClass(panelEl, "panel-active-turn");
          dojo.addClass(panelEl, "panel-pos-myself");
        } else {
          dojo.removeClass(panelEl, "panel-active-turn");
          dojo.addClass(panelEl, "panel-inactive");
          dojo.addClass(panelEl, "panel-pos-rival");
        }
      }
    },

    onNextPhase: function (evt) {
      if (evt) {
        dojo.stopEvent(evt);
      }

      // Check current action
      if (!this.checkAction("nextPhase")) {
        return;
      }

      // Prevent double click
      if (this.isNextPhaseLocked) {
        return;
      }
      this.isNextPhaseLocked = true;

      // Disable button visually
      var btn = dojo.byId("btn_next_phase"); // Action button
      var panelBtn = dojo.byId("raigo-next-phase"); // Panel button
      if (btn) this.setButtonEnabled(btn, false);
      if (panelBtn) this.setButtonEnabled(panelBtn, false);

      this.ajaxcall(
        "/raigo/raigo/nextPhase.html",
        {},
        this,
        function (result) {
          // Success: State update will reset the lock
          this.isNextPhaseLocked = false;
        },
        function (isError) {
          // Error: Release lock
          this.isNextPhaseLocked = false;
          if (btn) this.setButtonEnabled(btn, true);
          if (panelBtn) this.setButtonEnabled(panelBtn, true);
        }
      );
    },

    onPhasePanelClick: function (evt) {
      dojo.stopEvent(evt);

      if (!this.checkAction("nextPhase")) {
        return;
      }

      // Prevent double click
      if (this.isNextPhaseLocked) {
        return;
      }
      this.isNextPhaseLocked = true;

      // Disable button visually to indicate processing
      var btn = dojo.byId("btn_next_phase");
      if (btn) this.setButtonEnabled(btn, false);

      this.ajaxcall(
        "/raigo/raigo/nextPhase.html",
        {},
        this,
        function (result) {
          // Success: Lock will be reset in onEnteringState
          this.isNextPhaseLocked = false;
        },
        function (isError) {
          // Error: Release lock
          this.isNextPhaseLocked = false;
          if (btn) this.setButtonEnabled(btn, true);
        }
      );
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

    onDebugGeneratePiece: function (evt) {
      if (evt) {
        dojo.stopEvent(evt);
      }

      var inputEl = dojo.byId("raigo-debug-container-id");
      var containerId = inputEl ? inputEl.value.trim() : "";

      if (!containerId) {
        alert("繧ｳ繝ｳ繝・リID繧貞・蜉帙＠縺ｦ縺上□縺輔＞");
        return;
      }

      var container = dojo.byId(containerId);
      if (!container) {
        alert(`繧ｳ繝ｳ繝・リ '${containerId}' 縺瑚ｦ九▽縺九ｊ縺ｾ縺帙ｓ`);
        return;
      }

      // DB菫晏ｭ倥メ繧ｧ繝・け繝懊ャ繧ｯ繧ｹ縺ｮ迥ｶ諷九ｒ蜿門ｾ・
      var persistCheckbox = dojo.byId("raigo-debug-persist");
      var persist = persistCheckbox ? persistCheckbox.checked : false;

      // 蜊ｳ蠎ｧ縺ｫ繝ｭ繝ｼ繧ｫ繝ｫ縺ｧ鬧偵ｒ逕滓・
      var existingPieces = dojo.query(".piece", container).length;
      var pieceId = `piece-${containerId}-${Date.now()}-${existingPieces}`;
      this.displayPiece(containerId, existingPieces, pieceId);

      // 髱槫酔譛溘〒繧ｵ繝ｼ繝舌・縺ｫ騾夂衍・井ｻ悶・繝ｬ繧､繝､繝ｼ縺ｸ縺ｮ蜷梧悄逕ｨ・・
      this.ajaxcall(
        "/raigo/raigo/debugGeneratePiece.html",
        {
          containerId: containerId,
          persist: persist ? 1 : 0
        },
        this,
        function (result) {
          console.log(`鬧偵ｒ繧ｵ繝ｼ繝舌・邨檎罰縺ｧ逕滓・縺励∪縺励◆: ${containerId} (DB菫晏ｭ・ ${persist})`);
        }
      );
    },

    debugPlacePieces: function () {
      console.log("debugPlacePieces called");
      var self = this;

      // kyoukoku_rival, myself 縺ｫ24蛟九★縺､
      var kyoukokuContainers = ["kyoukoku_rival", "kyoukoku_myself"];
      for (var containerId of kyoukokuContainers) {
        for (var position = 0; position < 24; position++) {
          var randomType = Math.floor(Math.random() * 88) + 1;

          this.ajaxcall(
            "/raigo/raigo/debugGeneratePiece.html",
            {
              containerId: containerId,
              type: randomType,
              persist: 1
            },
            this,
            function () {
              self.displayPiece(containerId, position, `debug-piece-${containerId}-${position}`, randomType, "front");
            }
          );
        }
      }

      // 縺昴・莉悶・繧ｳ繝ｳ繝・リ縺ｫ蜷・蛟九★縺､
      var singleContainers = [
        "inside_rival_1", "inside_rival_2", "inside_rival_3", "inside_myself_1", "inside_myself_2", "inside_myself_3",
        "hand_rival_1", "hand_rival_2", "hand_myself_1", "hand_myself_2",
        "moon_rival", "moon_myself",
        "oumoncircle_rival_1", "oumoncircle_rival_2", "oumoncircle_rival_3", "oumoncircle_myself_1", "oumoncircle_myself_2", "oumoncircle_myself_3",
        "tower_rival_1", "tower_rival_2", "tower_rival_3", "tower_rival_4", "tower_rival_5", "tower_rival_6", "tower_rival_7",
        "tower_myself_1", "tower_myself_2", "tower_myself_3", "tower_myself_4", "tower_myself_5", "tower_myself_6", "tower_myself_7"
      ];

      for (var containerId of singleContainers) {
        var randomType = Math.floor(Math.random() * 88) + 1;

        this.ajaxcall(
          "/raigo/raigo/debugGeneratePiece.html",
          {
            containerId: containerId,
            type: randomType,
            persist: 1
          },
          this,
          function () {
            self.displayPiece(containerId, 0, `debug-piece-${containerId}`, randomType, "front");
          }
        );
      }
    },

    onUpdateGameState: function (stateName, state) {
      // 繧ｲ繝ｼ繝迥ｶ諷九′譖ｴ譁ｰ縺輔ｌ縺溘→縺阪↓繝槭・繧ｫ繝ｼ迥ｶ諷九ｒ繝ｪ繧ｻ繝・ヨ・医碁國縲阪↓謌ｻ縺呻ｼ・
      var phasePanelTop = dojo.byId("raigo-phase-panel-top");
      var phasePanelBottom = dojo.byId("raigo-phase-panel-bottom");

      if (phasePanelTop) {
        dojo.removeClass(phasePanelTop, "phase-state-active");
        dojo.addClass(phasePanelTop, "phase-state-hidden");
      }
      if (phasePanelBottom) {
        dojo.removeClass(phasePanelBottom, "phase-state-active");
        dojo.addClass(phasePanelBottom, "phase-state-hidden");
      }
      // 謇狗分遘ｻ蜍輔ヵ繝ｩ繧ｰ繧偵Μ繧ｻ繝・ヨ
      this.isMovingToNextPlayer = false;
    },

    moveToNextPlayer: function () {
      // 譌｢縺ｫ螳溯｡御ｸｭ縺ｪ繧我ｺ碁㍾螳溯｡後ｒ髦ｲ縺・
      if (this.isMovingToNextPlayer) {
        return;
      }
      this.isMovingToNextPlayer = true;
      var self = this;

      // 謇狗分繧堤ｧｻ蜍輔☆繧帰JAX蜻ｼ縺ｳ蜃ｺ縺・
      this.ajaxcall(
        "/raigo/raigo/nextPhase.html",
        {},
        this,
        function (result) {
          // 謌仙粥譎ゅ・onUpdateGameState縺悟他縺ｰ繧後※繝槭・繧ｫ繝ｼ繧偵Μ繧ｻ繝・ヨ
          self.isMovingToNextPlayer = false;
        },
        function (isErrorNotified) {
          // 繧ｨ繝ｩ繝ｼ縺檎匱逕溘＠縺ｦ繧ゅヵ繝ｩ繧ｰ繧偵Μ繧ｻ繝・ヨ
          self.isMovingToNextPlayer = false;
        }
      );

      // 3遘貞ｾ後↓繧ｿ繧､繝繧｢繧ｦ繝域凾繧ゅヵ繝ｩ繧ｰ繧偵Μ繧ｻ繝・ヨ
      setTimeout(function () {
        if (self.isMovingToNextPlayer) {
          self.isMovingToNextPlayer = false;
        }
      }, 3000);
    }
  });
});

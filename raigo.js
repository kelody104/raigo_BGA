define(["dojo", "dojo/_base/declare", "ebg/core/gamegui"], function (dojo, declare) {
  return declare("bgagame.raigo", ebg.core.gamegui, {
    constructor: function () {
      this.phaseOrder = ["setupGame", "kai", "gen", "sen", "tsumuHatsu", "kakure"];
      this.phaseText = {
        setupGame: { name: "", sub: "セットアップ中" },
        kai: { name: "開", sub: "条件を満たさない場合はスキップ" },
        gen: { name: "現", sub: "insideからhandへ移動" },
        sen: { name: "選", sub: "雷山から駒を引く" },
        tsumuHatsu: { name: "積 / 発", sub: "積：塔に重ねる / 発：峡谷へ置いて効果" },
        kakure: { name: "隠", sub: "隠駒を移動してターン終了" },
      };
      this.phaseShortName = {
        setupGame: null,
        kai: "開",
        gen: "現",
        sen: "選",
        tsumuHatsu: "発",
        kakure: "隠",
      };
      this.phaseIndexMap = {
        setupGame: "00",
        kai: "10",
        gen: "20",
        sen: "30",
        tsumuHatsu: "50",
        kakure: "80"
      };
      this.selectedInsideId = null; // genMove phase中に選択されたinside要素ID
      this.isMovingToNextPlayer = false; // 手番移動中フラグ
    },

    setup: function (gamedatas) {
      this.gamedatas = gamedatas;

      const btn = dojo.byId("raigo-next-phase");
      if (btn) {
        dojo.connect(btn, "onclick", this, "onNextPhase");
      }

      // フェーズパネルのクリックイベント
      const phasePanel = dojo.byId("raigo-phase-panel");
      if (phasePanel) {
        dojo.connect(phasePanel, "onclick", this, "onPhasePanelClick");
      }

      // デバッグボタンの接続
      const debugBtn = dojo.byId("raigo-debug-generate-piece");
      if (debugBtn) {
        dojo.connect(debugBtn, "onclick", this, "onDebugGeneratePiece");
      }

      // サーバーから受け取った駒データを表示
      console.log("[setup] gamedatas.pieces:", gamedatas.pieces);
      if (gamedatas.pieces) {
        for (const piece of gamedatas.pieces) {
          // kyoukoku_rivalの駒は表示しない
          if (piece.piece_container.startsWith("kyoukoku")) {
            continue;
          }
          this.displayPiece(piece.piece_container, piece.piece_position, piece.piece_id, piece.piece_type, piece.piece_face);
        }
      }

      const initialState = (gamedatas.gamestate && gamedatas.gamestate.name) ? gamedatas.gamestate.name : "";
      this.updatePhaseUI(initialState);

      // プレイヤー視点に応じてボードを回転（コメントアウトされた旧ロジック）
      /*
      const board = dojo.byId("raigo-board");
      if (board) {
        const currentPlayerId = this.player_id;
        const playerB = gamedatas.players ? Object.values(gamedatas.players).find(p => p.player_color !== "ffffff") : null;
        const playerBId = playerB ? playerB.player_id : null;
        
        // プレイヤーBの場合、ボードを180度回転
        if (currentPlayerId === playerBId) {
          dojo.addClass(board, "board-rotated");
        }
        
        // デバッグ: 各コンテナにランダムな駒を1つずつ配置
        // this.debugPlacePieces();
      }
      */

      // ボード視点の設定: 各プレイヤーが自分を上側に見えるようにボードを回転
      const board = dojo.byId("raigo-board");
      if (board && this.gamedatas.players) {
        const currentPlayerNo = this.gamedatas.players[this.player_id]?.player_no;
        // プレイヤーNoが2以上の場合はボードを180度回転
        if (currentPlayerNo >= 2) {
          dojo.addClass(board, "board-rotated");
        }
      }



      // Create test pieces
      const innerBoard = dojo.byId("raigo-board");
      if (innerBoard) {

        // 1. Upper group: 16 cols × 2 rows (ura.jpg)
        /* 
        const cols16 = 16;
        const width16 = cols16 * pieceWidth;
        const startLeft16 = (840 - width16) / 2; // Center in 840px container
        for (let row = 0; row < 2; row++) {
          for (let col = 0; col < cols16; col++) {
            const piece = dojo.create("div", { className: "piece", id: `upper-${row}-${col}` }, container);
            piece.style.left = `${startLeft16 + col * pieceWidth}px`;
            piece.style.top = `${currentTop + row * pieceHeight}px`;
          }
        }
        currentTop += 2 * pieceHeight + space;
        */

        // 2. Middle row 1: 24 cols × 1 row (雷.jpg)
        /*
        const cols24 = 24;
        const width24 = cols24 * pieceWidth;
        const startLeft24 = (840 - width24) / 2;
        for (let col = 0; col < cols24; col++) {
          const piece = dojo.create("div", { className: "new-piece", id: `middle1-${col}` }, container);
          piece.style.left = `${startLeft24 + col * pieceWidth}px`;
          piece.style.top = `${currentTop}px`;
        }
        currentTop += pieceHeight + space;
        */

        // 3. Middle row 2: 24 cols × 1 row (雷.jpg)
        /*
        for (let col = 0; col < cols24; col++) {
          const piece = dojo.create("div", { className: "new-piece", id: `middle2-${col}` }, container);
          piece.style.left = `${startLeft24 + col * pieceWidth}px`;
          piece.style.top = `${currentTop}px`;
        }
        currentTop += pieceHeight + space;
        */

        // 4. Lower group: 16 cols × 2 rows (ura.jpg)
        /*
        for (let row = 0; row < 2; row++) {
          for (let col = 0; col < cols16; col++) {
            const piece = dojo.create("div", { className: "piece", id: `lower-${row}-${col}` }, container);
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
        const btn = dojo.byId("btn_next_phase");
        const panelBtn = dojo.byId("raigo-next-phase");
        if (btn) this.setButtonEnabled(btn, true);
        if (panelBtn) this.setButtonEnabled(panelBtn, true);
      }, 800); // Increased to 800ms for safety

      if (stateName === "genMove" && this.isCurrentPlayerActive()) {
        this.setupGenMovePhase();
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

      if (stateName === "genMove") {
        return;
      } else if (stateName === "sen") {
        return;
      } else if (stateName === "tsumuHatsu") {
        // TsumuHatsu phase UI is setup by setupTsumuHatsuPhase
        return;
      }

      const label = this.getNextButtonLabel(stateName);
      this.addActionButton("btn_next_phase", label, "onNextPhase");
    },

    setupNotifications: function () {
      dojo.subscribe("piecePlaced", this, "onPiecePlaced");
      dojo.subscribe("pieceMoved", this, "onPieceMoved");
      dojo.subscribe("insideSelected", this, "onInsideSelected");
      dojo.subscribe("yakuCompleted", this, "onYakuCompleted");
      dojo.subscribe("towerCleared", this, "onTowerCleared");
      dojo.subscribe("kakureMoved", this, "onKakureMoved");
    },

    onYakuCompleted: function (notif) {
      const yakuName = notif.args.yaku_name;
      const score = notif.args.score;
      const towerId = notif.args.towerId;

      this.showMessage(dojo.string.substitute("役完成: ${yaku_name} (${score}点)", {
        yaku_name: yakuName,
        score: score
      }), "info");

      // エフェクトがあればここに追加（例: 塔の発光など）
      const tower = dojo.byId(towerId);
      if (tower) {
        dojo.animateProperty({
          node: tower,
          duration: 1000,
          properties: {
            backgroundColor: { start: "#ffff00", end: "transparent" } // 黄色点滅
          }
        }).play();
      }

      // スコアの更新
      this.scoreCtrl[notif.args.player_id].toValue(notif.args.new_score);
    },

    onTowerCleared: function (notif) {
      const towerId = notif.args.towerId;
      // 実際にはonPieceMovedでexclusionへ移動するはずだが、
      // ここで特定の演出や安全策としてのクリアを行う
      // 今回はonPieceMovedでの移動を信頼し、ここではログ出力のみ
      console.log(`Tower cleared: ${towerId}`);
    },

    setupTsumuHatsuPhase: function () {
      // 自分の手札（hand_myself_1, hand_myself_2）にある駒をクリック可能にする
      const handIds = ["hand_myself_1", "hand_myself_2"];

      for (const handId of handIds) {
        const hand = dojo.byId(handId);
        if (hand) {
          const pieces = dojo.query(".piece", hand);
          pieces.forEach((piece) => {
            // 既存のハンドラがある可能性を考慮して connect
            dojo.addClass(piece, "selectable-piece");
            const handler = dojo.connect(piece, "onclick", this, (function (pId) {
              return (evt) => {
                dojo.stopEvent(evt);
                this.onHandPieceClick(pId, handId);
              };
            }).call(this, piece.id));
            // 注意: クリーンアップが必要だが簡易実装のため省略
            // 本来は phase 終了時に disconnect すべき
          });
        }
      }

      const label = this.getNextButtonLabel("tsumuHatsu");
      this.addActionButton("btn_next_phase", label, "onNextPhase");
    },

    onHandPieceClick: function (pieceId, containerId) {
      if (!this.isCurrentPlayerActive()) return;

      this.selectedPieceId = pieceId.split('-')[2] || pieceId;

      // SELECT された視覚効果
      dojo.query(".selected-piece").removeClass("selected-piece");
      dojo.addClass(dojo.byId(pieceId), "selected-piece");

      // 1. 塔を選択可能にする (tower_myself_*, tower_rival_*)
      dojo.query(".selectable-tower").removeClass("selectable-tower");
      dojo.query(".tower-column").addClass("selectable-tower");

      const towers = dojo.query(".tower-column");
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

      // 2. 峡谷を選択可能にする (kyoukoku_myself)
      // kyoukoku_myself は div#kyoukoku_myself
      const kyoukoku = dojo.byId("kyoukoku_myself");
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
      let idVal = this.selectedPieceId;

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
      let idVal = this.selectedPieceId;

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
      // 自分の塔（tower_myself_1..7）のみ選択可能にする
      dojo.query(".selectable-tower").removeClass("selectable-tower");

      const towers = dojo.query("[id^='tower_myself_']");
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

      const label = this.getNextButtonLabel("kakure");
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

      const towerId = notif.args.towerId;
      const tower = dojo.byId(towerId);
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
      // inside選択時の通知（クライアント側の状態更新）
      this.selectedInsideId = notif.args.insideId;
      console.log(`Selected inside: ${this.selectedInsideId}`);
    },

    onPieceMoved: function (notif) {
      // 駒移動完了時の通知
      const fromContainer = notif.args.fromContainer;
      const toContainer = notif.args.toContainer;
      console.log(`Piece moved from ${fromContainer} to ${toContainer}`);

      // DOM上で駒を移動
      const fromElem = dojo.byId(fromContainer);
      const toElem = dojo.byId(toContainer);
      if (fromElem && toElem) {
        const piece = dojo.query(".piece", fromElem)[0];
        if (piece) {
          dojo.place(piece, toElem);
        }
      }
    },

    onPiecePlaced: function (notif) {
      const containerId = notif.args.container;

      const container = dojo.byId(containerId);
      if (!container) {
        console.error(`コンテナ '${containerId}' が見つかりません`);
        return;
      }

      // 実際のコンテナ内の駒数をカウント
      const existingPieces = dojo.query(".piece", container).length;

      // 駒IDを一意に生成
      const pieceId = `piece-${containerId}-${Date.now()}-${existingPieces}`;
      this.displayPiece(containerId, existingPieces, pieceId);
    },

    setupGenMovePhase: function () {
      // inside要素にクリックハンドラを追加
      const insideIds = ["inside_rival_1", "inside_rival_2", "inside_rival_3"];
      for (const insideId of insideIds) {
        const elem = dojo.byId(insideId);
        if (elem) {
          dojo.connect(elem, "onclick", this, (function (id) {
            return () => this.onInsideClick(id);
          }).call(this, insideId));
        }
      }

      const label = this.getNextButtonLabel("genMove");
      this.addActionButton("btn_next_phase", label, "onNextPhase");
    },

    setupSenPhase: function () {
      // deck要素にクリックハンドラを追加
      const deckIds = ["deck_rival", "deck_myself"];

      // Current number of pieces taken from deck in this phase
      const piecesTaken = this.gamedatas.deck_pieces_taken || 0;
      const canTakeMore = piecesTaken < 2;

      if (!canTakeMore) {
        // Already taken 2 pieces, skip deck interaction
        const label = this.getNextButtonLabel("sen");
        this.addActionButton("btn_next_phase", label, "onNextPhase");
        return;
      }

      for (const deckId of deckIds) {
        const elem = dojo.byId(deckId);
        if (elem) {
          dojo.connect(elem, "onclick", this, (function (id) {
            return () => this.onDeckClick("deck"); // クリックされた場所に関わらずコンテナは 'deck'
          }).call(this, deckId));
        }
      }

      const label = this.getNextButtonLabel("sen");
      this.addActionButton("btn_next_phase", label, "onNextPhase");
    },

    onDeckClick: function (deckId) {
      if (!this.isCurrentPlayerActive()) return;

      // deckId は常に "deck" として渡される
      // 実際のDOM要素は deck_rival または deck_myself を想定
      // ここではクリックされた要素を特定する必要がないため、汎用的な "deck" を使用

      // 駒の存在チェックはサーバー側で行うか、displayPieceのロジックで判断
      // const container = dojo.byId(deckId); // このIDの要素は存在しない可能性が高い
      // if (!container) return;

      // const piece = dojo.query(".piece", container)[0];
      // if (!piece) {
      //   console.log(`No piece in ${deckId}`);
      //   return;
      // }

      // Check if we've already taken 2 pieces
      const piecesTaken = this.gamedatas.deck_pieces_taken || 0;
      if (piecesTaken >= 2) {
        console.log("Already taken 2 pieces from deck");
        return;
      }

      // Clear any previous highlights first
      dojo.query(".available-hand").forEach(function (elem) {
        dojo.removeClass(elem, "available-hand");
      });

      // 利用可能なhand/insideを黄緑化
      const targetIds = ["hand_rival_1", "hand_rival_2", "inside_rival_1", "inside_rival_2", "inside_rival_3"];
      for (const targetId of targetIds) {
        const target = dojo.byId(targetId);
        if (target) {
          const pieces = dojo.query(".piece", target);
          if (pieces.length === 0) {
            // 空いている
            dojo.addClass(target, "available-hand");
            // Store handler reference for later cleanup
            const handler = dojo.connect(target, "onclick", this, (function (tId, dId) {
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

      // サーバーへ移動リクエスト送信
      this.ajaxcall(
        "/raigo/raigo/movePieceFromDeck.html",
        {
          fromContainer: "deck", // コンテナ名は常に 'deck'
          toContainer: targetId
        },
        this,
        (function () {
          // Update piece count after successful move
          const piecesTaken = (this.gamedatas.deck_pieces_taken || 0) + 1;
          this.gamedatas.deck_pieces_taken = piecesTaken;

          // If 2 pieces already taken, disable further deck clicks
          if (piecesTaken >= 2) {
            console.log("2 pieces taken, disabling deck selection");
            // Visually disable decks
            const deckIds = ["deck_rival", "deck_myself"];
            for (const deckId of deckIds) {
              const elem = dojo.byId(deckId);
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

      const container = dojo.byId(insideId);
      if (!container) return;

      const piece = dojo.query(".piece", container)[0];
      if (!piece) {
        console.log(`No piece in ${insideId}`);
        return;
      }

      this.selectedInsideId = insideId;

      // 利用可能なhand（空いているもの）を黄緑化
      const handIds = ["hand_rival_1", "hand_rival_2"];
      for (const handId of handIds) {
        const hand = dojo.byId(handId);
        if (hand) {
          const pieces = dojo.query(".piece", hand);
          if (pieces.length === 0) {
            // 空いている
            dojo.addClass(hand, "available-hand");
            dojo.connect(hand, "onclick", this, (function (hId, iId) {
              return () => this.onHandClick(hId, iId);
            }).call(this, handId, insideId));
          }
        }
      }
    },

    onHandClick: function (handId, insideId) {
      if (!this.isCurrentPlayerActive()) return;

      // サーバーへ移動リクエスト送信
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
      let parentContainerId = containerId;
      let renderPosition = position;

      // deck コンテナの場合は、position に応じて rival/myself に振り分け
      if (containerId === "deck") {
        parentContainerId = (position % 2 === 0) ? "deck_rival" : "deck_myself";
        renderPosition = Math.floor(position / 2);
      }

      const container = dojo.byId(parentContainerId);
      if (!container) {
        console.error(`コンテナ '${parentContainerId}' が見つかりません`);
        return;
      }

      // 駒をコンテナの直接の子として生成
      const piece = dojo.create("div", {
        className: "piece",
        id: pieceId
      }, container);

      piece.style.position = "absolute";

      // 基本寸法
      piece.style.width = "35px";
      piece.style.height = "35px";

      // 表裏に応じてCSSクラスとテキストを付与
      const t = type;
      const pt = (this.gamedatas && this.gamedatas.piece_types && typeof t !== "undefined") ? this.gamedatas.piece_types[t] : null;
      const name = (pt && pt.name) ? pt.name : "";
      const weight = (pt && pt.weight) ? pt.weight : 1;

      if (face === "front") {
        dojo.addClass(piece, "piece-front");
        piece.setAttribute("data-weight", weight);
        piece.textContent = name;
        piece.style.fontWeight = "700";

        // 上側コンテナの駒は180度回転させる（deck除外）
        const topContainers = ["kyoukoku_rival", "hand_rival_1", "hand_rival_2", "inside_rival_1", "inside_rival_2", "inside_rival_3", "oumoncircle_rival_1", "oumoncircle_rival_2", "oumoncircle_rival_3", "moon_rival", "deck_rival", "tower_rival_1", "tower_rival_2", "tower_rival_3", "tower_rival_4", "tower_rival_5", "tower_rival_6", "tower_rival_7"];
        if (topContainers.includes(parentContainerId)) {
          dojo.addClass(piece, "piece-rotated");
        }
      } else {
        dojo.addClass(piece, "piece-back");
        piece.textContent = "";
        piece.style.fontWeight = "700";
      }

      console.log(`[displayPiece] id=${pieceId}, container=${containerId}, type=${type}, face=${face}`);

      // コンテナタイプで異なるレイアウトを適用
      if (parentContainerId.startsWith("kyoukoku")) {
        piece.style.left = `${renderPosition * 35}px`;
        piece.style.top = "0";
      } else if (parentContainerId.startsWith("deck")) {
        const rowIndex = Math.floor(renderPosition / 2);
        const isTopRow = renderPosition % 2 === 0;
        piece.style.left = `${rowIndex * 35}px`;
        piece.style.top = isTopRow ? "0" : "35px";
      } else if (parentContainerId.startsWith("tower_rival")) {
        // tower_rival: 下から上に向かって積み上がる（ライン側から）
        piece.style.left = "0";
        piece.style.top = `${210 - (renderPosition + 1) * 35}px`;
      } else if (parentContainerId.startsWith("tower_myself")) {
        // tower_myself: 上から下に向かって積み上がる（ライン側から）
        piece.style.left = "0";
        piece.style.top = `${renderPosition * 35}px`;
      } else if (parentContainerId === "exclusion") {
        // exclusion: スタック（重ねて表示）
        piece.style.left = "0";
        piece.style.top = "0";
      } else {
        piece.style.left = "calc(50% - 17.5px)";
        piece.style.top = `${renderPosition * 35}px`;
      }

      console.log(`駒を表示しました: ${pieceId} in ${containerId} (位置: ${position}), left=${piece.style.left}, top=${piece.style.top}, backgroundImage=${piece.style.backgroundImage}`);

      // 駒をクリックすると裏表が切り替わる
      dojo.connect(piece, "onclick", this, function (evt) {
        dojo.stopEvent(evt);

        // 現在の状態を判定
        const isFront = dojo.hasClass(piece, "piece-front");
        const newFace = isFront ? "back" : "front";

        // クラスを切り替え
        if (isFront) {
          dojo.removeClass(piece, "piece-front");
          dojo.addClass(piece, "piece-back");
          piece.textContent = "";
        } else {
          dojo.removeClass(piece, "piece-back");
          dojo.addClass(piece, "piece-front");
          const pt = (this.gamedatas && this.gamedatas.piece_types && typeof type !== "undefined") ? this.gamedatas.piece_types[type] : null;
          const name = (pt && pt.name) ? pt.name : "";
          piece.textContent = name;
        }

        console.log(`[togglePiece] ${pieceId}が${newFace}に切り替わりました`);
      });
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
      const idx = this.getPhaseIndex(stateName);
      if (idx === -1) return "次へ";
      if (stateName === "kakure") return "手番終了（次の手番へ）";

      const nextState = this.phaseOrder[idx + 1];
      const nextName = (this.phaseText[nextState] && this.phaseText[nextState].name) ? this.phaseText[nextState].name : "次";
      return `次へ：${nextName}`;
    },

    updatePhaseUI: function (stateName) {
      const nameEl = dojo.byId("raigo-phase-name");
      const subEl = dojo.byId("raigo-phase-sub");
      const btnEl = dojo.byId("raigo-next-phase");
      const panelEl = dojo.byId("raigo-phase-panel");

      if (!nameEl || !subEl || !btnEl) {
        return;
      }

      if (!this.isPhaseState(stateName)) {
        nameEl.innerHTML = "-";
        subEl.innerHTML = "-";
        btnEl.innerHTML = "待機中";
        this.setButtonEnabled(btnEl, false);
        if (panelEl) {
          dojo.removeClass(panelEl, "panel-active-turn");
          dojo.addClass(panelEl, "panel-inactive");
        }
        return;
      }

      const shortName = this.phaseShortName[stateName];
      const phaseNum = this.phaseIndexMap[stateName] || "--";

      // 番号を表示 (raigo.css 側で .phase-number などのスタイルが必要かもしれません)
      const phaseNumHtml = `<div class="phase-number" style="font-size:10px; opacity:0.6; position:absolute; top:5px; left:5px;">${phaseNum}</div>`;

      if (stateName === "tsumuHatsu") {
        nameEl.innerHTML = phaseNumHtml + '<div class="phase-text-container"><span class="phase-tsumuHatsu-left">積</span><span class="phase-tsumuHatsu-right">発</span></div>';
      } else {
        nameEl.innerHTML = phaseNumHtml + (shortName || "");
      }

      subEl.innerHTML = this.isCurrentPlayerActive() ? "あなたの手番です" : "相手の手番です";

      btnEl.innerHTML = this.getNextButtonLabel(stateName);
      this.setButtonEnabled(btnEl, this.isCurrentPlayerActive());

      // ターンに応じてパネルの背景色と位置を変更
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
      const btn = dojo.byId("btn_next_phase"); // Action button
      const panelBtn = dojo.byId("raigo-next-phase"); // Panel button
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
      const btn = dojo.byId("btn_next_phase");
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

      const inputEl = dojo.byId("raigo-debug-container-id");
      const containerId = inputEl ? inputEl.value.trim() : "";

      if (!containerId) {
        alert("コンテナIDを入力してください");
        return;
      }

      const container = dojo.byId(containerId);
      if (!container) {
        alert(`コンテナ '${containerId}' が見つかりません`);
        return;
      }

      // DB保存チェックボックスの状態を取得
      const persistCheckbox = dojo.byId("raigo-debug-persist");
      const persist = persistCheckbox ? persistCheckbox.checked : false;

      // 即座にローカルで駒を生成
      const existingPieces = dojo.query(".piece", container).length;
      const pieceId = `piece-${containerId}-${Date.now()}-${existingPieces}`;
      this.displayPiece(containerId, existingPieces, pieceId);

      // 非同期でサーバーに通知（他プレイヤーへの同期用）
      this.ajaxcall(
        "/raigo/raigo/debugGeneratePiece.html",
        {
          containerId: containerId,
          persist: persist ? 1 : 0
        },
        this,
        function (result) {
          console.log(`駒をサーバー経由で生成しました: ${containerId} (DB保存: ${persist})`);
        }
      );
    },

    debugPlacePieces: function () {
      console.log("debugPlacePieces called");
      var self = this;

      // kyoukoku_rival, myself に24個ずつ
      const kyoukokuContainers = ["kyoukoku_rival", "kyoukoku_myself"];
      for (const containerId of kyoukokuContainers) {
        for (let position = 0; position < 24; position++) {
          const randomType = Math.floor(Math.random() * 88) + 1;

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

      // その他のコンテナに各1個ずつ
      const singleContainers = [
        "inside_rival_1", "inside_rival_2", "inside_rival_3", "inside_myself_1", "inside_myself_2", "inside_myself_3",
        "hand_rival_1", "hand_rival_2", "hand_myself_1", "hand_myself_2",
        "moon_rival", "moon_myself",
        "oumoncircle_rival_1", "oumoncircle_rival_2", "oumoncircle_rival_3", "oumoncircle_myself_1", "oumoncircle_myself_2", "oumoncircle_myself_3",
        "tower_rival_1", "tower_rival_2", "tower_rival_3", "tower_rival_4", "tower_rival_5", "tower_rival_6", "tower_rival_7",
        "tower_myself_1", "tower_myself_2", "tower_myself_3", "tower_myself_4", "tower_myself_5", "tower_myself_6", "tower_myself_7"
      ];

      for (const containerId of singleContainers) {
        const randomType = Math.floor(Math.random() * 88) + 1;

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
      // ゲーム状態が更新されたときにマーカー状態をリセット（「隠」に戻す）
      const phasePanelTop = dojo.byId("raigo-phase-panel-top");
      const phasePanelBottom = dojo.byId("raigo-phase-panel-bottom");

      if (phasePanelTop) {
        dojo.removeClass(phasePanelTop, "phase-state-active");
        dojo.addClass(phasePanelTop, "phase-state-hidden");
      }
      if (phasePanelBottom) {
        dojo.removeClass(phasePanelBottom, "phase-state-active");
        dojo.addClass(phasePanelBottom, "phase-state-hidden");
      }
      // 手番移動フラグをリセット
      this.isMovingToNextPlayer = false;
    },

    moveToNextPlayer: function () {
      // 既に実行中なら二重実行を防ぐ
      if (this.isMovingToNextPlayer) {
        return;
      }
      this.isMovingToNextPlayer = true;
      const self = this;

      // 手番を移動するAJAX呼び出し
      this.ajaxcall(
        "/raigo/raigo/nextPhase.html",
        {},
        this,
        function (result) {
          // 成功時はonUpdateGameStateが呼ばれてマーカーをリセット
          self.isMovingToNextPlayer = false;
        },
        function (isErrorNotified) {
          // エラーが発生してもフラグをリセット
          self.isMovingToNextPlayer = false;
        }
      );

      // 3秒後にタイムアウト時もフラグをリセット
      setTimeout(function () {
        if (self.isMovingToNextPlayer) {
          self.isMovingToNextPlayer = false;
        }
      }, 3000);
    }
  });
});
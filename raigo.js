define(["dojo", "dojo/_base/declare", "ebg/core/gamegui"], function (dojo, declare) {
  return declare("bgagame.raigo", ebg.core.gamegui, {
    constructor: function () {
      this.phaseOrder = ["kai", "genMove", "gen", "sen", "tsumuHatsu", "in"];
      this.phaseText = {
        kai: { name: "開", sub: "条件を満たさない場合はスキップ" },
        genMove: { name: "現・移動", sub: "insideの駒をhandに移動" },
        gen: { name: "現", sub: "自分のプレイマット上の駒の移動" },
        sen: { name: "選", sub: "雷山（山札）から駒を引く" },
        tsumuHatsu: { name: "積 / 発", sub: "積：塔に重ねる / 発：峡谷へ置いて効果" },
        in: { name: "隠", sub: "手番を終了する" },
      };
      this.phaseShortName = {
        kai: "開",
        genMove: "現",
        gen: "現",
        sen: "選",
        tsumuHatsu: "発",
        in: "隠",
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
          // kyoukokuAの駒は表示しない
          if (piece.piece_container.startsWith("kyoukoku")) {
            continue;
          }
          this.displayPiece(piece.piece_container, piece.piece_position, piece.piece_id, piece.piece_type, piece.piece_face);
        }
      }

      const initialState = (gamedatas.gamestate && gamedatas.gamestate.name) ? gamedatas.gamestate.name : "";
      this.updatePhaseUI(initialState);

      // プレイヤー視点に応じてボードを回転
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
        this.debugPlacePieces();
      }
      */
      

      // ボード視点の設定: 各プレイヤーが自分を上側に見えるようにボードを回転
      const board = dojo.byId("raigo-board");
      if (board && this.gamedatas.players) {
        const players = Object.values(this.gamedatas.players);
        const currentPlayerNo = this.gamedatas.players[this.player_id]?.player_no;
        
        // プレイヤーNoが2以上の場合はボードを180度回転
        if (currentPlayerNo >= 2) {
          dojo.addClass(board, "board-rotated");
        }
      }

      // 新規マーカーパネルハンドラー（コメントアウト）
      /*
      const phasePanelTop = dojo.byId("raigo-phase-panel-top");
      const phasePanelBottom = dojo.byId("raigo-phase-panel-bottom");
      
      if (phasePanelTop && phasePanelBottom) {
        // 初期状態を「隠」に設定
        dojo.addClass(phasePanelTop, "phase-state-hidden");
        dojo.addClass(phasePanelBottom, "phase-state-hidden");
        
        const self = this;
        // 下のマーカーのみクリック可能
        dojo.connect(phasePanelBottom, "onclick", this, function(evt) {
          dojo.stopEvent(evt);
          
          if (dojo.hasClass(phasePanelBottom, "phase-state-hidden")) {
            // 「隠」状態 → 「積/発」状態に遷移
            dojo.removeClass(phasePanelBottom, "phase-state-hidden");
            dojo.addClass(phasePanelBottom, "phase-state-active");
            // 上のマーカーも同じ状態に
            dojo.removeClass(phasePanelTop, "phase-state-hidden");
            dojo.addClass(phasePanelTop, "phase-state-active");
          } else if (dojo.hasClass(phasePanelBottom, "phase-state-active")) {
            // 「積/発」状態 → 手番を移動（1度だけ実行）
            dojo.removeClass(phasePanelBottom, "phase-state-active");
            dojo.removeClass(phasePanelTop, "phase-state-active");
            self.moveToNextPlayer();
          }
        });
      }
      */

      // 従来のフェーズパネルハンドラー
      const phasePanelEl = dojo.byId("raigo-phase-panel");
      if (phasePanelEl) {
        // 初期状態を「隠」に設定
        dojo.addClass(phasePanelEl, "phase-state-hidden");
        
        const self = this;
        dojo.connect(phasePanelEl, "onclick", this, function(evt) {
          dojo.stopEvent(evt);
          
          if (dojo.hasClass(phasePanelEl, "phase-state-hidden")) {
            // 「隠」状態 → 「積/発」状態に遷移
            dojo.removeClass(phasePanelEl, "phase-state-hidden");
            dojo.addClass(phasePanelEl, "phase-state-active");
          } else if (dojo.hasClass(phasePanelEl, "phase-state-active")) {
            // 「積/発」状態 → 手番を移動（1度だけ実行）
            dojo.removeClass(phasePanelEl, "phase-state-active");
            self.moveToNextPlayer();
          }
        });
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
      if (stateName === "genMove" && this.isCurrentPlayerActive()) {
        this.setupGenMovePhase();
      } else if (stateName === "sen" && this.isCurrentPlayerActive()) {
        this.setupSenPhase();
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
        // genMoveフェーズは手動でセットアップ
        return;
      } else if (stateName === "sen") {
        // senフェーズは手動でセットアップ
        return;
      }

      const label = this.getNextButtonLabel(stateName);
      this.addActionButton("btn_next_phase", label, "onNextPhase");
    },

    setupNotifications: function () {
      dojo.subscribe("piecePlaced", this, "onPiecePlaced");
      dojo.subscribe("pieceMoved", this, "onPieceMoved");
      dojo.subscribe("insideSelected", this, "onInsideSelected");
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
      const insideIds = ["insideA1", "insideA2", "insideA3"];
      for (const insideId of insideIds) {
        const elem = dojo.byId(insideId);
        if (elem) {
          dojo.connect(elem, "onclick", this, (function(id) {
            return () => this.onInsideClick(id);
          }).call(this, insideId));
        }
      }
      
      const label = this.getNextButtonLabel("genMove");
      this.addActionButton("btn_next_phase", label, "onNextPhase");
    },

    setupSenPhase: function () {
      // deck要素にクリックハンドラを追加
      const deckIds = ["deckA", "deckB"];
      
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
          dojo.connect(elem, "onclick", this, (function(id) {
            return () => this.onDeckClick(id);
          }).call(this, deckId));
        }
      }
      
      const label = this.getNextButtonLabel("sen");
      this.addActionButton("btn_next_phase", label, "onNextPhase");
    },

    onDeckClick: function (deckId) {
      if (!this.isCurrentPlayerActive()) return;
      
      const container = dojo.byId(deckId);
      if (!container) return;
      
      const piece = dojo.query(".piece", container)[0];
      if (!piece) {
        console.log(`No piece in ${deckId}`);
        return;
      }

      // Check if we've already taken 2 pieces
      const piecesTaken = this.gamedatas.deck_pieces_taken || 0;
      if (piecesTaken >= 2) {
        console.log("Already taken 2 pieces from deck");
        return;
      }

      // Clear any previous highlights first
      dojo.query(".available-hand").forEach(function(elem) {
        dojo.removeClass(elem, "available-hand");
      });

      // 利用可能なhand/insideを黄緑化
      const targetIds = ["handA1", "handA2", "insideA1", "insideA2", "insideA3"];
      for (const targetId of targetIds) {
        const target = dojo.byId(targetId);
        if (target) {
          const pieces = dojo.query(".piece", target);
          if (pieces.length === 0) {
            // 空いている
            dojo.addClass(target, "available-hand");
            // Store handler reference for later cleanup
            const handler = dojo.connect(target, "onclick", this, (function(tId, dId) {
              return () => {
                // Disconnect all target handlers first
                dojo.query(".available-hand").forEach(function(elem) {
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
      dojo.query(".available-hand").forEach(function(elem) {
        dojo.removeClass(elem, "available-hand");
      });

      // サーバーへ移動リクエスト送信
      this.ajaxcall(
        "/raigo/raigo/movePieceFromDeck.html",
        { 
          fromContainer: deckId,
          toContainer: targetId
        },
        this,
        (function() {
          // Update piece count after successful move
          const piecesTaken = (this.gamedatas.deck_pieces_taken || 0) + 1;
          this.gamedatas.deck_pieces_taken = piecesTaken;
          
          // If 2 pieces already taken, disable further deck clicks
          if (piecesTaken >= 2) {
            console.log("2 pieces taken, disabling deck selection");
            // Visually disable decks
            const deckIds = ["deckA", "deckB"];
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
      const handIds = ["handA1", "handA2"];
      for (const handId of handIds) {
        const hand = dojo.byId(handId);
        if (hand) {
          const pieces = dojo.query(".piece", hand);
          if (pieces.length === 0) {
            // 空いている
            dojo.addClass(hand, "available-hand");
            dojo.connect(hand, "onclick", this, (function(hId, iId) {
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
        (function() {
          dojo.query(".available-hand").forEach((function(hand) {
            dojo.removeClass(hand, "available-hand");
          }).bind(this));
        }).bind(this)
      );
    },

    displayPiece: function (containerId, position, pieceId, type, face) {
      const container = dojo.byId(containerId);
      if (!container) {
        console.error(`コンテナ '${containerId}' が見つかりません`);
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
        const topContainers = ["kyoukokuA", "handA1", "handA2", "insideA1", "insideA2", "insideA3", "oumoncircleA1", "oumoncircleA2", "oumoncircleA3", "moonA", "deckA", "towerA1", "towerA2", "towerA3", "towerA4", "towerA5", "towerA6", "towerA7"];
        if (topContainers.includes(containerId)) {
          dojo.addClass(piece, "piece-rotated");
        }
      } else {
        dojo.addClass(piece, "piece-back");
        piece.textContent = "";
        piece.style.fontWeight = "700";
      }

      console.log(`[displayPiece] id=${pieceId}, container=${containerId}, type=${type}, face=${face}`);

      // コンテナタイプで異なるレイアウトを適用
      if (containerId.startsWith("kyoukoku")) {
        piece.style.left = `${position * 35}px`;
        piece.style.top = "0";
      } else if (containerId.startsWith("deck")) {
        const rowIndex = Math.floor(position / 2);
        const isTopRow = position % 2 === 0;
        piece.style.left = `${rowIndex * 35}px`;
        piece.style.top = isTopRow ? "0" : "35px";
      } else if (containerId.startsWith("towerA")) {
        // towerA: 下から上に向かって積み上がる（ライン側から）
        piece.style.left = "0";
        piece.style.top = `${210 - (position + 1) * 35}px`;
      } else if (containerId.startsWith("towerB")) {
        // towerB: 上から下に向かって積み上がる（ライン側から）
        piece.style.left = "0";
        piece.style.top = `${position * 35}px`;
      } else if (containerId === "exclusion") {
        // exclusion: スタック（重ねて表示）
        piece.style.left = "0";
        piece.style.top = "0";
      } else {
        piece.style.left = "calc(50% - 17.5px)";
        piece.style.top = `${position * 35}px`;
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

      if (stateName === "tsumuHatsu") {
        nameEl.innerHTML = '<span class="phase-tsumuHatsu"><span class="phase-tsumuHatsu-left">積</span><span class="phase-tsumuHatsu-right">発</span></span>';
      } else {
        nameEl.innerHTML = shortName;
      }
      
      subEl.innerHTML = this.isCurrentPlayerActive() ? "あなたの手番です" : "相手の手番です";

      btnEl.innerHTML = this.getNextButtonLabel(stateName);
      this.setButtonEnabled(btnEl, this.isCurrentPlayerActive());

      // ターンに応じてパネルの背景色と位置を変更
      if (panelEl) {
        if (this.isCurrentPlayerActive()) {
          dojo.removeClass(panelEl, "panel-inactive");
          dojo.addClass(panelEl, "panel-active-turn");
        } else {
          dojo.removeClass(panelEl, "panel-active-turn");
          dojo.addClass(panelEl, "panel-inactive");
        }
      }
    },

    onPhasePanelClick: function (evt) {
      dojo.stopEvent(evt);
      
      if (!this.checkAction("nextPhase")) {
        return;
      }

      this.ajaxcall(
        "/raigo/raigo/nextPhase.html",
        {},
        this,
        function () {},
        function () {}
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
      
      // kyoukokuA, B に24個ずつ
      const kyoukokuContainers = ["kyoukokuA", "kyoukokuB"];
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
            function() {
              self.displayPiece(containerId, position, `debug-piece-${containerId}-${position}`, randomType, "front");
            }
          );
        }
      }
      
      // その他のコンテナに各1個ずつ
      const singleContainers = [
        "insideA1", "insideA2", "insideA3", "insideB1", "insideB2", "insideB3",
        "handA1", "handA2", "handB1", "handB2",
        "moonA", "moonB",
        "oumoncircleA1", "oumoncircleA2", "oumoncircleA3", "oumoncircleB1", "oumoncircleB2", "oumoncircleB3",
        "towerA1", "towerA2", "towerA3", "towerA4", "towerA5", "towerA6", "towerA7",
        "towerB1", "towerB2", "towerB3", "towerB4", "towerB5", "towerB6", "towerB7"
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
          function() {
            self.displayPiece(containerId, 0, `debug-piece-${containerId}`, randomType, "front");
          }
        );
      }
    },

    onUpdateGameState: function(stateName, state) {
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

    moveToNextPlayer: function() {
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
        function(result) {
          // 成功時はonUpdateGameStateが呼ばれてマーカーをリセット
          self.isMovingToNextPlayer = false;
        },
        function(isErrorNotified) {
          // エラーが発生してもフラグをリセット
          self.isMovingToNextPlayer = false;
        }
      );
      
      // 3秒後にタイムアウト時もフラグをリセット
      setTimeout(function() {
        if (self.isMovingToNextPlayer) {
          self.isMovingToNextPlayer = false;
        }
      }, 3000);
    }
  });
});
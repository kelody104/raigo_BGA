<?php
require_once(APP_GAMEMODULE_PATH . 'module/table/table.game.php');

class Raigo extends Table
{
    public $piece_types;
    public $game_setup;

    public function __construct()
    {
        parent::__construct();
        self::initGameStateLabels(array(
            'kai_skip' => 10,
            'deck_pieces_taken' => 0
        ));
        
        // 駒定義と初期配置定義を読み込み
        require_once('material.inc.php');
    }

    protected function getGameName()
    {
        return "raigo";
    }

    protected function setupNewGame($players, $options = array())
    {
        $gameinfos = self::getGameinfos();
        $default_colors = $gameinfos['player_colors'];

        $sql = "INSERT INTO player (player_id, player_no, player_color, player_canal, player_name, player_avatar) VALUES ";
        $values = array();

        $player_no = 1;
        foreach ($players as $player_id => $player) {
            $color = array_shift($default_colors);

            $values[] =
                "('" . $player_id . "'," . $player_no . ",'" . $color . "','" . $player['player_canal'] . "','" .
                addslashes($player['player_name']) . "','" . addslashes($player['player_avatar']) . "')";
            $player_no++;
        }

        $sql .= implode(',', $values);
        self::DbQuery($sql);

        self::reloadPlayersBasicInfos();

        $opt = $options[100] ?? 1;
        $skipKai = ($opt == 2) ? 1 : 0;
        self::setGameStateInitialValue('kai_skip', $skipKai);

        // 駒テーブルを初期化
        self::DbQuery("DELETE FROM piece");

        // game_setup 定義に基づいて駒を配置
        foreach ($this->game_setup as $container => $pieces) {
            $position = 0;
            foreach ($pieces as $pieceData) {
                $type = $pieceData['type'];
                $face = $pieceData['face'];
                self::DbQuery("INSERT INTO piece (piece_container, piece_position, piece_type, piece_face) VALUES ('$container', $position, $type, '$face')");
                $position++;
            }
        }

        self::activeNextPlayer();
    }

    protected function getAllDatas(): array
    {
        $pieces = self::getObjectListFromDB("SELECT piece_id, piece_container, piece_position, piece_type, piece_face FROM piece ORDER BY piece_position");
        
        // deck_pieces_taken の値を安全に取得（存在しない場合は0）
        $deckPiecesTaken = 0;
        try {
            $deckPiecesTaken = (int) self::getGameStateValue('deck_pieces_taken');
        } catch (Exception $e) {
            $deckPiecesTaken = 0;
        }

        // 現在の手番プレイヤーIDを取得
        $activePlayerId = $this->getActivePlayerId();

        return array(
            'players' => self::loadPlayersBasicInfos(),
            'pieces' => $pieces,
            'piece_types' => $this->piece_types,
            'gamestate' => $this->gamestate->state(),
            'deck_pieces_taken' => $deckPiecesTaken,
            'active_player_id' => $activePlayerId
        );
    }

    public function getGameProgression()
    {
        return 0;
    }

    public function stGameSetup()
    {
        // First time setup: transition to initial phase
        $this->gamestate->nextState("");
    }

    public function stDebug()
    {
        // デバッグフェーズの初期化処理があればここに記述
    }

    public function stKai()
    {
        $playerId = $this->getActivePlayerId();

        $this->notifyAllPlayers(
            "log",
            clienttranslate('${player_name} skips Kai phase (開)'),
            array(
                "player_name" => $this->getPlayerNameById($playerId)
            )
        );
        $this->gamestate->nextState("skip");
    }

    public function stSen()
    {
        // Reset the deck pieces taken counter for this phase
        try {
            self::setGameStateValue('deck_pieces_taken', 0);
        } catch (Exception $e) {
            // ラベルが存在しない場合は無視
        }
    }

    public function nextPhase()
    {
        self::checkAction("nextPhase");

        $stateName = $this->gamestate->state()["name"];

        if ($stateName === "in") {
            $this->gamestate->nextState("endTurn");
        } else if ($stateName === "genMove") {
            $this->gamestate->nextState("next");
        } else {
            $this->gamestate->nextState("next");
        }
    }

    public function selectInsidePiece($insideId)
    {
        self::checkAction("selectInsidePiece");
        $playerId = $this->getActivePlayerId();
        $this->notifyPlayer($playerId, "insideSelected", "", array("insideId" => $insideId));
    }

    public function selectDeckPiece($deckId)
    {
        self::checkAction("selectDeckPiece");
        $playerId = $this->getActivePlayerId();
        $this->notifyPlayer($playerId, "deckSelected", "", array("deckId" => $deckId));
    }

    public function movePieceFromDeck($fromContainer, $toContainer)
    {
        self::checkAction("movePieceFromDeck");
        
        // Check if already taken 2 pieces from deck
        $piecesAlreadyTaken = 0;
        try {
            $piecesAlreadyTaken = (int) self::getGameStateValue('deck_pieces_taken');
        } catch (Exception $e) {
            $piecesAlreadyTaken = 0;
        }
        
        if ($piecesAlreadyTaken >= 2) {
            throw new BgaUserException(clienttranslate("You can only take 2 pieces from deck per phase"));
        }
        
        $playerId = $this->getActivePlayerId();

        // Move piece from deck to target (hand/inside)
        $piece = self::getObjectFromDB("SELECT piece_id, piece_position FROM piece WHERE piece_container = '$fromContainer' LIMIT 1");
        if ($piece) {
            self::DbQuery("UPDATE piece SET piece_container = '$toContainer', piece_position = 0 WHERE piece_id = " . $piece['piece_id']);
            
            // Increment the counter
            try {
                self::setGameStateValue('deck_pieces_taken', $piecesAlreadyTaken + 1);
            } catch (Exception $e) {
                // ラベルが存在しない場合は無視
            }
            
            $this->notifyAllPlayers("pieceMoved", "", array("fromContainer" => $fromContainer, "toContainer" => $toContainer));
        }
    }

    public function movePieceToContainer($fromContainer, $toContainer)
    {
        self::checkAction("movePieceToHand");
        $playerId = $this->getActivePlayerId();

        // DBから駒を移動
        $piece = self::getObjectFromDB("SELECT piece_id, piece_position FROM piece WHERE piece_container = '$fromContainer' LIMIT 1");
        if ($piece) {
            self::DbQuery("UPDATE piece SET piece_container = '$toContainer', piece_position = 0 WHERE piece_id = " . $piece['piece_id']);
            $this->notifyAllPlayers("pieceMoved", "", array("fromContainer" => $fromContainer, "toContainer" => $toContainer));
        }
    }

    public function stEndTurn()
    {
        $this->activeNextPlayer();
        $this->gamestate->nextState("next");
    }

    public function zombieTurn($state, $active_player)
    {
        $stateName = $state["name"];

        if ($stateName === "in") {
            $this->gamestate->nextState("endTurn");
            return;
        }

        if (in_array($stateName, array("kai", "gen", "sen", "tsumuHatsu"), true)) {
            $this->gamestate->nextState("next");
            return;
        }

        $this->gamestate->nextState("next");
    }

    public function upgradeTableDb($from_version)
    {
    }

    private function canDoKai($playerId)
    {
        return !$this->isKaiSkipped();
    }

    private function isKaiSkipped()
    {
        return (int) self::getGameStateValue('kai_skip') === 1;
    }

    public function getContainerPieceCount($containerId)
    {
        // コンテナ内の駒数を取得
        $count = self::getUniqueValueFromDB("SELECT COUNT(*) FROM piece WHERE piece_container = '$containerId'");
        return (int)$count;
    }

    public function addPieceToContainer($containerId)
    {
        // 現在の駒数を取得
        $position = $this->getContainerPieceCount($containerId);
        
        // 新しい駒を追加
        self::DbQuery("INSERT INTO piece (piece_container, piece_position, piece_type) VALUES ('$containerId', $position, 'ura')");
        
        return $position;
    }

    public function saveDebugPiece($containerId, $type, $face = 'front')
    {
        // 現在の駒数を取得
        $position = $this->getContainerPieceCount($containerId);
        
        // テスト駒をDBに保存
        self::DbQuery("INSERT INTO piece (piece_container, piece_position, piece_type, piece_face) VALUES ('$containerId', $position, $type, '$face')");
        
        $pieceId = self::DbQuery("SELECT LAST_INSERT_ID() as id");
        return $pieceId[0]['id'] ?? null;
    }

    public function debugFlipPiece($pieceId)
    {
        // 駒の表裏を取得
        $piece = self::getObjectFromDB("SELECT piece_id, piece_face, piece_container, piece_position FROM piece WHERE piece_id = $pieceId");
        
        if (!$piece) {
            throw new BgaUserException("Piece not found");
        }

        // 表裏を切り替え
        $newFace = ($piece['piece_face'] === 'front') ? 'back' : 'front';
        self::DbQuery("UPDATE piece SET piece_face = '$newFace' WHERE piece_id = $pieceId");

        // 通知
        $this->notifyAllPlayers("debugPieceFlipped", "", array(
            "pieceId" => $pieceId,
            "newFace" => $newFace
        ));
    }

    public function debugMovePiece($pieceId, $toContainer)
    {
        // 駒の現在の位置を取得
        $piece = self::getObjectFromDB("SELECT piece_id, piece_container, piece_position FROM piece WHERE piece_id = $pieceId");
        
        if (!$piece) {
            throw new BgaUserException("Piece not found");
        }

        $fromContainer = $piece['piece_container'];

        // 移動先コンテナの駒数を取得して新しい位置を決定
        $newPosition = $this->getContainerPieceCount($toContainer);

        // 駒を移動
        self::DbQuery("UPDATE piece SET piece_container = '$toContainer', piece_position = $newPosition WHERE piece_id = $pieceId");

        // 移動元のコンテナの駒位置を再調整
        $piecesInContainer = self::getObjectListFromDB("SELECT piece_id, piece_position FROM piece WHERE piece_container = '$fromContainer' ORDER BY piece_position");
        $newPos = 0;
        foreach ($piecesInContainer as $p) {
            self::DbQuery("UPDATE piece SET piece_position = $newPos WHERE piece_id = " . $p['piece_id']);
            $newPos++;
        }

        // 通知
        $this->notifyAllPlayers("debugPieceMoved", "", array(
            "pieceId" => $pieceId,
            "fromContainer" => $fromContainer,
            "toContainer" => $toContainer,
            "newPosition" => $newPosition
        ));
    }
}
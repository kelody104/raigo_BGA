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
            'deck_pieces_taken' => 0,
            'kakure_pos' => 4 // 初期位置は中央(4)とする、またはセットアップでランダムなど要検討。とりあえず4。
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

        // game_setup 定義に基づいて駒（山札・除外駒）を配置
        foreach ($this->game_setup as $container => $pieces) {
            $position = 0;
            foreach ($pieces as $pieceData) {
                $type = $pieceData['type'];
                $face = $pieceData['face'];
                self::DbQuery("INSERT INTO piece (piece_container, piece_position, piece_type, piece_face) VALUES ('$container', $position, $type, '$face')");
                $position++;
            }
        }

        // --- 奥義駒 (hand3) の配布 ---
        // weight=9 かつ available=true の駒IDを取得
        $okugiCandidates = array();
        foreach ($this->piece_types as $pId => $pData) {
            if ($pData['weight'] == 9 && isset($pData['available']) && $pData['available'] === true) {
                $okugiCandidates[] = $pId;
            }
        }

        // 各プレイヤーに3枚ずつ配布
        foreach ($players as $player_id => $player) {
            // 候補からランダムに3つ選ぶ（重複なし）
            $keys = array_rand($okugiCandidates, 3);
            if (!is_array($keys)) $keys = array($keys); // 候補が少ない場合の安全策

            $pos = 0;
            foreach ($keys as $key) {
                $typeId = $okugiCandidates[$key];
                // hand3_p{player_id} コンテナに配置
                $container = 'hand3_p' . $player_id;
                self::DbQuery("INSERT INTO piece (piece_container, piece_position, piece_type, piece_face) VALUES ('$container', $pos, $typeId, 'front')");
                $pos++;
            }
        }

        // --- 先手後手の決定 ---
        self::activeNextPlayer(); // BGA標準のランダム決定を利用（初期は0なのでランダムに1人目が選ばれるはず）
        $firstPlayerId = self::getActivePlayerId();

        // --- 初期手札の配布 ---
        // 先手: 1枚, 後手: 2枚
        $pIds = array_keys($players);
        // 先手が $activePlayerId なので、そのプレイヤーから順に処理
        // ただし BGA の activeNextPlayer は setup 段階では単純にプレイヤー順序を回すだけかもしれないが
        // ここでは明示的に判定して配布する
        
        foreach ($players as $player_id => $player) {
            $count = ($player_id == $firstPlayerId) ? 1 : 2;
            
            // deck から $count 枚取得して hand_p{player_id} へ移動
            // piece_container='deck' の駒を position 順（上から）またはランダムに取得
            // ここでは既にシャッフル済みなので position 若い順に取得
            $sql = "SELECT piece_id FROM piece WHERE piece_container = 'deck' ORDER BY piece_position ASC LIMIT $count";
            $cardsToDraw = self::getObjectListFromDB($sql);
            
            foreach ($cardsToDraw as $c) {
                $pIdDb = $c['piece_id'];
                $handContainer = 'hand_p' . $player_id;
                self::DbQuery("UPDATE piece SET piece_container = '$handContainer', piece_position = 0 WHERE piece_id = $pIdDb");
            }
            
            // deck の position を詰める処理が必要だが、BGA標準Deckクラスを使っていないため
            // 手動でやるか、あるいは position が飛び飛びでも問題ない設計にするか。
            // ここでは一旦そのままにする（取得時に ORDER BY piece_position すれば順不同でも上から取れるため）
        }

        // --- 初期スコア設定 (2点) ---
        self::DbQuery("UPDATE player SET player_score = 2");
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

    public function stGen()
    {
        // Gen phase initialization
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

    public function stTsumuHatsu()
    {
        // TsumuHatsu phase initialization
    }

    public function stKakure()
    {
        // Kakure phase initialization
    }

    public function stEndTurn()
    {
        // Active next player
        $playerId = self::activeNextPlayer();
        self::giveExtraTime($playerId);
        
        $this->gamestate->nextState("next");
    }

    /*
     * 役判定ロジック
     * 塔の駒配列（上から順）を受け取り、成立している役情報を返す
     */
    public function calculateHand($towerPieces)
    {
        if (empty($towerPieces) || count($towerPieces) < 2) {
            return null;
        }

        // 駒の詳細情報を付与（name, weight）
        $pieces = $this->_hydratePieces($towerPieces);
        $count = count($pieces);
        $names = array_column($pieces, 'name');
        $weights = array_column($pieces, 'weight');

        // 1. 雷神役 (Raijin-yaku) - 6枚限定
        if ($count === 6) {
            $raijin = $this->_checkRaijinYaku($names);
            if ($raijin) return $raijin;
        }

        // 2. 特殊役 (Tokushu-yaku) - 6枚限定
        if ($count === 6) {
            $tokushu = $this->_checkTokushuYaku($pieces);
            if ($tokushu) return $tokushu;
        }

        // 3. 組み合わせ役 (Kumiai-yaku) - 6枚限定
        if ($count === 6) {
            $kumiai = $this->_checkKumiaiYaku($weights, $pieces);
            if ($kumiai) return $kumiai;
        }

        // 4. 基本役 (Kihon-yaku) - 3枚以上
        if ($count >= 3) {
            $kihon = $this->_checkKihonYaku($weights, $pieces);
            if ($kihon) return $kihon;
        }

        return null;
    }

    private function _hydratePieces($towerPieces)
    {
        $hydrated = array();
        foreach ($towerPieces as $p) {
            $typeId = $p['piece_type'];
            if (isset($this->piece_types[$typeId])) {
                $def = $this->piece_types[$typeId];
                $p['name'] = $def['name'];
                $p['weight'] = (int)$def['weight'];
                $hydrated[] = $p;
            }
        }
        return $hydrated;
    }

    private function _checkRaijinYaku($names)
    {
        $seq = implode('', $names);

        // 時雨蓮花: 斬陣轟霧瞬浄
        if ($seq === '斬陣轟霧瞬浄') return array('name' => '時雨蓮花', 'score' => 10, 'type' => 'raijin');
        
        // 桜花乱舞: 五轟五轟五轟 OR 轟五轟五轟五
        if ($seq === '五轟五轟五轟' || $seq === '轟五轟五轟五') return array('name' => '桜花乱舞', 'score' => 11, 'type' => 'raijin');

        // 霞・双頭連: 轟轟轟霧瞬浄
        if ($seq === '轟轟轟霧瞬浄') return array('name' => '霞・双頭連', 'score' => 11, 'type' => 'raijin');

        // 霧幻輪廻: 六霧六霧六霧 OR 霧六霧六霧六
        if ($seq === '六霧六霧六霧' || $seq === '霧六霧六霧六') return array('name' => '霧幻輪廻', 'score' => 12, 'type' => 'raijin');

        // 毘沙門櫓: 斬斬斬斬斬斬
        if ($seq === '斬斬斬斬斬斬') return array('name' => '毘沙門櫓', 'score' => 12, 'type' => 'raijin');

        // 六花白雪: 六六霧霧瞬瞬
        if ($seq === '六六霧霧瞬瞬') return array('name' => '六花白雪', 'score' => 12, 'type' => 'raijin');

        return null;
    }

    private function _checkTokushuYaku($pieces)
    {
        // ペア判定用ヘルパー
        $isPairMatch = function($i, $j, $checkName) use ($pieces) {
            $p1 = $pieces[$i];
            $p2 = $pieces[$j];
            $wMatch = ($p1['weight'] === $p2['weight']);
            if (!$wMatch) return false;
            if ($checkName) {
                return ($p1['name'] === $p2['name']);
            }
            return true;
        };

        // 椿: (1,2), (3,4), (5,6) が名前・重さ共に一致
        if ($isPairMatch(0, 1, true) && $isPairMatch(2, 3, true) && $isPairMatch(4, 5, true)) {
            return array('name' => '椿', 'score' => 6, 'type' => 'tokushu');
        }

        // 山茶花: (1,2), (3,4), (5,6) が重さ一致
        if ($isPairMatch(0, 1, false) && $isPairMatch(2, 3, false) && $isPairMatch(4, 5, false)) {
            return array('name' => '山茶花', 'score' => 4, 'type' => 'tokushu');
        }

        return null;
    }

    private function _checkKumiaiYaku($weights, $pieces)
    {
        // 上3枚と下3枚に分割
        $upper = array_slice($weights, 0, 3);
        $lower = array_slice($weights, 3, 3);

        $isOuka = function($w) { return count(array_unique($w)) === 1; };
        $isRenge = function($w) {
            // 連番チェック (3,4,5 or 5,4,3 etc)
            // 仕様書例: 4,5,6 (上から下へ)
            // ここでは単純に差分が1であることを確認
            return (abs($w[0] - $w[1]) === 1 && abs($w[1] - $w[2]) === 1 && ($w[0] - $w[1]) === ($w[1] - $w[2]));
        };

        $uOuka = $isOuka($upper);
        $lOuka = $isOuka($lower);
        $uRenge = $isRenge($upper);
        $lRenge = $isRenge($lower);

        $bonus = $this->_calculateBonus($pieces, 4); // 組み合わせ役は基本点が高いのでここで計算

        // 桜々花 (4点)
        if ($uOuka && $lOuka) return $this->_makeYakuResult('桜々花', 4, $bonus);
        
        // 桜蓮花 (3点)
        if ($uOuka && $lRenge) return $this->_makeYakuResult('桜蓮花', 3, $bonus);

        // 蓮桜花 (3点)
        if ($uRenge && $lOuka) return $this->_makeYakuResult('蓮桜花', 3, $bonus);

        // 蓮々花 (2点)
        if ($uRenge && $lRenge) return $this->_makeYakuResult('蓮々花', 2, $bonus);

        return null;
    }

    private function _checkKihonYaku($weights, $pieces)
    {
        $count = count($weights);
        $isAllSame = (count(array_unique($weights)) === 1);
        
        // 連番チェック
        $isSeq = true;
        if ($count > 1) {
            $diff = $weights[1] - $weights[0];
            if (abs($diff) !== 1) $isSeq = false;
            else {
                for ($i = 1; $i < $count - 1; $i++) {
                    if (($weights[$i+1] - $weights[$i]) !== $diff) {
                        $isSeq = false;
                        break;
                    }
                }
            }
        } else { $isSeq = false; }

        // 1つ飛ばし連番 (Step 2)
        $isStep2 = true;
        if ($count > 1) {
            $diff2 = $weights[1] - $weights[0];
            if (abs($diff2) !== 2) $isStep2 = false;
            else {
                for ($i = 1; $i < $count - 1; $i++) {
                    if (($weights[$i+1] - $weights[$i]) !== $diff2) {
                        $isStep2 = false;
                        break;
                    }
                }
            }
        } else { $isStep2 = false; }

        $baseName = "";
        $baseScore = 0;

        // 桜花 check
        if ($isAllSame) {
            $baseName = "桜花";
            $baseScore = 2;
        }
        // 蓮花 check
        else if ($isSeq) {
            $baseName = "蓮花";
            $baseScore = 1;
        }
        // 奇数/偶数蓮花 check
        else if ($isStep2) {
            $isOdd = ($weights[0] % 2 !== 0);
            $allMatch = true;
            foreach($weights as $w) {
                if ($isOdd && $w % 2 === 0) $allMatch = false;
                if (!$isOdd && $w % 2 !== 0) $allMatch = false;
            }
            if ($allMatch) {
                $baseName = $isOdd ? "奇数蓮花" : "偶数蓮花";
                $baseScore = 1;
            }
        }

        if ($baseName === "") return null;

        // ボーナス計算
        $bonus = $this->_calculateBonus($pieces, 0); // 基本点は加算済みとして0を渡すか、あるいはここで基礎点を足すか
        // _calculateBonus は加算分だけ返すと便利だが、ここでは合計計算済みの構造体を返す設計にする

        // 命名規則適用 (三重桜花 etc)
        $heightStr = "";
        if ($count === 3) $heightStr = "三重";
        else if ($count === 4) $heightStr = "四重";
        else if ($count === 5) $heightStr = "五重";
        else if ($count === 6) $heightStr = "六重";

        $finalName = $heightStr . ($bonus['is_some'] ? "染め" : "") . $baseName;
        $totalScore = $baseScore + $bonus['height_bonus'] + $bonus['some_bonus'];

        return array('name' => $finalName, 'score' => $totalScore, 'type' => 'kihon');
    }

    private function _calculateBonus($pieces, $baseHeightBonusOffset = 0)
    {
        $count = count($pieces);
        
        // 高さ加点: 4段以上なら (段数 - 3)。ただし組み合わせ役は6段固定だが仕様書には「高さ加点（6段なので固定）」とある。
        // 基本役の定義: 段数 - 3.
        // 6段の場合: 6 - 3 = 3点。
        // 引数 $baseHeightBonusOffset は使わず、純粋に計算する
        $heightBonus = ($count >= 4) ? ($count - 3) : 0;

        // 染め加点: 全ての駒の種類(type ID)が一致
        $types = array_column($pieces, 'piece_type');
        $isSome = (count(array_unique($types)) === 1);
        $someBonus = $isSome ? 1 : 0;

        return array('height_bonus' => $heightBonus, 'some_bonus' => $someBonus, 'is_some' => $isSome);
    }

    private function _makeYakuResult($name, $baseScore, $bonus)
    {
        // 組み合わせ役などは6段固定なので高さボーナスは3点入るはず
        $total = $baseScore + $bonus['height_bonus'] + $bonus['some_bonus'];
        // 組み合わせ役の染め特別命名があるかは仕様書に明記ないが、基本役同様に付与するなら
        $finalName = ($bonus['is_some'] ? "染め" : "") . $name;
        return array('name' => $finalName, 'score' => $total, 'type' => 'kumiai');
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

    public function actTsumu($pieceId, $towerId)
    {
        self::checkAction("actTsumu");

        $playerId = self::getActivePlayerId();
        
        // 1. 駒情報の取得と所有権チェック
        $piece = self::getObjectFromDB("SELECT * FROM piece WHERE piece_id = $pieceId");
        if (!$piece) {
            throw new BgaUserException("Piece not found");
        }
        
        // 自分の hand にある駒のみ操作可能
        if (strpos($piece['piece_container'], "hand_p" . $playerId) === false) {
             throw new BgaUserException("You can only stack pieces from your hand");
        }

        // 2. 移動処理
        $sql = "SELECT MAX(piece_position) FROM piece WHERE piece_container = '$towerId'";
        $maxPos = self::getUniqueValueFromDB($sql);
        $newPos = ($maxPos === null) ? 0 : $maxPos + 1;

        self::DbQuery("UPDATE piece SET piece_container = '$towerId', piece_position = $newPos WHERE piece_id = $pieceId");

        // 通知
        $this->notifyAllPlayers("pieceMoved", clienttranslate('${player_name} stacks a piece on ${tower_name}'), array(
            'player_name' => self::getActivePlayerName(),
            'fromContainer' => $piece['piece_container'],
            'toContainer' => $towerId,
            'pieceId' => $pieceId,
            'tower_name' => $towerId
        ));

        // 3. 役判定
        $towerPieces = self::getObjectListFromDB("SELECT * FROM piece WHERE piece_container = '$towerId' ORDER BY piece_position DESC");
        
        $yaku = $this->calculateHand($towerPieces);
        
        if ($yaku) {
            $score = $yaku['score'];
            $yakuName = $yaku['name'];
            
            self::DbQuery("UPDATE player SET player_score = player_score + $score WHERE player_id = $playerId");
            $newScore = self::getUniqueValueFromDB("SELECT player_score FROM player WHERE player_id = $playerId");

            $this->notifyAllPlayers("yakuCompleted", clienttranslate('${player_name} completes Yaku: ${yaku_name} (${score} points)'), array(
                'player_name' => self::getActivePlayerName(),
                'yaku_name' => $yakuName,
                'score' => $score,
                'new_score' => $newScore,
                'towerId' => $towerId
            ));

            self::DbQuery("UPDATE piece SET piece_container = 'exclusion', piece_position = 0 WHERE piece_container = '$towerId'");
            $this->notifyAllPlayers("towerCleared", "", array('towerId' => $towerId));
        }

        // フェーズ終了
        // $this->gamestate->nextState("next"); // 手動遷移に変更
    }

    public function actHatsu($pieceId, $kyoukokuId)
    {
        self::checkAction("actHatsu");

        $playerId = self::getActivePlayerId();

        // 1. 駒情報の取得と所有権チェック
        $piece = self::getObjectFromDB("SELECT * FROM piece WHERE piece_id = $pieceId");
        if (!$piece) {
            throw new BgaUserException("Piece not found");
        }

        // 自分の hand にある駒のみ操作可能
        if (strpos($piece['piece_container'], "hand_p" . $playerId) === false) {
             throw new BgaUserException("You can only activate pieces from your hand");
        }

        // 2. 移動処理
        // 峡谷の空いているスロットを探すか、あるいは kyoukokuId (例: kyoukoku_myself) に追記するか。
        // 仕様では峡谷は一列に並ぶ。
        // 既存の峡谷の駒数を取得して position を決定
        $sql = "SELECT MAX(piece_position) FROM piece WHERE piece_container = '$kyoukokuId'";
        $maxPos = self::getUniqueValueFromDB($sql);
        $newPos = ($maxPos === null) ? 0 : $maxPos + 1;

        self::DbQuery("UPDATE piece SET piece_container = '$kyoukokuId', piece_position = $newPos WHERE piece_id = $pieceId");

        // 通知
        $this->notifyAllPlayers("pieceMoved", clienttranslate('${player_name} activates a piece to ${kyoukoku_name}'), array(
            'player_name' => self::getActivePlayerName(),
            'fromContainer' => $piece['piece_container'],
            'toContainer' => $kyoukokuId,
            'pieceId' => $pieceId,
            'kyoukoku_name' => $kyoukokuId
        ));

        // 3. 効果処理（フレームワーク）
        $typeId = $piece['piece_type'];
        $this->resolvePieceEffect($playerId, $typeId);

        // フェーズ終了
        // $this->gamestate->nextState("next"); // 手動遷移に変更
    }

    private function resolvePieceEffect($playerId, $typeId)
    {
        // 駒の definition を取得
        if (!isset($this->piece_types[$typeId])) return;
        $def = $this->piece_types[$typeId];
        $name = $def['name'];

        // 効果処理分岐
        // 将来的にはここで switch ($def['name']) などで分岐
        $this->notifyAllPlayers("log", clienttranslate('Effect of ${piece_name} is activated (not implemented yet)'), array(
            'piece_name' => $name
        ));

        // 例: '雷' (9) の場合
        if ($name === '雷') {
            // 雷の効果処理
        }
    }

    public function actMoveKakure($towerId)
    {
        self::checkAction("actMoveKakure"); // states.inc.php に actMoveKakure を追加が必要

        $playerId = self::getActivePlayerId();

        // towerId のバリデーション (format: tower_myself_X or tower_rival_X)
        // 自分の塔の下に配置するイメージであれば tower_myself_X
        if (preg_match('/^tower_myself_([1-7])$/', $towerId, $matches)) {
            $pos = intval($matches[1]);
        } else {
             throw new BgaUserException("Invalid tower selection for Kakure marker: $towerId");
        }

        // 現在の位置と異なるかチェック（必要なら）
        // $currentPos = self::getGameStateValue('kakure_pos');
        // if ($currentPos == $pos) throw new ...

        // 位置更新
        self::setGameStateValue('kakure_pos', $pos);

        // 通知
        $this->notifyAllPlayers("kakureMoved", clienttranslate('${player_name} moves Hidden Marker to position ${pos}'), array(
            'player_name' => self::getActivePlayerName(),
            'pos' => $pos,
            'towerId' => $towerId
        ));

        // フェーズ終了
        // $this->gamestate->nextState("next"); // 手動遷移に変更
    }

    public function nextPhase()
    {
        self::checkAction("nextPhase");
        $this->gamestate->nextState("next");
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
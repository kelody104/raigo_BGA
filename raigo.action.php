<?php
class action_raigo extends APP_GameAction
{
    public function __default()
    {
        if (self::isArg('notifwindow')) {
            $this->view = "common_notifwindow";
            $this->viewArgs['table'] = self::getArg("table", AT_posint, true);
        } else {
            $this->view = "raigo_raigo";
        }
    }

    public function actTsumu()
    {
        self::setAjaxMode();
        $pieceId = self::getArg("pieceId", AT_int);
        $towerId = self::getArg("towerId", AT_alphanum);
        
        $this->game->actTsumu($pieceId, $towerId);
        self::ajaxResponse();
    }

    public function actHatsu()
    {
        self::setAjaxMode();
        $pieceId = self::getArg("pieceId", AT_int);
        $kyoukokuId = self::getArg("kyoukokuId", AT_alphanum);
        
        $this->game->actHatsu($pieceId, $kyoukokuId);
        self::ajaxResponse();
    }

    public function actMoveKakure()
    {
        self::setAjaxMode();
        $towerId = self::getArg("towerId", AT_alphanum);
        
        $this->game->actMoveKakure($towerId);
        self::ajaxResponse();
    }

    public function nextPhase()
    {
        self::setAjaxMode();
        $this->game->nextPhase();
        self::ajaxResponse();
    }

    public function debugGeneratePiece()
    {
        self::setAjaxMode();
        $containerId = self::getArg("containerId", AT_alphanum);
        $type = self::getArg("type", AT_int);
        $persist = self::getArg("persist", AT_int, false) ?? 0;
        
        // サーバー側でのバリデーション（オプション）
        if (empty($containerId) || empty($type)) {
            throw new BgaUserException("Invalid container ID or type");
        }

        // DB保存が有効な場合のみDBに追加
        if ($persist === 1) {
            $pieceId = $this->game->saveDebugPiece($containerId, $type, 'front');
        }

        // 自分以外のプレイヤーに通知を送信
        $currentPlayerId = $this->getCurrentPlayerId();
        $players = $this->game->loadPlayersBasicInfos();
        
        foreach ($players as $playerId => $player) {
            if ($playerId != $currentPlayerId) {
                $this->game->notifyPlayer($playerId, "piecePlaced", "", [
                    "container" => $containerId,
                    "type" => $type
                ]);
            }
        }

        self::ajaxResponse();
    }

    public function movePieceToHand()
    {
        self::setAjaxMode();
        $fromContainer = self::getArg("fromContainer", AT_alphanum);
        $toContainer = self::getArg("toContainer", AT_alphanum);
        
        if (empty($fromContainer) || empty($toContainer)) {
            throw new BgaUserException("Invalid container IDs");
        }

        $this->game->movePieceToContainer($fromContainer, $toContainer);
        self::ajaxResponse();
    }

    public function movePieceFromDeck()
    {
        self::setAjaxMode();
        $fromContainer = self::getArg("fromContainer", AT_alphanum);
        $toContainer = self::getArg("toContainer", AT_alphanum);
        
        if (empty($fromContainer) || empty($toContainer)) {
            throw new BgaUserException("Invalid container IDs");
        }

        $this->game->movePieceFromDeck($fromContainer, $toContainer);
        self::ajaxResponse();
    }

    public function debugFlipPiece()
    {
        self::setAjaxMode();
        $pieceId = self::getArg("pieceId", AT_int);
        
        $this->game->debugFlipPiece($pieceId);
        self::ajaxResponse();
    }

    public function debugMovePiece()
    {
        self::setAjaxMode();
        $pieceId = self::getArg("pieceId", AT_int);
        $toContainer = self::getArg("toContainer", AT_alphanum);
        
        if (empty($toContainer)) {
            throw new BgaUserException("Invalid container ID");
        }

        $this->game->debugMovePiece($pieceId, $toContainer);
        self::ajaxResponse();
    }
}

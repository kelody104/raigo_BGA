<?php
require_once(APP_GAMEMODULE_PATH . 'module/table/table.game.php');

class Raigo extends Table
{
    public function __construct()
    {
        parent::__construct();
        self::initGameStateLabels(array(
            'kai_enabled' => 10
        ));
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
        self::setGameStateInitialValue('kai_enabled', 1);

        self::activeNextPlayer();
    }

    protected function getAllDatas(): array
    {
        return array(
            'players' => self::loadPlayersBasicInfos()
        );
    }

    public function getGameProgression()
    {
        return 0;
    }

    public function stKai()
    {
        $playerId = $this->getActivePlayerId();

        if (!$this->canDoKai($playerId)) {
            $this->notifyAllPlayers(
                "log",
                clienttranslate('${player_name} skips Kai phase (開)'),
                array(
                    "player_name" => $this->getPlayerNameById($playerId)
                )
            );
            $this->gamestate->nextState("skip");
        }
    }

    public function nextPhase()
    {
        self::checkAction("nextPhase");

        $stateName = $this->gamestate->state()["name"];

        if ($stateName === "in") {
            $this->gamestate->nextState("endTurn");
        } else {
            $this->gamestate->nextState("next");
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
        return self::getGameStateValue('kai_enabled') == 1;
    }
}

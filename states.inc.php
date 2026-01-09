<?php
$machinestates = array(
    1 => array(
        "name" => "gameSetup",
        "description" => "",
        "type" => "manager",
        "transitions" => array("" => 2)
    ),

    2 => array(
        "name" => "kai",
        "description" => clienttranslate('${actplayer} is in Kai phase (開)'),
        "descriptionmyturn" => clienttranslate('${you} are in Kai phase (開)'),
        "type" => "activeplayer",
        "action" => "stKai",
        "possibleactions" => array("nextPhase"),
        "transitions" => array("next" => 3, "skip" => 3)
    ),

    3 => array(
        "name" => "gen",
        "description" => clienttranslate('${actplayer} is in Gen phase (現)'),
        "descriptionmyturn" => clienttranslate('${you} are in Gen phase (現)'),
        "type" => "activeplayer",
        "possibleactions" => array("nextPhase"),
        "transitions" => array("next" => 4)
    ),

    4 => array(
        "name" => "sen",
        "description" => clienttranslate('${actplayer} is in Sen phase (選)'),
        "descriptionmyturn" => clienttranslate('${you} are in Sen phase (選)'),
        "type" => "activeplayer",
        "possibleactions" => array("nextPhase"),
        "transitions" => array("next" => 5)
    ),

    5 => array(
        "name" => "tsumuHatsu",
        "description" => clienttranslate('${actplayer} is in Tsumu/Hatsu phase (積/発)'),
        "descriptionmyturn" => clienttranslate('${you} are in Tsumu/Hatsu phase (積/発)'),
        "type" => "activeplayer",
        "possibleactions" => array("nextPhase"),
        "transitions" => array("next" => 6)
    ),

    6 => array(
        "name" => "in",
        "description" => clienttranslate('${actplayer} is in In phase (隠)'),
        "descriptionmyturn" => clienttranslate('${you} are in In phase (隠)'),
        "type" => "activeplayer",
        "possibleactions" => array("nextPhase"),
        "transitions" => array("endTurn" => 7)
    ),

    7 => array(
        "name" => "endTurn",
        "description" => "",
        "type" => "game",
        "action" => "stEndTurn",
        "transitions" => array("next" => 2)
    ),

    99 => array(
        "name" => "gameEnd",
        "description" => clienttranslate("End of game"),
        "type" => "manager",
        "action" => "stGameEnd",
        "args" => "argGameEnd"
    )
);

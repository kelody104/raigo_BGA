<?php
$machinestates = array(
    1 => array(
        "name" => "gameSetup",
        "description" => "",
        "type" => "manager",
        "action" => "stGameSetup",
        "transitions" => array("" => 10)
    ),

    5 => array(
        "name" => "debug",
        "description" => "",
        "descriptionmyturn" => "",
        "type" => "activeplayer",
        "possibleactions" => array("debugFlipPiece", "debugMovePiece", "nextPhase"),
        "transitions" => array("next" => 10)
    ),

    10 => array(
        "name" => "kai",
        "description" => "",
        "descriptionmyturn" => "",
        "type" => "activeplayer",
        "action" => "stKai",
        "possibleactions" => array("nextPhase"),
        "transitions" => array("next" => 19, "skip" => 19)
    ),

    19 => array(
        "name" => "preGen",
        "description" => "",
        "type" => "game",
        "action" => "stPreGen",
        "transitions" => array("next" => 20)
    ),

    20 => array(
        "name" => "gen",
        "description" => "",
        "descriptionmyturn" => "",
        "type" => "activeplayer",
        "action" => "stGen",
        "possibleactions" => array("selectInsidePiece", "movePieceToHand", "nextPhase"),
        "transitions" => array("next" => 29)
    ),

    29 => array(
        "name" => "preSen",
        "description" => "",
        "type" => "game",
        "action" => "stPreSen",
        "transitions" => array("next" => 30)
    ),

    30 => array(
        "name" => "sen",
        "description" => "",
        "descriptionmyturn" => "",
        "type" => "activeplayer",
        "action" => "stSen",
        "possibleactions" => array("selectDeckPiece", "movePieceFromDeck", "nextPhase"),
        "transitions" => array("next" => 39)
    ),

    39 => array(
        "name" => "preTsumuHatsu",
        "description" => "",
        "type" => "game",
        "action" => "stPreTsumuHatsu",
        "transitions" => array("next" => 40)
    ),

    40 => array(
        "name" => "tsumuHatsu",
        "description" => "",
        "descriptionmyturn" => "",
        "type" => "activeplayer",
        "action" => "stTsumuHatsu",
        "possibleactions" => array("actTsumu", "actHatsu", "nextPhase"),
        "transitions" => array("next" => 79)
    ),

    79 => array(
        "name" => "preKakure",
        "description" => "",
        "type" => "game",
        "action" => "stPreKakure",
        "transitions" => array("next" => 80)
    ),

    80 => array(
        "name" => "kakure",
        "description" => "",
        "descriptionmyturn" => "",
        "type" => "activeplayer",
        "action" => "stKakure",
        "possibleactions" => array("nextPhase"),
        "transitions" => array("next" => 60)
    ),

    60 => array(
        "name" => "endTurn",
        "description" => "",
        "type" => "game",
        "action" => "stEndTurn",
        "transitions" => array("next" => 10)
    ),

    99 => array(
        "name" => "gameEnd",
        "description" => clienttranslate("End of game"),
        "type" => "manager",
        "action" => "stGameEnd",
        "args" => "argGameEnd"
    )
);

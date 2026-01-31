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
        "transitions" => array("next" => 20, "skip" => 20)
    ),

    20 => array(
        "name" => "genMove",
        "description" => "",
        "descriptionmyturn" => "",
        "type" => "activeplayer",
        "possibleactions" => array("selectInsidePiece", "movePieceToHand", "nextPhase"),
        "transitions" => array("next" => 30)
    ),

    25 => array(
        "name" => "gen",
        "description" => "",
        "descriptionmyturn" => "",
        "type" => "activeplayer",
        "possibleactions" => array("nextPhase"),
        "transitions" => array("next" => 30)
    ),

    30 => array(
        "name" => "sen",
        "description" => "",
        "descriptionmyturn" => "",
        "type" => "activeplayer",
        "possibleactions" => array("selectDeckPiece", "movePieceFromDeck", "nextPhase"),
        "transitions" => array("next" => 40)
    ),

    40 => array(
        "name" => "tsumuHatsu",
        "description" => "",
        "descriptionmyturn" => "",
        "type" => "activeplayer",
        "possibleactions" => array("nextPhase"),
        "transitions" => array("next" => 50)
    ),

    50 => array(
        "name" => "in",
        "description" => "",
        "descriptionmyturn" => "",
        "type" => "activeplayer",
        "possibleactions" => array("nextPhase"),
        "transitions" => array("endTurn" => 60)
    ),

    60 => array(
        "name" => "endTurn",
        "description" => "",
        "type" => "game",
        "action" => "stEndTurn",
        "transitions" => array("next" => 20)
    ),

    99 => array(
        "name" => "gameEnd",
        "description" => clienttranslate("End of game"),
        "type" => "manager",
        "action" => "stGameEnd",
        "args" => "argGameEnd"
    )
);

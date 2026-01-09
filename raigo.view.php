<?php

  
  require_once( APP_BASE_PATH."view/common/game.view.php" );
  
  class view_raigo_raigo extends game_view
  {
    function getGameName() {
        return "raigo";
    }    
  	function build_page( $viewArgs )
  	{		
  	    
        $players = $this->game->loadPlayersBasicInfos();
        $players_nbr = count( $players );
  	}
  }
  


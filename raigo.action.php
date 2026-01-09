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

    public function nextPhase()
    {
        self::setAjaxMode();
        $this->game->nextPhase();
        self::ajaxResponse();
    }
}

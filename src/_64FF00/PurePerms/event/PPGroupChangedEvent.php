<?php

namespace _64FF00\PurePerms\event;

use _64FF00\PurePerms\PPGroup;
use _64FF00\PurePerms\PurePerms;

use pocketmine\event\plugin\PluginEvent;

use pocketmine\IPlayer;

class PPGroupChangedEvent extends PluginEvent
{
    public static $handlerList = null;

    /**
     * @param PurePerms $plugin
     * @param IPlayer $player
     * @param PPGroup $group
     * @param $levelName
     */
    public function __construct(PurePerms $plugin, IPlayer $player, PPGroup $group)
    {
        parent::__construct($plugin);

        $this->group = $group;
        $this->player = $player;
    }

    /**
     * @return PPGroup
     */
    public function getGroup()
    {
        return $this->group;
    }

    /**
     * @return IPlayer
     */
    public function getPlayer()
    {
        return $this->player;
    }
}

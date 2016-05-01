<?php

namespace _64FF00\PurePerms\cmd;

use _64FF00\PurePerms\PurePerms;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\command\PluginIdentifiableCommand;

use pocketmine\utils\TextFormat;

class Groups extends Command implements PluginIdentifiableCommand
{
    /**
     * @param PurePerms $plugin
     * @param $name
     * @param $description
     */
    public function __construct(PurePerms $plugin, $name, $description)
    {
        $this->plugin = $plugin;
        
        parent::__construct($name, $description);
        
        $this->setPermission("pperms.command.groups");
    }

    /**
     * @param CommandSender $sender
     * @param $label
     * @param array $args
     * @return bool
     */
    public function execute(CommandSender $sender, $label, array $args)
    {
        if(!$this->testPermission($sender))
            return false;
        
        $result = "";
        
        foreach($this->plugin->getGroups() as $group)
        {
            $result .= $group->getName() . ", ";
        }
        
        $result = substr($result, 0, -2);
        
        $sender->sendMessage(TextFormat::BLUE . "[PurePerms] " . $this->plugin->getMessage("cmds.groups.messages.all_registered_groups", $result));
        
        return true;
    }
    
    public function getPlugin()
    {
        return $this->plugin;
    }
}

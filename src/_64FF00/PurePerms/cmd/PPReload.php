<?php

namespace _64FF00\PurePerms\cmd;

use _64FF00\PurePerms\PurePerms;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\command\PluginIdentifiableCommand;

use pocketmine\utils\TextFormat;

class PPReload extends Command implements PluginIdentifiableCommand
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
        
        $this->setPermission("pperms.command.ppreload");
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
        
        $this->plugin->reload();
        
        $sender->sendMessage(TextFormat::BLUE . "[PurePerms] " . $this->plugin->getMessage("cmds.ppreload.messages.successfully_reloaded"));
        
        return true;
    }
    
    public function getPlugin()
    {
        return $this->plugin;
    }
}

<?php

namespace _64FF00\PurePerms\cmd;

use _64FF00\PurePerms\PurePerms;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\command\PluginIdentifiableCommand;

use pocketmine\utils\TextFormat;

class RmGroup extends Command implements PluginIdentifiableCommand
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
        
        $this->setPermission("pperms.command.rmgroup");
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
        
        if(!isset($args[0]) || count($args) > 1)
        {
            $sender->sendMessage(TextFormat::BLUE . "[PurePerms] " . $this->plugin->getMessage("cmds.rmgroup.usage"));
            
            return true;
        }

        $result = $this->plugin->removeGroup($args[0]);
        
        if($result === PurePerms::SUCCESS)
        {
            $sender->sendMessage(TextFormat::BLUE . "[PurePerms] " . $this->plugin->getMessage("cmds.rmgroup.messages.group_removed_successfully", $args[0]));
        }
        elseif($result === PurePerms::INVALID_NAME)
        {
            $sender->sendMessage(TextFormat::BLUE . "[PurePerms] " . $this->plugin->getMessage("cmds.rmgroup.messages.invalid_group_name", $args[0]));
        }
        else
        {
            $sender->sendMessage(TextFormat::RED . "[PurePerms] " . $this->plugin->getMessage("cmds.rmgroup.messages.group_not_exist", $args[0]));
        }
        
        return true;
    }
    
    public function getPlugin()
    {
        return $this->plugin;
    }
}

<?php

namespace _64FF00\PurePerms\provider;

use _64FF00\PurePerms\PurePerms;
use _64FF00\PurePerms\PPGroup;

use pocketmine\IPlayer;

use pocketmine\utils\Config;

class DefaultProvider implements ProviderInterface
{
    private $groups, $userDataFolder;

    /**
     * @param PurePerms $plugin
     */
    public function __construct(PurePerms $plugin)
    {
        $this->plugin = $plugin;

        $this->plugin->saveResource("groups.yml");

        $this->groups = new Config($this->plugin->getDataFolder() . "groups.yml", Config::YAML, [
        ]);

        $this->userDataFolder = $this->plugin->getDataFolder() . "players/";

        if(!file_exists($this->userDataFolder)) \mkdir($this->userDataFolder, 0777, true);
    }

    /**
     * @param PPGroup $group
     * @return mixed
     */
    public function getGroupData(PPGroup $group)
    {
        $groupName = $group->getName();
        
        if(!isset($this->getGroupsData()[$groupName]) || !is_array($this->getGroupsData()[$groupName])) return [];

        return $this->getGroupsData()[$groupName];
    }

    /**
     * @return mixed
     */
    public function getGroupsConfig()
    {
        return $this->groups;
    }

    /**
     * @return mixed
     */
    public function getGroupsData()
    {
        return $this->groups->getAll();
    }

    /**
     * @param IPlayer $player
     * @param bool $onUpdate
     * @return array|Config
     */
    public function getPlayerConfig(IPlayer $player, $onUpdate = false)
    {
        $userName = $player->getName();

        // TODO
        if($onUpdate === true)
        {
            if(!file_exists($this->userDataFolder . strtolower($userName) . ".yml"))
            {
                return new Config($this->userDataFolder . strtolower($userName) . ".yml", Config::YAML, [
                    "userName" => $userName,
                    "group" => $this->plugin->getDefaultGroup()->getName(),
                    "permissions" => [],
                    "worlds" => []
                ]);
            }
            else
            {
                return new Config($this->userDataFolder . strtolower($userName) . ".yml", Config::YAML, [
                ]);
            }
        }
        else
        {
            if(file_exists($this->userDataFolder . strtolower($userName) . ".yml"))
            {
                return new Config($this->userDataFolder . strtolower($userName) . ".yml", Config::YAML, [
                ]);
            }
            else
            {
                return [
                    "userName" => $userName,
                    "group" => $this->plugin->getDefaultGroup()->getName(),
                    "permissions" => [],
                    "worlds" => []
                ];
            }
        }
    }

    /**
     * @param IPlayer $player
     * @return array|Config
     */
    public function getPlayerData(IPlayer $player)
    {
        $userConfig = $this->getPlayerConfig($player);

        return (($userConfig instanceof Config) ? $userConfig->getAll() : $userConfig);
    }

    /**
     * @param PPGroup $group
     * @param array $tempGroupData
     */
    public function setGroupData(PPGroup $group, array $tempGroupData)
    {
        $groupName = $group->getName();

        $this->groups->set($groupName, $tempGroupData);

        $this->groups->save();
    }

    /**
     * @param array $tempGroupsData
     */
    public function setGroupsData(array $tempGroupsData)
    {
        $this->groups->setAll($tempGroupsData);

        $this->groups->save();
    }

    /**
     * @param IPlayer $player
     * @param array $tempUserData
*/
    public function setPlayerData(IPlayer $player, array $tempUserData)
    {
        $userData = $this->getPlayerConfig($player, true);

        if(!$userData instanceof Config) throw new \RuntimeException("Failed to update player data: Invalid data type (" . get_class($userData) . ")");

        $userData->setAll($tempUserData);

        $userData->save();
    }
    
    public function close()
    {
    }
}

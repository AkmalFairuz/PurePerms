<?php

namespace _64FF00\PurePerms;              

use pocketmine\utils\Config;                                                    

class PPMessages
{
    private $language, $messages;
    
    private $langList = [];

    /**
     * @param PurePerms $plugin
     */
    public function __construct(PurePerms $plugin)
    {
        $this->plugin = $plugin;
        
        $this->registerLanguages();
        
        $this->loadMessages();
    }

    public function registerLanguages()
    {
        $result = [];
        
        foreach($this->plugin->getResources() as $resource)
        {
            if(mb_strpos($resource, "messages-") !== false) $result[] = substr($resource, -6, -4);
        }
        
        $this->langList = $result;
    }

    /**
     * @param $node
     * @param ...$vars
     * @return mixed|null
     */
    public function getMessage($node, ...$vars)
    {
        $msg = $this->messages->getNested($node);
        
        if($msg != null)
        {
            $number = 0;
            
            foreach($vars as $v)
            {           
                $msg = str_replace("%var$number%", $v, $msg);
                
                $number++;
            }
            
            return $msg;
        }
        
        return null;
    }

    /**
     * @return mixed
     */
    public function getVersion()
    {
        $version = $this->messages->get("messages-version");

        return $version;
    }

    public function loadMessages()
    {       
        $defaultLang = $this->plugin->getConfigValue("default-language");
        
        foreach($this->langList as $langName)
        {
            if(strtolower($defaultLang) == $langName)
            {
                $this->language = $langName;
            }
        }
        
        if(!isset($this->language))
        {
            $this->plugin->getLogger()->warning("Language resource " . $defaultLang . " not found. Using default language resource by " . $this->plugin->getDescription()->getAuthors()[0]);
            
            $this->language = "en";
        }
        
        $this->plugin->saveResource("messages-" . $this->language . ".yml");
        
        $this->messages = new Config($this->plugin->getDataFolder() . "messages-" . $this->language . ".yml", Config::YAML, [
        ]);
        
        $this->plugin->getLogger()->info("Setting default language to '" . $defaultLang . "'");
        
        if(version_compare($this->getVersion(), $this->plugin->getPPVersion()) == -1)
        {
            $this->plugin->saveResource("messages-" . $this->language . ".yml", true);
        
            $this->messages = new Config($this->plugin->getDataFolder() . "messages-" . $this->language . ".yml", Config::YAML, [
            ]);
        }
    }
    
    public function reloadMessages()
    {
        $this->messages->reload();
    }    
}

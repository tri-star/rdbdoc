<?php


namespace Dbdg\Plugins;


class PluginManager
{

    private $plugins;


    public function __construct()
    {
        $this->plugins = array(

        );
    }


    public function register($extensionPoint, PluginInterface $plugin)
    {
        if(!isset($this->plugins[$extensionPoint])) {
            throw new \Exception('無効な拡張ポイントが指定されました。');
        }
        $name = $plugin->getName();
        $this->plugins[$extensionPoint][$name] = $plugin;
    }


    public function getPlugins($extensionPoint)
    {
        if(!isset($this->plugins[$extensionPoint])) {
            throw new \Exception('無効な拡張ポイントが指定されました。');
        }

        return $this->plugins[$extensionPoint];
    }
}

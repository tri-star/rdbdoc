<?php


namespace Dbdg\Plugins;


class PluginManager
{

    /**
     * @var PluginInterface[]
     */
    private $plugins;


    /**
     * @var PluginInterface[]
     */
    private $extensionPoints;


    public function __construct()
    {
        $this->plugins = array();
        $this->extensionPoints = array(
            'document_writer' => array(),
        );
    }


    public function defineExtensionPoint($name)
    {
        if(isset($this->extensionPoints[$name])) {
            return;
        }
        $this->extensionPoints[$name] = array();
    }


    public function register(PluginInterface $plugin)
    {
        $name = $plugin->getName();
        $this->plugins[$name] = $plugin;

        $plugin->installPlugin($this);
    }


    public function registerExtensionPoint($extensionPoint, PluginInterface $plugin)
    {
        if(!isset($this->extensionPoints[$extensionPoint])) {
            throw new \Exception('無効な拡張ポイントが指定されました。: ' . $extensionPoint);
        }
        $this->extensionPoints[$extensionPoint] = $plugin;
    }


    public function getPlugins($extensionPoint)
    {
        if(!isset($this->extensionPoints[$extensionPoint])) {
            throw new \Exception('無効な拡張ポイントが指定されました。: ' . $extensionPoint);
        }

        return $this->extensionPoints[$extensionPoint];
    }
}

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

    /**
     * @var PluginManager
     */
    private static $instance;

    /**
     * @return PluginManager
     */
    public static function getInstance()
    {
        if(!is_null(self::$instance)) {
            return self::$instance;
        }

        self::$instance = new PluginManager();

        return self::$instance;
    }


    private function __construct()
    {
        $this->plugins = array();
        $this->extensionPoints = array(
            'document_writer' => array(),
        );
    }


    public function loadPlugins()
    {
        //TODO: プラグインのロード先を追加／変更可能にする
        //プラグインのディレクトリ(プロジェクト直下のplugins)
        $pluginDir = dirname(dirname(__DIR__)) . '/plugins';
        if(!is_dir($pluginDir)) {
            throw new \Exception("プラグインディレクトリ: {$pluginDir} が存在しません");
        }

        $iterator = new \DirectoryIterator($pluginDir);
        foreach($iterator as $dir) {

            if(in_array($dir, array('.', '..'))) {
                continue;
            }

            $installScript = $pluginDir . '/' . $dir . '/install.php';
            if(!is_file($installScript)) {
                continue;
            }

            $plugin = require($installScript);
            if(!$plugin instanceof PluginInterface) {
                throw new \Exception('無効なプラグインがロードされました。 dir:' . $dir);
            }

            $this->register($plugin);
        }

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
        $this->extensionPoints[$extensionPoint][] = $plugin;
    }


    public function getPlugins($extensionPoint)
    {
        if(!isset($this->extensionPoints[$extensionPoint])) {
            throw new \Exception('無効な拡張ポイントが指定されました。: ' . $extensionPoint);
        }

        return $this->extensionPoints[$extensionPoint];
    }
}

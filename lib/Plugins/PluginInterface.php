<?php


namespace Dbdg\Plugins;


interface PluginInterface
{

    public function getName();


    public function installPlugin(PluginManager $manager);

}

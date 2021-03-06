<?php

require_once __DIR__ . '/vendor/autoload.php';

use Dbdg\Plugins\PluginManager;
use Symfony\Component\Console\Application;


$pluginManager = PluginManager::getInstance();
$pluginManager->loadPlugins();

$application = new Application();
$application->add(new \Dbdg\Commands\GenerateTemplateCommand());
$application->add(new \Dbdg\Commands\UpdateTemplateCommand());
$application->add(new \Dbdg\Commands\GenerateDocumentCommand());
$application->add(new \Dbdg\Commands\ListPluginCommand());
$application->add(new \Dbdg\Commands\ListDifferenceCommand());

$application->run();

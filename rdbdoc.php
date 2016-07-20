<?php

require_once './vendor/autoload.php';

use Dbdg\Plugins\PluginManager;
use Symfony\Component\Console\Application;


$pluginManager = PluginManager::getInstance();
$pluginManager->loadPlugins();

$application = new Application();
$application->add(new \Dbdg\Commands\GenerateTemplateCommand());
$application->add(new \Dbdg\Commands\UpdateTemplateCommand());
$application->add(new \Dbdg\Commands\GenerateDocumentCommand());

$application->run();

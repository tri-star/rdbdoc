<?php

require_once './vendor/autoload.php';

use Dbdg\Commands\CreateTemplateCommand;
use Dbdg\Commands\GenerateDocumentCommand;
use Dbdg\Commands\UpdateTemplateCommand;
use Dbdg\Plugins\PluginManager;
use Symfony\Component\Console\Application;


$pluginManager = PluginManager::getInstance();
$pluginManager->loadPlugins();

$application = new Application();
$application->add(new CreateTemplateCommand());
$application->add(new UpdateTemplateCommand());
$application->add(new GenerateDocumentCommand());

$application->run();

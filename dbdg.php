<?php

require_once './vendor/autoload.php';

use Dbdg\Commands\CreateTemplateCommand;
use Dbdg\Commands\GenerateDocumentCommand;
use Dbdg\Commands\UpdateTemplateCommand;
use Symfony\Component\Console\Application;

$application = new Application();
$application->add(new CreateTemplateCommand());
$application->add(new UpdateTemplateCommand());
$application->add(new GenerateDocumentCommand());

$application->run();

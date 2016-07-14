<?php

require_once './vendor/autoload.php';

use Dbdg\Commands\CreateTemplateCommand;
use Symfony\Component\Console\Application;

$application = new Application();
$application->add(new CreateTemplateCommand());

$application->run();

<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

require_once __DIR__ . '/../app/functions.php';

$configurator = App\Bootstrap::boot();
$container = $configurator->createContainer();
$application = $container->getByType(Nette\Application\Application::class);
$application->run();


//echo 'Hello World';
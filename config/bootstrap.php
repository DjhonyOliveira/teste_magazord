<?php

use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\ORMSetup;
use Dotenv\Dotenv;

$root = dirname(__DIR__);

require_once $root . '/vendor/autoload.php';

Dotenv::createImmutable($root)->safeLoad();

$isDevMode = ($_ENV['APP_ENV'] ?? 'dev') === 'dev';

$config = ORMSetup::createAttributeMetadataConfiguration(
    paths: [$root . '/src/Model'],
    isDevMode: $isDevMode,
);

$connection = DriverManager::getConnection(require $root . '/config/database.php', $config);

return new EntityManager($connection, $config);
<?php

use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;

require_once "vendor/autoload.php";

$ruta = [__DIR__ . "/src/Entity"];
$modo = false;
$mysql = [
    'driver'   => 'pdo_mysql',
    'user'     => 'root',
    'password' => '',
    'dbname'   => 'Garantias'
];

$configuracion = Setup::createAnnotationMetadataConfiguration($ruta, $modo, null, null, false);
$em = EntityManager::create($mysql, $configuracion);
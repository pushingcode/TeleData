<?php

use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager; 
use App\AppCoreServices as Core;
use App\AppLogServices;   

require_once "vendor/autoload.php";

$get_app = new Core;
 
$ruta = [__DIR__ . "" .$get_app->pathsData('Entity')];
$modo = false;
$mysql = $get_app->appData('db');

$configuracion = Setup::createAnnotationMetadataConfiguration($ruta, $modo, null, null, false);
$em = EntityManager::create($mysql,$configuracion);
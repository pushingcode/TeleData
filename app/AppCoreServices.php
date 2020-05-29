<?php

declare(strict_types = 1);

namespace App;

use App\AppLogServices;

class AppCoreServices 
{
    public static function makeEntityManager(){
        
        require_once(__DIR__ .'/../bootstrap.php');
        AppLogServices::logEvent(__FUNCTION__,'Se ha creado un EntityManager', [], 100);
        return $em;
    }
}
<?php

declare(strict_types = 1);

namespace App;

use Monolog\Logger;
use Monolog\Handler\FirePHPHandler;
use Monolog\Handler\StreamHandler;

class AppLogServices
{
    /**
     * logEvent() Ejecuta el servicio de logging
     * DEBUG (100)
     * INFO (200)
     * NOTICE (250)
     * WARNING (300)
     * ERROR (400)
     * CRITICAL (500)
     * ALERT (550)
     * EMERGENCY (600)
     * 
     * @param string $services
     * @param string $event
     * @param array $payload
     * @param int $level
     * @return void
     */
    public static function logEvent($services, $event, $payload, $level)
    {
        $logger = new Logger($services);

        $logger->pushHandler(new StreamHandler(__DIR__ . '/../logs/services.log', $level));
        $logger->pushHandler(new FirePHPHandler());
        
        $logger->info($event, $payload);
    }

}
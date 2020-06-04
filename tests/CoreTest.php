<?php

declare(strict_types = 1);

namespace Test;

use PHPUnit\Framework\TestCase;
use App\AppCoreServices;
use App\AppLogServices;

final class CoreTest extends TestCase
{
    public function testCoreAppDbData()
    {
        $data = new AppCoreServices();
        $testSalida_db = $data->appData('db');
        var_dump($testSalida_db);
        $this->assertIsArray($testSalida_db);
    }

    public function testCoreAppPathsData()
    {
        $data = new AppCoreServices();
        $testSalida_p = $data->pathsData('Entity');
        var_dump($testSalida_p);
        $this->assertIsString($testSalida_p);
    }

    public function testCoreLoggingServices()
    {
        AppLogServices::logEvent(__FUNCTION__,'Test del LogServices', [], 600);
        $this->assertIsArray(array());
    }

}
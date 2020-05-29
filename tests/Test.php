<?php

declare(strict_types = 1);

namespace Test;

use PHPUnit\Framework\TestCase;
use App\Services\GarantiasInsert;

final class Test extends TestCase
{

    public function testPersistGarantias()
    {
        $waranty = new GarantiasInsert();
        $new_garantia = [];

        for ($i=1; $i < 100 ; $i++) { 
            $new_garantia[] = (string)rand(200,600);
        }

        $exwcute = $waranty->crearGaratias($new_garantia);
        var_dump($exwcute);
        $this->assertIsInt($exwcute);
    }
}
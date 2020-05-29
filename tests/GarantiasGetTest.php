<?php

declare(strict_types = 1);

namespace Test;

use PHPUnit\Framework\TestCase;
use App\Services\GarantiasGet;

final class GarantiasGetTest extends TestCase
{

    public function testConsultGarantias()
    {
        $t_consulta = new GarantiasGet();
        $t_c_execute = $t_consulta->showRecordByID(2);
        var_dump($t_c_execute);
        $this->assertIsArray($t_c_execute);
    }

}


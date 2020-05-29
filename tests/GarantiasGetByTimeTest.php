<?php

declare(strict_types = 1);

namespace Test;

use PHPUnit\Framework\TestCase;
use App\Services\GarantiasGet;

final class GarantiasGetByTimeTest extends TestCase
{
    public function testConsultGarantiasFechas()
    {
        $f_consulta = new GarantiasGet();
        $dates = ['2020-05-26 18:44:42','2020-05-27 10:20:57'];
        $f_execute = $f_consulta->searchRecordByDate($dates);
        var_dump($f_execute);
        $this->assertIsArray($f_execute);
    }
}
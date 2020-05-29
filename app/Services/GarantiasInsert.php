<?php

declare(strict_types=1);

namespace App\Services;

use Entity\Entity\Garantia;
use Carbon\Carbon;
use App\AppLogServices;
use App\AppCoreServices as Core;

final class GarantiasInsert extends Core
{
    public $em;

    public function __construct()
    {
        $this->em = parent::makeEntityManager();
    }

    public function createRecord( array $garantias_contract )
    {
        $batch_size = 20;
        $dateTime = Carbon::now()->toDateTime();
        try {
            foreach ($garantias_contract as $key => $value) {
                $garantia = new Garantia();
                $garantia->setContract($value);
                $garantia->setCreated($dateTime);

                $this->em->persist($garantia);

                if(($key % $batch_size)==0){
                    $this->em->flush();
                    $this->em->clear();
                    AppLogServices::logEvent(__FUNCTION__,'Batch ejecutado ' . $key, ['BATCH'=>$key, 'CONTRACT'=>$value], 100);
                }
            }

            $this->em->flush();
            $this->em->clear();
            $salida = $garantia->getId();
            AppLogServices::logEvent(__FUNCTION__,'End Batch ' . $key, ['BATCH' => $key, 'CONTRACT' => $value, 'lastID' => $salida], 100);
            

            
            
        } catch (\PDOException $e) {
            throw $e;
            $salida = 0;
        }
        
        return $salida;
    }

}
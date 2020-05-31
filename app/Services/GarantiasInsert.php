<?php

declare(strict_types=1);

namespace App\Services;

use Entity\Entity\Garantia;
use Carbon\Carbon;
use App\AppLogServices;
use App\AppCoreServices as Core;
use Doctrine\ORM\ORMException;
use Exception;

final class GarantiasInsert extends Core
{
    public $em;

    public function __construct()
    {
        
        if (!isset($this->em)) {
            $this->em = parent::makeEntityManager();
        }
        
    }

    public function createRecord( array $garantias_contract, int $batch_size)
    {
        $dateTime = Carbon::now()->toDateTime();

        try {

            if ($batch_size >= 100) {
                throw new Exception("Advertencia de Ejecucion,", 300);
            }

            foreach ($garantias_contract as $key => $value) {

                $garantia = new Garantia();
                $garantia->setContract($value);
                $garantia->setCreated($dateTime);

                $this->em->persist($garantia);

                if(($key % $batch_size)==0){
                    $this->em->flush();
                    $this->em->clear();
                    AppLogServices::logEvent(__FUNCTION__,'Batch ejecutado ' . $key, ['BATCH_ID'=> parent::setIdWork(), 'CONTRACT'=>$value], 100);
                }
            }

            $this->em->flush();
            $this->em->clear();
            $salida = $garantia->getId();
            AppLogServices::logEvent(__FUNCTION__,'End Batch ' . $key, ['BATCH_ID' => parent::setIdWork(), 'CONTRACT' => $value, 'lastID' => $salida], 100);
            
        } catch (Exception $dep) {
            AppLogServices::logEvent(__FUNCTION__, $dep->getMessage(), ['_ID' => parent::setIdWork()], $dep->getCode());
            $salida = 0;
        } catch (ORMException $e) {
            throw $e;
            AppLogServices::logEvent(__FUNCTION__,'ORMException', ['_ID' => parent::setIdWork(), "ACTION" => "PERSIST", "Internal" => $e], 550);
            $salida = 0;
        }
        
        return $salida;
    }

}
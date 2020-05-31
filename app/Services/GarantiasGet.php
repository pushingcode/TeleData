<?php

declare(strict_types = 1);

namespace App\Services;

use App\AppCoreServices as Core;
use Carbon\Carbon;
use Doctrine\ORM\Query\ResultSetMapping;

final class GarantiasGet extends Core
{
    public $em;

    public function __construct()
    {
        if (!isset($this->em)) {
            $this->em = parent::makeEntityManager();
        }
    }

    public function showRecordByID( int $id )
    {
        if (!isset($this->em)) {
            $this->em = parent::makeEntityManager();
        }

        $query = $this->em->find('Entity\Entity\Garantia',$id);
        $query_a_o = Carbon::instance($query->getCreated());
        $query_a = [
            'CONTRATO'  => $query->getContract(),
            'CREADO'    => $query_a_o->toDayDateTimeString()
        ];
        return $query_a;
    }

    public function searchRecordByDate( array $dates )
    {
        if (!isset($this->em)) {
            $this->em = parent::makeEntityManager();
        }
        
        $start = $dates[0];
        $start = Carbon::parse($start)->toDateTime();

        $end = $dates[1];
        $end = Carbon::parse($end)->toDateTime();

        $rsm = new ResultSetMapping;
        $rsm->addEntityResult('Entity\Entity\Garantia','g');
        $rsm->addFieldResult('g','id','id');
        $rsm->addFieldResult('g','contract','contract');
        $rsm->addFieldResult('g','created_at','created');

        $sql = "SELECT * FROM garantias WHERE created_at BETWEEN ? AND ?";
        $query = $this->em->createNativeQuery($sql,$rsm);
        $query->setParameter(1, $start);
        $query->setParameter(2, $end);

        foreach ($query->getResult() as $value) {
            $query_salida[] = [
                'id'        => $value->getId(),
                'contrato'  => $value->getContract(),
                'Creado'    => $value->getCreated()
            ];
        }

        return $query_salida;
    }
}
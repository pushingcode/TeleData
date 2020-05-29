<?php

declare(strict_types = 1);

namespace Entity\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="garantias")
 */
class Garantia
{
    /** 
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue
     */
    private $id;

    /** @ORM\Column(length=140) */
    private $contract;

    /** @ORM\Column(type="datetime", name="created_at") */
    private $created;

    /** SETTERS */

    public function setContract(string $contract)
    {
        $this->contract = $contract;
    }

    public function setCreated($date)
    {
        $this->created = $date;
    }

    /** GETTERS */

    public function getId()
    {
        return $this->id;
    }

    public function getContract()
    {
        return $this->contract;
    }

    public function getCreated()
    {
        return $this->created;
    }
}
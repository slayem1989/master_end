<?php

namespace whiteLabel\BackOfficeBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Anomalie_fraude
 *
 * @ORM\Table(name="anomalie_fraude")
 * @ORM\Entity(repositoryClass="whiteLabel\BackOfficeBundle\Repository\Anomalie_fraudeRepository")
 */
class Anomalie_fraude
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }
}


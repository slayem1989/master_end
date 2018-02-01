<?php

namespace whiteLabel\BackOfficeBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Anomalie_desistement
 *
 * @ORM\Table(name="anomalie_desistement")
 * @ORM\Entity(repositoryClass="whiteLabel\BackOfficeBundle\Repository\Anomalie_desistementRepository")
 */
class Anomalie_desistement
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


<?php

namespace whiteLabel\BackOfficeBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Anomalie_PND
 *
 * @ORM\Table(name="anomalie__p_n_d")
 * @ORM\Entity(repositoryClass="whiteLabel\BackOfficeBundle\Repository\Anomalie_PNDRepository")
 */
class Anomalie_PND
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


<?php

namespace whiteLabel\BackOfficeBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Anomalie_
 *
 * @ORM\Table(name="anomalie_")
 * @ORM\Entity(repositoryClass="whiteLabel\BackOfficeBundle\Repository\Anomalie_Repository")
 */
class Anomalie_
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


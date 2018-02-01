<?php

namespace whiteLabel\BackOfficeBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Anomalie_paiementAnnule
 *
 * @ORM\Table(name="anomalie_paiement_annule")
 * @ORM\Entity(repositoryClass="whiteLabel\BackOfficeBundle\Repository\Anomalie_paiementAnnuleRepository")
 */
class Anomalie_paiementAnnule
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


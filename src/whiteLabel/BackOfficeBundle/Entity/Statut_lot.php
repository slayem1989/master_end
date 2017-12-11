<?php

namespace whiteLabel\BackOfficeBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Statut_lot
 *
 * @ORM\Table(name="statut_lot")
 * @ORM\Entity(repositoryClass="whiteLabel\BackOfficeBundle\Repository\Statut_lotRepository")
 */
class Statut_lot
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
     * @var string
     *
     * @ORM\Column(name="statut", type="integer")
     */
    private $statut;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=255, nullable=true)
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="slug", type="string", length=255)
     */
    private $slug;



    const STATUT_1 = 1;
    const STATUT_2 = 2;
    const STATUT_3 = 3;
    const STATUT_4 = 4;
    const STATUT_44 = 44;
    const STATUT_5 = 5;
    const STATUT_55 = 55;
    const STATUT_6 = 6;
    const STATUT_7 = 7;
    const STATUT_8 = 8;

    const STATUT_SLUG_1 = 'Intégration d’un nouveau lot de prime';
    const STATUT_SLUG_2 = 'Emission de la ND par le groupe Up';
    const STATUT_SLUG_3 = 'Emission des BAT par le groupe Up';
    const STATUT_SLUG_4 = 'Validation de la ND par TMF';
    const STATUT_SLUG_44 = 'Refus de la ND par TMF';
    const STATUT_SLUG_5 = 'Validation des BAT par TMF';
    const STATUT_SLUG_55 = 'Refus des BAT par TMF';
    const STATUT_SLUG_6 = 'Réception des fonds par le groupe Up si solution 1 ou par TMF si solution 2';
    const STATUT_SLUG_7 = 'Edition des LC';
    const STATUT_SLUG_8 = 'Expédition des LC / Virement';
    


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set statut
     *
     * @param integer $statut
     *
     * @return Statut_lot
     */
    public function setStatut($statut)
    {
        $this->statut = $statut;

        return $this;
    }

    /**
     * Get statut
     *
     * @return integer
     */
    public function getStatut()
    {
        return $this->statut;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Statut_lot
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set slug
     *
     * @param string $slug
     *
     * @return Statut_lot
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Get slug
     *
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }
}

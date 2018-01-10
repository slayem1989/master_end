<?php

namespace whiteLabel\BackOfficeBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Statut_prime
 *
 * @ORM\Table(name="statut_prime")
 * @ORM\Entity(repositoryClass="whiteLabel\BackOfficeBundle\Repository\Statut_primeRepository")
 */
class Statut_prime
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
    const STATUT_5 = 5;
    const STATUT_6 = 6;
    const STATUT_7 = 7;
    const STATUT_8 = 8;
    const STATUT_9 = 9;
    const STATUT_10 = 10;
    const STATUT_11 = 11;
    const STATUT_12 = 12;
    const STATUT_13 = 13;
    const STATUT_14 = 14;
    const STATUT_15 = 15;
    const STATUT_16 = 16;
    const STATUT_17 = 17;
    const STATUT_18 = 18;

    const STATUT_SLUG_1 = 'Intégration d’une nouvelle prime';
    const STATUT_SLUG_2 = 'LC éditée';
    const STATUT_SLUG_3 = 'LC expédiée';
    const STATUT_SLUG_4 = 'Virement émis';
    const STATUT_SLUG_5 = 'Paiement débité';
    const STATUT_SLUG_6 = 'Réception, notification et archive du PND';
    const STATUT_SLUG_7 = 'Envoi du modèle de la lettre de désistement';
    const STATUT_SLUG_8 = 'Réception, notification et archive du désistement';
    const STATUT_SLUG_9 = 'Gestion et traitement de l’incomplétude du désistement tout type';
    const STATUT_SLUG_10 = 'Gestion et traitement du désistement complet tout type sauf décès';
    const STATUT_SLUG_11 = 'Gestion et traitement du désistement complet décès';
    const STATUT_SLUG_12 = 'LC réexpédiée';
    const STATUT_SLUG_13 = 'LC rééditée';
    const STATUT_SLUG_14 = 'Statut généré automatiquement à partir de la date d’édition';
    const STATUT_SLUG_15 = 'Remboursé';
    const STATUT_SLUG_16 = 'Rejet';
    const STATUT_SLUG_17 = 'Opposition';
    const STATUT_SLUG_18 = 'Paiement annulé';



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
     * @return Statut_prime
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
     * @return Statut_prime
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
     * @return Statut_prime
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

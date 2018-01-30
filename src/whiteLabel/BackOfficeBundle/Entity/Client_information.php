<?php

namespace whiteLabel\BackOfficeBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Client_information
 *
 * @ORM\Table(name="client_information")
 * @ORM\Entity(repositoryClass="whiteLabel\BackOfficeBundle\Repository\Client_informationRepository")
 */
class Client_information
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
     * @ORM\Column(name="nom", type="string", length=255)
     */
    private $nom;

    /**
     * @var string
     *
     * @ORM\Column(name="interlocuteur", type="string", length=255)
     */
    private $interlocuteur;

    /**
     * @var string
     *
     * @ORM\Column(name="titre_dispositif", type="string", length=255, nullable=true)
     */
    private $titreDispositif;



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
     * Set nom
     *
     * @param string $nom
     *
     * @return Client_information
     */
    public function setNom($nom)
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * Get nom
     *
     * @return string
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * Set interlocuteur
     *
     * @param string $interlocuteur
     *
     * @return Client_information
     */
    public function setInterlocuteur($interlocuteur)
    {
        $this->interlocuteur = $interlocuteur;

        return $this;
    }

    /**
     * Get interlocuteur
     *
     * @return string
     */
    public function getInterlocuteur()
    {
        return $this->interlocuteur;
    }

    /**
     * Set titreDispositif
     *
     * @param string $titreDispositif
     *
     * @return Client_information
     */
    public function setTitreDispositif($titreDispositif)
    {
        $this->titreDispositif = $titreDispositif;

        return $this;
    }

    /**
     * Get titreDispositif
     *
     * @return string
     */
    public function getTitreDispositif()
    {
        return $this->titreDispositif;
    }
}

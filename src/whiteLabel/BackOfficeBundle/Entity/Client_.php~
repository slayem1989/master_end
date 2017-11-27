<?php

namespace whiteLabel\BackOfficeBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Client_
 *
 * @ORM\Table(name="client_")
 * @ORM\Entity(repositoryClass="whiteLabel\BackOfficeBundle\Repository\Client_Repository")
 */
class Client_
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
     * @var \DateTime
     *
     * @ORM\Column(name="date_creation", type="datetime")
     */
    private $dateCreation;

    /**
     * @var string
     *
     * @ORM\Column(name="auteur_creation", type="string", length=255)
     */
    private $auteurCreation;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_modif", type="datetime")
     */
    private $dateModif;

    /**
     * @var string
     *
     * @ORM\Column(name="auteur_modif", type="string", length=255)
     */
    private $auteurModif;

    /**
     * @ORM\OneToOne(targetEntity="whiteLabel\BackOfficeBundle\Entity\Client_information", cascade={"persist"})
     * @Assert\Valid
     */
    private $client_information;

    /**
     * @ORM\OneToOne(targetEntity="whiteLabel\BackOfficeBundle\Entity\Client_adresseNoteDebit", cascade={"persist"})
     */
    private $client_adresseNoteDebit;

    /**
     * @ORM\OneToOne(targetEntity="whiteLabel\BackOfficeBundle\Entity\Client_adresseFacturation", cascade={"persist"})
     */
    private $client_adresseFacturation;

    /**
     * @var \whiteLabel\BackOfficeBundle\Entity\Client_banque
     *
     * @ORM\OneToMany(targetEntity="whiteLabel\BackOfficeBundle\Entity\Client_banque", mappedBy="client", cascade={"persist"})
     */
    private $banque;



    /**
     * Client_ constructor.
     */
    public function __construct()
    {
        $this->dateCreation = new \Datetime();
        $this->dateModif = new \Datetime();

        $this->auteurCreation = $_SESSION['login']->getUsername();
        $this->auteurModif = $_SESSION['login']->getUsername();

        $this->banque = new \Doctrine\Common\Collections\ArrayCollection();
    }



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
     * Set dateCreation
     *
     * @param \DateTime $dateCreation
     *
     * @return Client_
     */
    public function setDateCreation($dateCreation)
    {
        $this->dateCreation = $dateCreation;

        return $this;
    }

    /**
     * Get dateCreation
     *
     * @return \DateTime
     */
    public function getDateCreation()
    {
        return $this->dateCreation;
    }

    /**
     * Set auteurCreation
     *
     * @param string $auteurCreation
     *
     * @return Client_
     */
    public function setAuteurCreation($auteurCreation)
    {
        $this->auteurCreation = $auteurCreation;

        return $this;
    }

    /**
     * Get auteurCreation
     *
     * @return string
     */
    public function getAuteurCreation()
    {
        return $this->auteurCreation;
    }

    /**
     * Set dateModif
     *
     * @param \DateTime $dateModif
     *
     * @return Client_
     */
    public function setDateModif($dateModif)
    {
        $this->dateModif = $dateModif;

        return $this;
    }

    /**
     * Get dateModif
     *
     * @return \DateTime
     */
    public function getDateModif()
    {
        return $this->dateModif;
    }

    /**
     * Set auteurModif
     *
     * @param string $auteurModif
     *
     * @return Client_
     */
    public function setAuteurModif($auteurModif)
    {
        $this->auteurModif = $auteurModif;

        return $this;
    }

    /**
     * Get auteurModif
     *
     * @return string
     */
    public function getAuteurModif()
    {
        return $this->auteurModif;
    }

    /**
     * Set clientInformation
     *
     * @param \whiteLabel\BackOfficeBundle\Entity\Client_information $clientInformation
     *
     * @return Client_
     */
    public function setClientInformation(\whiteLabel\BackOfficeBundle\Entity\Client_information $clientInformation = null)
    {
        $this->client_information = $clientInformation;

        return $this;
    }

    /**
     * Get clientInformation
     *
     * @return \whiteLabel\BackOfficeBundle\Entity\Client_information
     */
    public function getClientInformation()
    {
        return $this->client_information;
    }

    /**
     * Set clientAdresseNoteDebit
     *
     * @param \whiteLabel\BackOfficeBundle\Entity\Client_adresseNoteDebit $clientAdresseNoteDebit
     *
     * @return Client_
     */
    public function setClientAdresseNoteDebit(\whiteLabel\BackOfficeBundle\Entity\Client_adresseNoteDebit $clientAdresseNoteDebit = null)
    {
        $this->client_adresseNoteDebit = $clientAdresseNoteDebit;

        return $this;
    }

    /**
     * Get clientAdresseNoteDebit
     *
     * @return \whiteLabel\BackOfficeBundle\Entity\Client_adresseNoteDebit
     */
    public function getClientAdresseNoteDebit()
    {
        return $this->client_adresseNoteDebit;
    }

    /**
     * Set clientAdresseFacturation
     *
     * @param \whiteLabel\BackOfficeBundle\Entity\Client_adresseFacturation $clientAdresseFacturation
     *
     * @return Client_
     */
    public function setClientAdresseFacturation(\whiteLabel\BackOfficeBundle\Entity\Client_adresseFacturation $clientAdresseFacturation = null)
    {
        $this->client_adresseFacturation = $clientAdresseFacturation;

        return $this;
    }

    /**
     * Get clientAdresseFacturation
     *
     * @return \whiteLabel\BackOfficeBundle\Entity\Client_adresseFacturation
     */
    public function getClientAdresseFacturation()
    {
        return $this->client_adresseFacturation;
    }

    /**
     * Add banque
     *
     * @param \whiteLabel\BackOfficeBundle\Entity\Client_banque $banque
     *
     * @return Client_
     */
    public function addBanque(\whiteLabel\BackOfficeBundle\Entity\Client_banque $banque)
    {
        $this->banque[] = $banque;

        // On lie la banque au client
        $banque->setClient($this);

        return $this;
    }

    /**
     * Remove banque
     *
     * @param \whiteLabel\BackOfficeBundle\Entity\Client_banque $banque
     */
    public function removeBanque(\whiteLabel\BackOfficeBundle\Entity\Client_banque $banque)
    {
        $this->banque->removeElement($banque);
    }

    /**
     * Get banque
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getBanque()
    {
        return $this->banque;
    }
}

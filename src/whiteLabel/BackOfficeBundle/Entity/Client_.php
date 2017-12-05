<?php

namespace whiteLabel\BackOfficeBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

use blackLabel\GenericBundle\Entity\Log;

/**
 * Client_
 *
 * @ORM\Table(name="client_")
 * @ORM\Entity(repositoryClass="whiteLabel\BackOfficeBundle\Repository\Client_Repository")
 */
class Client_ extends Log
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
        parent::__construct();

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

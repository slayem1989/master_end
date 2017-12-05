<?php

namespace whiteLabel\BackOfficeBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Client_adresseNoteDebit
 *
 * @ORM\Table(name="client_adresse_note_debit")
 * @ORM\Entity(repositoryClass="whiteLabel\BackOfficeBundle\Repository\Client_adresseNoteDebitRepository")
 */
class Client_adresseNoteDebit
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
     * @ORM\Column(name="destinataire", type="string", length=255)
     */
    private $destinataire;

    /**
     * @var string
     *
     * @ORM\Column(name="complement1", type="string", length=255, nullable=true)
     */
    private $complement1;

    /**
     * @var string
     *
     * @ORM\Column(name="complement2", type="string", length=255, nullable=true)
     */
    private $complement2;

    /**
     * @var string
     *
     * @ORM\Column(name="adresse", type="string", length=255)
     */
    private $adresse;

    /**
     * @var string
     *
     * @ORM\Column(name="code_postal", type="string", length=20)
     */
    private $codePostal;

    /**
     * @var string
     *
     * @ORM\Column(name="ville", type="string", length=255)
     */
    private $ville;



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
     * Set destinataire
     *
     * @param string $destinataire
     *
     * @return Client_adresseNoteDebit
     */
    public function setDestinataire($destinataire)
    {
        $this->destinataire = $destinataire;

        return $this;
    }

    /**
     * Get destinataire
     *
     * @return string
     */
    public function getDestinataire()
    {
        return $this->destinataire;
    }

    /**
     * Set complement1
     *
     * @param string $complement1
     *
     * @return Client_adresseNoteDebit
     */
    public function setComplement1($complement1)
    {
        $this->complement1 = $complement1;

        return $this;
    }

    /**
     * Get complement1
     *
     * @return string
     */
    public function getComplement1()
    {
        return $this->complement1;
    }

    /**
     * Set complement2
     *
     * @param string $complement2
     *
     * @return Client_adresseNoteDebit
     */
    public function setComplement2($complement2)
    {
        $this->complement2 = $complement2;

        return $this;
    }

    /**
     * Get complement2
     *
     * @return string
     */
    public function getComplement2()
    {
        return $this->complement2;
    }

    /**
     * Set adresse
     *
     * @param string $adresse
     *
     * @return Client_adresseNoteDebit
     */
    public function setAdresse($adresse)
    {
        $this->adresse = $adresse;

        return $this;
    }

    /**
     * Get adresse
     *
     * @return string
     */
    public function getAdresse()
    {
        return $this->adresse;
    }

    /**
     * Set codePostal
     *
     * @param string $codePostal
     *
     * @return Client_adresseNoteDebit
     */
    public function setCodePostal($codePostal)
    {
        $this->codePostal = $codePostal;

        return $this;
    }

    /**
     * Get codePostal
     *
     * @return string
     */
    public function getCodePostal()
    {
        return $this->codePostal;
    }

    /**
     * Set ville
     *
     * @param string $ville
     *
     * @return Client_adresseNoteDebit
     */
    public function setVille($ville)
    {
        $this->ville = $ville;

        return $this;
    }

    /**
     * Get ville
     *
     * @return string
     */
    public function getVille()
    {
        return $this->ville;
    }
}

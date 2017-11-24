<?php

namespace blackLabel\ImportBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Import_commande
 *
 * @ORM\Table(name="import_commande")
 * @ORM\Entity(repositoryClass="blackLabel\ImportBundle\Repository\Import_commandeRepository")
 */
class Import_commande
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
     * @var int
     *
     * @ORM\Column(name="canal_id", type="integer")
     */
    private $canal_id;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=255, nullable=true)
     */
    private $type;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime", nullable=true)
     */
    private $date;

    /**
     * @var int
     *
     * @ORM\Column(name="numero", type="integer", nullable=true)
     */
    private $numero;

    /**
     * @var string
     *
     * @ORM\Column(name="nom", type="string", length=255, nullable=true)
     */
    private $nom;

    /**
     * @var string
     *
     * @ORM\Column(name="prenom", type="string", length=255, nullable=true)
     */
    private $prenom;

    /**
     * @var string
     *
     * @ORM\Column(name="siren", type="string", length=20, nullable=true)
     */
    private $siren;

    /**
     * @var string
     *
     * @ORM\Column(name="denomination", type="string", length=255, nullable=true)
     */
    private $denomination;

    /**
     * @var string
     *
     * @ORM\Column(name="representant", type="string", length=255, nullable=true)
     */
    private $representant;

    /**
     * @var string
     *
     * @ORM\Column(name="adresse_facturation", type="string", length=255, nullable=true)
     */
    private $adresseFacturation;

    /**
     * @var string
     *
     * @ORM\Column(name="complement_adresse_facturation", type="string", length=255, nullable=true)
     */
    private $complementAdresseFacturation;

    /**
     * @var string
     *
     * @ORM\Column(name="code_postal_facturation", type="string", length=20, nullable=true)
     */
    private $codePostalFacturation;

    /**
     * @var string
     *
     * @ORM\Column(name="ville_facturation", type="string", length=255, nullable=true)
     */
    private $villeFacturation;

    /**
     * @var string
     *
     * @ORM\Column(name="pays", type="string", length=50, nullable=true)
     */
    private $pays;

    /**
     * @var string
     *
     * @ORM\Column(name="adresse_chantier", type="string", length=255, nullable=true)
     */
    private $adresseChantier;

    /**
     * @var string
     *
     * @ORM\Column(name="complement_adresse_chantier", type="string", length=255, nullable=true)
     */
    private $complementAdresseChantier;

    /**
     * @var string
     *
     * @ORM\Column(name="code_postal_chantier", type="string", length=30, nullable=true)
     */
    private $codePostalChantier;

    /**
     * @var string
     *
     * @ORM\Column(name="ville_chantier", type="string", length=255, nullable=true)
     */
    private $villeChantier;

    /**
     * @var string
     *
     * @ORM\Column(name="telephone", type="string", length=30, nullable=true)
     */
    private $telephone;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255, nullable=true)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="iban", type="string", length=255, nullable=true)
     */
    private $iban;

    /**
     * @var string
     *
     * @ORM\Column(name="montant_aide", type="string", length=255, nullable=true)
     */
    private $montantAide;

    /**
     * @var string
     *
     * @ORM\Column(name="numero_action", type="string", length=255, nullable=true)
     */
    private $numeroAction;

    /**
     * @var string
     *
     * @ORM\Column(name="apporteur_affaire", type="string", length=255, nullable=true)
     */
    private $apporteurAffaire;

    /**
     * @var string
     *
     * @ORM\Column(name="onglet", type="string", length=255, nullable=true)
     */
    private $onglet;



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
     * Set canalId
     *
     * @param integer $canalId
     *
     * @return Import_commande
     */
    public function setCanalId($canalId)
    {
        $this->canal_id = $canalId;

        return $this;
    }

    /**
     * Get canalId
     *
     * @return integer
     */
    public function getCanalId()
    {
        return $this->canal_id;
    }

    /**
     * Set type
     *
     * @param string $type
     *
     * @return Import_commande
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     *
     * @return Import_commande
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set numero
     *
     * @param integer $numero
     *
     * @return Import_commande
     */
    public function setNumero($numero)
    {
        $this->numero = $numero;

        return $this;
    }

    /**
     * Get numero
     *
     * @return integer
     */
    public function getNumero()
    {
        return $this->numero;
    }

    /**
     * Set nom
     *
     * @param string $nom
     *
     * @return Import_commande
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
     * Set prenom
     *
     * @param string $prenom
     *
     * @return Import_commande
     */
    public function setPrenom($prenom)
    {
        $this->prenom = $prenom;

        return $this;
    }

    /**
     * Get prenom
     *
     * @return string
     */
    public function getPrenom()
    {
        return $this->prenom;
    }

    /**
     * Set siren
     *
     * @param string $siren
     *
     * @return Import_commande
     */
    public function setSiren($siren)
    {
        $this->siren = $siren;

        return $this;
    }

    /**
     * Get siren
     *
     * @return string
     */
    public function getSiren()
    {
        return $this->siren;
    }

    /**
     * Set denomination
     *
     * @param string $denomination
     *
     * @return Import_commande
     */
    public function setDenomination($denomination)
    {
        $this->denomination = $denomination;

        return $this;
    }

    /**
     * Get denomination
     *
     * @return string
     */
    public function getDenomination()
    {
        return $this->denomination;
    }

    /**
     * Set representant
     *
     * @param string $representant
     *
     * @return Import_commande
     */
    public function setRepresentant($representant)
    {
        $this->representant = $representant;

        return $this;
    }

    /**
     * Get representant
     *
     * @return string
     */
    public function getRepresentant()
    {
        return $this->representant;
    }

    /**
     * Set adresseFacturation
     *
     * @param string $adresseFacturation
     *
     * @return Import_commande
     */
    public function setAdresseFacturation($adresseFacturation)
    {
        $this->adresseFacturation = $adresseFacturation;

        return $this;
    }

    /**
     * Get adresseFacturation
     *
     * @return string
     */
    public function getAdresseFacturation()
    {
        return $this->adresseFacturation;
    }

    /**
     * Set complementAdresseFacturation
     *
     * @param string $complementAdresseFacturation
     *
     * @return Import_commande
     */
    public function setComplementAdresseFacturation($complementAdresseFacturation)
    {
        $this->complementAdresseFacturation = $complementAdresseFacturation;

        return $this;
    }

    /**
     * Get complementAdresseFacturation
     *
     * @return string
     */
    public function getComplementAdresseFacturation()
    {
        return $this->complementAdresseFacturation;
    }

    /**
     * Set codePostalFacturation
     *
     * @param string $codePostalFacturation
     *
     * @return Import_commande
     */
    public function setCodePostalFacturation($codePostalFacturation)
    {
        $this->codePostalFacturation = $codePostalFacturation;

        return $this;
    }

    /**
     * Get codePostalFacturation
     *
     * @return string
     */
    public function getCodePostalFacturation()
    {
        return $this->codePostalFacturation;
    }

    /**
     * Set villeFacturation
     *
     * @param string $villeFacturation
     *
     * @return Import_commande
     */
    public function setVilleFacturation($villeFacturation)
    {
        $this->villeFacturation = $villeFacturation;

        return $this;
    }

    /**
     * Get villeFacturation
     *
     * @return string
     */
    public function getVilleFacturation()
    {
        return $this->villeFacturation;
    }

    /**
     * Set pays
     *
     * @param string $pays
     *
     * @return Import_commande
     */
    public function setPays($pays)
    {
        $this->pays = $pays;

        return $this;
    }

    /**
     * Get pays
     *
     * @return string
     */
    public function getPays()
    {
        return $this->pays;
    }

    /**
     * Set adresseChantier
     *
     * @param string $adresseChantier
     *
     * @return Import_commande
     */
    public function setAdresseChantier($adresseChantier)
    {
        $this->adresseChantier = $adresseChantier;

        return $this;
    }

    /**
     * Get adresseChantier
     *
     * @return string
     */
    public function getAdresseChantier()
    {
        return $this->adresseChantier;
    }

    /**
     * Set complementAdresseChantier
     *
     * @param string $complementAdresseChantier
     *
     * @return Import_commande
     */
    public function setComplementAdresseChantier($complementAdresseChantier)
    {
        $this->complementAdresseChantier = $complementAdresseChantier;

        return $this;
    }

    /**
     * Get complementAdresseChantier
     *
     * @return string
     */
    public function getComplementAdresseChantier()
    {
        return $this->complementAdresseChantier;
    }

    /**
     * Set codePostalChantier
     *
     * @param string $codePostalChantier
     *
     * @return Import_commande
     */
    public function setCodePostalChantier($codePostalChantier)
    {
        $this->codePostalChantier = $codePostalChantier;

        return $this;
    }

    /**
     * Get codePostalChantier
     *
     * @return string
     */
    public function getCodePostalChantier()
    {
        return $this->codePostalChantier;
    }

    /**
     * Set villeChantier
     *
     * @param string $villeChantier
     *
     * @return Import_commande
     */
    public function setVilleChantier($villeChantier)
    {
        $this->villeChantier = $villeChantier;

        return $this;
    }

    /**
     * Get villeChantier
     *
     * @return string
     */
    public function getVilleChantier()
    {
        return $this->villeChantier;
    }

    /**
     * Set telephone
     *
     * @param string $telephone
     *
     * @return Import_commande
     */
    public function setTelephone($telephone)
    {
        $this->telephone = $telephone;

        return $this;
    }

    /**
     * Get telephone
     *
     * @return string
     */
    public function getTelephone()
    {
        return $this->telephone;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return Import_commande
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set iban
     *
     * @param string $iban
     *
     * @return Import_commande
     */
    public function setIban($iban)
    {
        $this->iban = $iban;

        return $this;
    }

    /**
     * Get iban
     *
     * @return string
     */
    public function getIban()
    {
        return $this->iban;
    }

    /**
     * Set montantAide
     *
     * @param string $montantAide
     *
     * @return Import_commande
     */
    public function setMontantAide($montantAide)
    {
        $this->montantAide = $montantAide;

        return $this;
    }

    /**
     * Get montantAide
     *
     * @return string
     */
    public function getMontantAide()
    {
        return $this->montantAide;
    }

    /**
     * Set numeroAction
     *
     * @param string $numeroAction
     *
     * @return Import_commande
     */
    public function setNumeroAction($numeroAction)
    {
        $this->numeroAction = $numeroAction;

        return $this;
    }

    /**
     * Get numeroAction
     *
     * @return string
     */
    public function getNumeroAction()
    {
        return $this->numeroAction;
    }

    /**
     * Set apporteurAffaire
     *
     * @param string $apporteurAffaire
     *
     * @return Import_commande
     */
    public function setApporteurAffaire($apporteurAffaire)
    {
        $this->apporteurAffaire = $apporteurAffaire;

        return $this;
    }

    /**
     * Get apporteurAffaire
     *
     * @return string
     */
    public function getApporteurAffaire()
    {
        return $this->apporteurAffaire;
    }

    /**
     * Set onglet
     *
     * @param string $onglet
     *
     * @return Import_commande
     */
    public function setOnglet($onglet)
    {
        $this->onglet = $onglet;

        return $this;
    }

    /**
     * Get onglet
     *
     * @return string
     */
    public function getOnglet()
    {
        return $this->onglet;
    }
}
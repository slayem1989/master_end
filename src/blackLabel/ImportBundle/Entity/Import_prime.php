<?php

namespace blackLabel\ImportBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use whiteLabel\BackOfficeBundle\Entity\Statut_prime;
use blackLabel\GenericBundle\Entity\Log;

/**
 * Import_prime
 *
 * @ORM\Table(name="import_prime", indexes={
 *      @ORM\Index(name="canal_idx", columns={"canal_id"}),
 *      @ORM\Index(name="statut_idx", columns={"statut_id"})
 * })
 * @ORM\Entity(repositoryClass="blackLabel\ImportBundle\Repository\Import_primeRepository")
 */
class Import_prime extends Log
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
     * @ORM\Column(name="statut_id", type="integer")
     */
    private $statutId;

    /**
     * @var int
     *
     * @ORM\Column(name="canal_id", type="integer")
     */
    private $canalId;

    /**
     * @var int
     *
     * @ORM\Column(name="index_prime", type="integer")
     */
    private $index;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=255)
     */
    private $type;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime")
     */
    private $date;

    /**
     * @var int
     *
     * @ORM\Column(name="numero", type="string", length=7, nullable=true)
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
     * @ORM\Column(name="adresse_facturation", type="string", length=255)
     */
    private $adresseFacturation;

    /**
     * @var string
     *
     * @ORM\Column(name="complement_facturation", type="string", length=255, nullable=true)
     */
    private $complementFacturation;

    /**
     * @var string
     *
     * @ORM\Column(name="code_postal_facturation", type="string", length=20)
     */
    private $codePostalFacturation;

    /**
     * @var string
     *
     * @ORM\Column(name="ville_facturation", type="string", length=255)
     */
    private $villeFacturation;

    /**
     * @var string
     *
     * @ORM\Column(name="pays_facturation", type="string", length=50, nullable=true)
     */
    private $paysFacturation;

    /**
     * @var string
     *
     * @ORM\Column(name="adresse_chantier", type="string", length=255)
     */
    private $adresseChantier;

    /**
     * @var string
     *
     * @ORM\Column(name="complement_chantier", type="string", length=255, nullable=true)
     */
    private $complementChantier;

    /**
     * @var string
     *
     * @ORM\Column(name="code_postal_chantier", type="string", length=30)
     */
    private $codePostalChantier;

    /**
     * @var string
     *
     * @ORM\Column(name="ville_chantier", type="string", length=255)
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
     * @ORM\Column(name="montant_aide", type="decimal", precision=12, scale=2)
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
     * @var int
     *
     * @ORM\Column(name="modele_id", type="integer", nullable=true)
     */
    private $modeleId;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_operation", type="datetime", nullable=true)
     */
    private $dateOperation;

    /**
     * @var string
     *
     * @ORM\Column(name="montant_debite", type="decimal", precision=12, scale=2, nullable=true)
     */
    private $montantDebite;



    /**
     * Import_prime constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->statutId = Statut_prime::STATUT_1;
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
     * Set statutId
     *
     * @param integer $statutId
     *
     * @return Import_prime
     */
    public function setStatutId($statutId)
    {
        $this->statutId = $statutId;

        return $this;
    }

    /**
     * Get statutId
     *
     * @return integer
     */
    public function getStatutId()
    {
        return $this->statutId;
    }

    /**
     * Set canalId
     *
     * @param integer $canalId
     *
     * @return Import_prime
     */
    public function setCanalId($canalId)
    {
        $this->canalId = $canalId;

        return $this;
    }

    /**
     * Get canalId
     *
     * @return integer
     */
    public function getCanalId()
    {
        return $this->canalId;
    }

    /**
     * Set index
     *
     * @param integer $index
     *
     * @return Import_prime
     */
    public function setIndex($index)
    {
        $this->index = $index;

        return $this;
    }

    /**
     * Get index
     *
     * @return integer
     */
    public function getIndex()
    {
        return $this->index;
    }

    /**
     * Set type
     *
     * @param string $type
     *
     * @return Import_prime
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
     * @return Import_prime
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
     * @return Import_prime
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
     * @return Import_prime
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
     * @return Import_prime
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
     * @return Import_prime
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
     * @return Import_prime
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
     * @return Import_prime
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
     * @return Import_prime
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
     * Set complementFacturation
     *
     * @param string $complementFacturation
     *
     * @return Import_prime
     */
    public function setComplementFacturation($complementFacturation)
    {
        $this->complementFacturation = $complementFacturation;

        return $this;
    }

    /**
     * Get complementFacturation
     *
     * @return string
     */
    public function getComplementFacturation()
    {
        return $this->complementFacturation;
    }

    /**
     * Set codePostalFacturation
     *
     * @param string $codePostalFacturation
     *
     * @return Import_prime
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
     * @return Import_prime
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
     * Set paysFacturation
     *
     * @param string $paysFacturation
     *
     * @return Import_prime
     */
    public function setPaysFacturation($paysFacturation)
    {
        $this->paysFacturation = $paysFacturation;

        return $this;
    }

    /**
     * Get paysFacturation
     *
     * @return string
     */
    public function getPaysFacturation()
    {
        return $this->paysFacturation;
    }

    /**
     * Set adresseChantier
     *
     * @param string $adresseChantier
     *
     * @return Import_prime
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
     * Set complementChantier
     *
     * @param string $complementChantier
     *
     * @return Import_prime
     */
    public function setComplementChantier($complementChantier)
    {
        $this->complementChantier = $complementChantier;

        return $this;
    }

    /**
     * Get complementChantier
     *
     * @return string
     */
    public function getComplementChantier()
    {
        return $this->complementChantier;
    }

    /**
     * Set codePostalChantier
     *
     * @param string $codePostalChantier
     *
     * @return Import_prime
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
     * @return Import_prime
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
     * @return Import_prime
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
     * @return Import_prime
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
     * @return Import_prime
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
     * @return Import_prime
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
     * @return Import_prime
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
     * @return Import_prime
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
     * @return Import_prime
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

    /**
     * Set modeleId
     *
     * @param integer $modeleId
     *
     * @return Import_prime
     */
    public function setModeleId($modeleId)
    {
        $this->modeleId = $modeleId;

        return $this;
    }

    /**
     * Get modeleId
     *
     * @return integer
     */
    public function getModeleId()
    {
        return $this->modeleId;
    }

    /**
     * Set dateOperation
     *
     * @param \DateTime $dateOperation
     *
     * @return Import_prime
     */
    public function setDateOperation($dateOperation)
    {
        $this->dateOperation = $dateOperation;

        return $this;
    }

    /**
     * Get dateOperation
     *
     * @return \DateTime
     */
    public function getDateOperation()
    {
        return $this->dateOperation;
    }

    /**
     * Set montantDebite
     *
     * @param string $montantDebite
     *
     * @return Import_prime
     */
    public function setMontantDebite($montantDebite)
    {
        $this->montantDebite = $montantDebite;

        return $this;
    }

    /**
     * Get montantDebite
     *
     * @return string
     */
    public function getMontantDebite()
    {
        return $this->montantDebite;
    }
}

<?php

namespace blackLabel\ImportBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;

use blackLabel\GenericBundle\Entity\Log;
use whiteLabel\BackOfficeBundle\Entity\Statut_lot;

/**
 * Import_lot
 *
 * @ORM\Table(name="import_lot", indexes={
 *      @ORM\Index(name="client_idx", columns={"client_id"}),
 *      @ORM\Index(name="banque_idx", columns={"banque_id"}),
 *      @ORM\Index(name="statut_idx", columns={"statut_id"})
 * })
 * @ORM\Entity(repositoryClass="blackLabel\ImportBundle\Repository\Import_lotRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Import_lot extends Log
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
     * @ORM\Column(name="numero", type="integer", nullable=true)
     */
    private $numero;

    /**
     * @var int
     *
     * @ORM\Column(name="client_id", type="integer")
     */
    private $clientId;

    /**
     * @var int
     *
     * @ORM\Column(name="banque_id", type="integer")
     */
    private $banqueId;

    /**
     * @var int
     *
     * @ORM\Column(name="statut_id", type="integer")
     */
    private $statutId;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_statut_1", type="datetime", nullable=true)
     */
    private $dateStatut1;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_statut_11", type="datetime", nullable=true)
     */
    private $dateStatut11;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_statut_2", type="datetime", nullable=true)
     */
    private $dateStatut2;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_statut_3", type="datetime", nullable=true)
     */
    private $dateStatut3;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_statut_4", type="datetime", nullable=true)
     */
    private $dateStatut4;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_statut_44", type="datetime", nullable=true)
     */
    private $dateStatut44;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_statut_5", type="datetime", nullable=true)
     */
    private $dateStatut5;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_statut_55", type="datetime", nullable=true)
     */
    private $dateStatut55;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_statut_6", type="datetime", nullable=true)
     */
    private $dateStatut6;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_statut_7", type="datetime", nullable=true)
     */
    private $dateStatut7;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_statut_8", type="datetime", nullable=true)
     */
    private $dateStatut8;

    /**
     * @ORM\Column(name="file_url", type="string", length=255)
     */
    private $file_url;

    /**
     * @ORM\Column(name="file_alt", type="string", length=255)
     */
    private $file_alt;

    /**
     * @Assert\File(
     *     maxSize="20480k",
     *     mimeTypes={ "application/vnd.ms-excel", "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet" }
     * )
     */
    private $file;

    private $tempFilename;



    /**
     * Import_lot constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->statutId = Statut_Lot::STATUT_1;
        $this->dateStatut1 = new \Datetime();
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
     * Set numero
     *
     * @param integer $numero
     *
     * @return Import_lot
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
     * Set clientId
     *
     * @param integer $clientId
     *
     * @return Import_lot
     */
    public function setClientId($clientId)
    {
        $this->clientId = $clientId;

        return $this;
    }

    /**
     * Get clientId
     *
     * @return integer
     */
    public function getClientId()
    {
        return $this->clientId;
    }

    /**
     * Set banqueId
     *
     * @param integer $banqueId
     *
     * @return Import_lot
     */
    public function setBanqueId($banqueId)
    {
        $this->banqueId = $banqueId;

        return $this;
    }

    /**
     * Get banqueId
     *
     * @return integer
     */
    public function getBanqueId()
    {
        return $this->banqueId;
    }

    /**
     * Set statutId
     *
     * @param integer $statutId
     *
     * @return Import_lot
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
     * Set dateStatut1
     *
     * @param \DateTime $dateStatut1
     *
     * @return Import_lot
     */
    public function setDateStatut1($dateStatut1)
    {
        $this->dateStatut1 = $dateStatut1;

        return $this;
    }

    /**
     * Get dateStatut1
     *
     * @return \DateTime
     */
    public function getDateStatut1()
    {
        return $this->dateStatut1;
    }

    /**
     * Set dateStatut11
     *
     * @param \DateTime $dateStatut11
     *
     * @return Import_lot
     */
    public function setDateStatut11($dateStatut11)
    {
        $this->dateStatut11 = $dateStatut11;

        return $this;
    }

    /**
     * Get dateStatut11
     *
     * @return \DateTime
     */
    public function getDateStatut11()
    {
        return $this->dateStatut11;
    }

    /**
     * Set dateStatut2
     *
     * @param \DateTime $dateStatut2
     *
     * @return Import_lot
     */
    public function setDateStatut2($dateStatut2)
    {
        $this->dateStatut2 = $dateStatut2;

        return $this;
    }

    /**
     * Get dateStatut2
     *
     * @return \DateTime
     */
    public function getDateStatut2()
    {
        return $this->dateStatut2;
    }

    /**
     * Set dateStatut3
     *
     * @param \DateTime $dateStatut3
     *
     * @return Import_lot
     */
    public function setDateStatut3($dateStatut3)
    {
        $this->dateStatut3 = $dateStatut3;

        return $this;
    }

    /**
     * Get dateStatut3
     *
     * @return \DateTime
     */
    public function getDateStatut3()
    {
        return $this->dateStatut3;
    }

    /**
     * Set dateStatut4
     *
     * @param \DateTime $dateStatut4
     *
     * @return Import_lot
     */
    public function setDateStatut4($dateStatut4)
    {
        $this->dateStatut4 = $dateStatut4;

        return $this;
    }

    /**
     * Get dateStatut4
     *
     * @return \DateTime
     */
    public function getDateStatut4()
    {
        return $this->dateStatut4;
    }

    /**
     * Set dateStatut44
     *
     * @param \DateTime $dateStatut44
     *
     * @return Import_lot
     */
    public function setDateStatut44($dateStatut44)
    {
        $this->dateStatut44 = $dateStatut44;

        return $this;
    }

    /**
     * Get dateStatut44
     *
     * @return \DateTime
     */
    public function getDateStatut44()
    {
        return $this->dateStatut44;
    }

    /**
     * Set dateStatut5
     *
     * @param \DateTime $dateStatut5
     *
     * @return Import_lot
     */
    public function setDateStatut5($dateStatut5)
    {
        $this->dateStatut5 = $dateStatut5;

        return $this;
    }

    /**
     * Get dateStatut5
     *
     * @return \DateTime
     */
    public function getDateStatut5()
    {
        return $this->dateStatut5;
    }

    /**
     * Set dateStatut55
     *
     * @param \DateTime $dateStatut55
     *
     * @return Import_lot
     */
    public function setDateStatut55($dateStatut55)
    {
        $this->dateStatut55 = $dateStatut55;

        return $this;
    }

    /**
     * Get dateStatut55
     *
     * @return \DateTime
     */
    public function getDateStatut55()
    {
        return $this->dateStatut55;
    }

    /**
     * Set dateStatut6
     *
     * @param \DateTime $dateStatut6
     *
     * @return Import_lot
     */
    public function setDateStatut6($dateStatut6)
    {
        $this->dateStatut6 = $dateStatut6;

        return $this;
    }

    /**
     * Get dateStatut6
     *
     * @return \DateTime
     */
    public function getDateStatut6()
    {
        return $this->dateStatut6;
    }

    /**
     * Set dateStatut7
     *
     * @param \DateTime $dateStatut7
     *
     * @return Import_lot
     */
    public function setDateStatut7($dateStatut7)
    {
        $this->dateStatut7 = $dateStatut7;

        return $this;
    }

    /**
     * Get dateStatut7
     *
     * @return \DateTime
     */
    public function getDateStatut7()
    {
        return $this->dateStatut7;
    }

    /**
     * Set dateStatut8
     *
     * @param \DateTime $dateStatut8
     *
     * @return Import_lot
     */
    public function setDateStatut8($dateStatut8)
    {
        $this->dateStatut8 = $dateStatut8;

        return $this;
    }

    /**
     * Get dateStatut8
     *
     * @return \DateTime
     */
    public function getDateStatut8()
    {
        return $this->dateStatut8;
    }

    /**
     * Set fileUrl
     *
     * @param string $fileUrl
     *
     * @return Import_lot
     */
    public function setFileUrl($fileUrl)
    {
        $this->file_url = $fileUrl;

        return $this;
    }

    /**
     * Get fileUrl
     *
     * @return string
     */
    public function getFileUrl()
    {
        return $this->file_url;
    }

    /**
     * Set fileAlt
     *
     * @param string $fileAlt
     *
     * @return Import_lot
     */
    public function setFileAlt($fileAlt)
    {
        $this->file_alt = $fileAlt;

        return $this;
    }

    /**
     * Get fileAlt
     *
     * @return string
     */
    public function getFileAlt()
    {
        return $this->file_alt;
    }

    /* *****************************************************************
                    FONCTIONS POUR LES IMPORTS TELECHARGES
    *******************************************************************/
    /**
     * @return mixed
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * @param UploadedFile $file
     */
    public function setFile(UploadedFile $file)
    {
        $this->file = $file;

        // On vérifie si on avait déjà un fichier pour cette entité
        if (null !== $this->file_url) {
            // On sauvegarde l'extension du fichier pour le supprimer plus tard
            $this->tempFilename = $this->file_url;

            // On réinitialise les valeurs des attributs url et alt
            $this->file_url = null;
            $this->file_alt = null;
        }
    }

    /* *****************************************************************
                        EVENEMENTS POUR LES IMPORTS
    *******************************************************************/
    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function file_preUpload()
    {
        // Si jamais il n'y a pas de fichier (champ facultatif), on ne fait rien
        if (null === $this->file) {
            return;
        }

        // Le nom du fichier est son id, on doit juste stocker également son extension
        // Pour faire propre, on devrait renommer cet attribut en « extension », plutôt que « url »
        $this->file_url = $this->file->guessExtension();

        // Et on génère l'attribut alt de la balise <img>, à la valeur du nom du fichier sur le PC de l'internaute
        $this->file_alt = $this->file->getClientOriginalName();
    }

    /**
     * @ORM\PostPersist()
     * @ORM\PostUpdate()
     */
    public function file_upload()
    {
        // Si jamais il n'y a pas de fichier (champ facultatif), on ne fait rien
        if (null === $this->file) {
            return;
        }

        // Si on avait un ancien fichier, on le supprime
        if (null !== $this->tempFilename) {
            $oldFile = $this->file_getUploadRootDir() . '/' . $this->id . '_import.' . $this->tempFilename;
            if (file_exists($oldFile)) {
                unlink($oldFile);
            }
        }

        // On déplace le fichier envoyé dans le répertoire de notre choix
        $this->file->move(
            $this->file_getUploadRootDir(), // Le répertoire de destination
            $this->id . '_import.' . $this->file_url   // Le nom du fichier à créer, ici « id.extension »
        );
    }

    /**
     * @ORM\PreRemove()
     */
    public function file_preRemoveUpload()
    {
        // On sauvegarde temporairement le nom du fichier, car il dépend de l'id
        $this->tempFilename = $this->file_getUploadRootDir() . '/' . $this->id . '_import.' . $this->file_url;
    }

    /**
     * @ORM\PostRemove()
     */
    public function file_removeUpload()
    {
        // En PostRemove, on n'a pas accès à l'id, on utilise notre nom sauvegardé
        if (file_exists($this->tempFilename)) {
            // On supprime le fichier
            unlink($this->tempFilename);
        }
    }

    /**
     * @return string
     */
    public function file_getUploadDir()
    {
        // On retourne le chemin relatif vers l'image pour un navigateur
        return 'import';
    }

    /**
     * @return string
     */
    protected function file_getUploadRootDir()
    {
        // On retourne le chemin relatif vers l'image pour notre code PHP
        return __DIR__ . '/../../../../data/' . $this->file_getUploadDir();
    }

    /**
     * @return string
     */
    public function file_getWebPath()
    {
        return $this->file_getUploadDir() . '/' . $this->getId() . '_import.'.$this->getFileUrl();
    }
}

<?php

namespace whiteLabel\BackOfficeBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;

use blackLabel\GenericBundle\Entity\Log;

/**
 * Cheque_rapprochementBancaire
 *
 * @ORM\Table(name="cheque_rapprochement_bancaire", indexes={
 *      @ORM\Index(name="client_idx", columns={"client_id"})
 * })
 * @ORM\Entity(repositoryClass="whiteLabel\BackOfficeBundle\Repository\Cheque_rapprochementBancaireRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Cheque_rapprochementBancaire extends Log
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
     * @ORM\Column(name="client_id", type="integer")
     */
    private $clientId;

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
     * Cheque_rapprochementBancaire constructor.
     */
    public function __construct()
    {
        parent::__construct();
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
     * Set clientId
     *
     * @param integer $clientId
     *
     * @return Cheque_rapprochementBancaire
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
     * Set fileUrl
     *
     * @param string $fileUrl
     *
     * @return Cheque_rapprochementBancaire
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
     * @return Cheque_rapprochementBancaire
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
                    FONCTIONS POUR LE FICHIER TELECHARGE
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
                        EVENEMENTS POUR LE FICHIER
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
            $oldFile = $this->file_getUploadRootDir() . '/' . $this->id . '_rapprochement_bancaire.' . $this->tempFilename;
            if (file_exists($oldFile)) {
                unlink($oldFile);
            }
        }

        // On déplace le fichier envoyé dans le répertoire de notre choix
        $this->file->move(
            $this->file_getUploadRootDir(), // Le répertoire de destination
            $this->id . '_rapprochement_bancaire.' . $this->file_url   // Le nom du fichier à créer, ici « id.extension »
        );
    }

    /**
     * @ORM\PreRemove()
     */
    public function file_preRemoveUpload()
    {
        // On sauvegarde temporairement le nom du fichier, car il dépend de l'id
        $this->tempFilename = $this->file_getUploadRootDir() . '/' . $this->id . '_rapprochement_bancaire.' . $this->file_url;
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
        // On retourne le chemin relatif vers le fichier pour un navigateur
        return $this->getClientId() . '/rapprochementBancaire';
    }

    /**
     * @return string
     */
    protected function file_getUploadRootDir()
    {
        // On retourne le chemin relatif vers le fichier pour notre code PHP
        return __DIR__ . '/../../../../data/' . $this->file_getUploadDir();
    }

    /**
     * @return string
     */
    public function file_getWebPath()
    {
        return $this->file_getUploadDir() . '/' . $this->getId() . '_rapprochement_bancaire.'.$this->getFileUrl();
    }
}

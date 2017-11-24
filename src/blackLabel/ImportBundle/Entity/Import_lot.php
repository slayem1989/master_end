<?php

namespace blackLabel\ImportBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use blackLabel\GenericBundle\Entity\Log;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Import_lot
 *
 * @ORM\Table(name="import_lot")
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
     * @var string
     *
     * @ORM\Column(name="client", type="string", length=100)
     */
    private $client;

    /**
     * @ORM\Column(name="file_url", type="string", length=255)
     */
    private $file_url;

    /**
     * @ORM\Column(name="file_alt", type="string", length=255)
     */
    private $file_alt;

    /**
     * @Assert\File(maxSize="20480k", mimeTypes={ "application/vnd.ms-excel", "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet" })
     */
    private $file;

    private $tempFilename;



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
     * Set client
     *
     * @param string $client
     *
     * @return Import_lot
     */
    public function setClient($client)
    {
        $this->client = $client;

        return $this;
    }

    /**
     * Get client
     *
     * @return string
     */
    public function getClient()
    {
        return $this->client;
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
        $clientKey = explode(' | ', $this->getClient());
        if (null !== $this->tempFilename) {
            $oldFile = $this->file_getUploadRootDir() . '/' . $this->id . '_' . $clientKey[1] . '.' . $this->tempFilename;
            if (file_exists($oldFile)) {
                unlink($oldFile);
            }
        }

        // On déplace le fichier envoyé dans le répertoire de notre choix
        $this->file->move(
            $this->file_getUploadRootDir(), // Le répertoire de destination
            $this->id . '_' . $clientKey[1] . '.' . $this->file_url   // Le nom du fichier à créer, ici « id.extension »
        );
    }

    /**
     * @ORM\PreRemove()
     */
    public function file_preRemoveUpload()
    {
        // On sauvegarde temporairement le nom du fichier, car il dépend de l'id
        $clientKey = explode(' | ', $this->getClient());
        $this->tempFilename = $this->file_getUploadRootDir() . '/' . $this->id . '_' . $clientKey[1] . '.' . $this->file_url;
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
        $clientKey = explode(' | ', $this->getClient());
        return $this->file_getUploadDir() . '/' . $this->getId() . '_' . $clientKey[1] . '.'.$this->getFileUrl();
    }
}

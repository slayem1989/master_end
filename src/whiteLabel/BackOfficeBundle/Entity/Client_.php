<?php

namespace whiteLabel\BackOfficeBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;

use blackLabel\GenericBundle\Entity\Log;

/**
 * Client_
 *
 * @ORM\Table(name="client_")
 * @ORM\Entity(repositoryClass="whiteLabel\BackOfficeBundle\Repository\Client_Repository")
 * @ORM\HasLifecycleCallbacks
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
     * @ORM\Column(name="logo_url", type="string", length=255)
     */
    private $logo_url;

    /**
     * @ORM\Column(name="logo_alt", type="string", length=255)
     */
    private $logo_alt;

    /**
     * @Assert\File(
     *     maxSize="5120k",
     *     mimeTypes={ "image/jpg","image/jpeg","image/png" },
     *     mimeTypesMessage = "Format du fichier invalide. Les formats suivants sont acceptés: .jpg, .jpeg, .png"
     * )
     */
    private $logo;

    private $tempFilename;



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

    /**
     * Set logoUrl
     *
     * @param string $logoUrl
     *
     * @return Client_
     */
    public function setLogoUrl($logoUrl)
    {
        $this->logo_url = $logoUrl;

        return $this;
    }

    /**
     * Get logoUrl
     *
     * @return string
     */
    public function getLogoUrl()
    {
        return $this->logo_url;
    }

    /**
     * Set logoAlt
     *
     * @param string $logoAlt
     *
     * @return Client_
     */
    public function setLogoAlt($logoAlt)
    {
        $this->logo_alt = $logoAlt;

        return $this;
    }

    /**
     * Get logoAlt
     *
     * @return string
     */
    public function getLogoAlt()
    {
        return $this->logo_alt;
    }

    /* *****************************************************************
                    FONCTIONS POUR LES LOGOS TELECHARGES
    *******************************************************************/
    /**
     * @return mixed
     */
    public function getLogo()
    {
        return $this->logo;
    }

    /**
     * @param UploadedFile $logo
     */
    public function setLogo(UploadedFile $logo)
    {
        $this->logo = $logo;

        // On vérifie si on avait déjà un fichier pour cette entité
        if (null !== $this->logo_url) {
            // On sauvegarde l'extension du fichier pour le supprimer plus tard
            $this->tempFilename = $this->logo_url;

            // On réinitialise les valeurs des attributs url et alt
            $this->logo_url = null;
            $this->logo_alt = null;
        }
    }

    /* *****************************************************************
                        EVENEMENTS POUR LES LOGOS
    *******************************************************************/
    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function logo_preUpload()
    {
        // Si jamais il n'y a pas de fichier (champ facultatif), on ne fait rien
        if (null === $this->logo) {
            return;
        }

        // Le nom du fichier est son id, on doit juste stocker également son extension
        // Pour faire propre, on devrait renommer cet attribut en « extension », plutôt que « url »
        $this->logo_url = $this->logo->guessExtension();

        // Et on génère l'attribut alt de la balise <img>, à la valeur du nom du fichier sur le PC de l'internaute
        $this->logo_alt = $this->logo->getClientOriginalName();
    }

    /**
     * @ORM\PostPersist()
     * @ORM\PostUpdate()
     */
    public function logo_upload()
    {
        // Si jamais il n'y a pas de fichier (champ facultatif), on ne fait rien
        if (null === $this->logo) {
            return;
        }

        // Si on avait un ancien fichier, on le supprime
        if (null !== $this->tempFilename) {
            $oldFile = $this->logo_getUploadRootDir() . '/' . $this->id . '_logo' . '.' . $this->tempFilename;
            if (file_exists($oldFile)) {
                unlink($oldFile);
            }
        }

        // On déplace le fichier envoyé dans le répertoire de notre choix
        $this->logo->move(
            $this->logo_getUploadRootDir(), // Le répertoire de destination
            $this->id . '_logo' . '.' . $this->logo_url   // Le nom du fichier à créer, ici « id.extension »
        );
    }

    /**
     * @ORM\PreRemove()
     */
    public function logo_preRemoveUpload()
    {
        // On sauvegarde temporairement le nom du fichier, car il dépend de l'id
        $this->tempFilename = $this->logo_getUploadRootDir() . '/' . $this->id . '_logo' . '.' . $this->logo_url;
    }

    /**
     * @ORM\PostRemove()
     */
    public function logo_removeUpload()
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
    public function logo_getUploadDir()
    {
        // On retourne le chemin relatif vers l'image pour un navigateur
        return $this->getId() . '/logo';
    }

    /**
     * @return string
     */
    protected function logo_getUploadRootDir()
    {
        // On retourne le chemin relatif vers l'image pour notre code PHP
        return __DIR__ . '/../../../../data/' . $this->logo_getUploadDir();
    }

    /**
     * @return string
     */
    public function logo_getWebPath()
    {
        return $this->logo_getUploadDir() . '/' . $this->getId() . '_logo' . '.'.$this->getLogoUrl();
    }
}

<?php

namespace blackLabel\CommentaireBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

use blackLabel\GenericBundle\Entity\Log;

/**
 * Commentaire_lot
 *
 * @ORM\Table(name="commentaire_lot", indexes={
 *      @ORM\Index(name="lot_idx", columns={"lot_id"}),
 *      @ORM\Index(name="historique_idx", columns={"historique_id"})
 * })
 * @ORM\Entity(repositoryClass="blackLabel\CommentaireBundle\Repository\Commentaire_lotRepository")
 */
class Commentaire_lot extends Log
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
     * @ORM\Column(name="historique_id", type="integer")
     */
    private $historiqueId;

    /**
     * @var int
     *
     * @ORM\Column(name="lot_id", type="integer")
     */
    private $lotId;

    /**
     * @var string
     *
     * @ORM\Column(name="content", type="text")
     * @Assert\Length(max = 245)
     */
    private $content;



    /**
     * Commentaire_lot constructor.
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
     * Set historiqueId
     *
     * @param integer $historiqueId
     *
     * @return Commentaire_lot
     */
    public function setHistoriqueId($historiqueId)
    {
        $this->historiqueId = $historiqueId;

        return $this;
    }

    /**
     * Get historiqueId
     *
     * @return integer
     */
    public function getHistoriqueId()
    {
        return $this->historiqueId;
    }

    /**
     * Set lotId
     *
     * @param integer $lotId
     *
     * @return Commentaire_lot
     */
    public function setLotId($lotId)
    {
        $this->lotId = $lotId;

        return $this;
    }

    /**
     * Get lotId
     *
     * @return integer
     */
    public function getLotId()
    {
        return $this->lotId;
    }

    /**
     * Set content
     *
     * @param string $content
     *
     * @return Commentaire_lot
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content
     *
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }
}

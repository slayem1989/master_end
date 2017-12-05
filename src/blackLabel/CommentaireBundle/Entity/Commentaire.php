<?php

namespace blackLabel\CommentaireBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

use blackLabel\GenericBundle\Entity\Log;

/**
 * Commentaire
 *
 * @ORM\Table(name="commentaire")
 * @ORM\Entity(repositoryClass="blackLabel\CommentaireBundle\Repository\CommentaireRepository")
 */
class Commentaire extends Log
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
     * @ORM\Column(name="lot_id", type="integer")
     */
    private $lot_id;

    /**
     * @var string
     *
     * @ORM\Column(name="content", type="text")
     * @Assert\Length(max = 245)
     */
    private $content;



    /**
     * Commentaire constructor.
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
     * Set lotId
     *
     * @param integer $lotId
     *
     * @return Commentaire
     */
    public function setLotId($lotId)
    {
        $this->lot_id = $lotId;

        return $this;
    }

    /**
     * Get lotId
     *
     * @return integer
     */
    public function getLotId()
    {
        return $this->lot_id;
    }

    /**
     * Set content
     *
     * @param string $content
     *
     * @return Commentaire
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

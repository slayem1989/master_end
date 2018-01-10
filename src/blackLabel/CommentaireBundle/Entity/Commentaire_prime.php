<?php

namespace blackLabel\CommentaireBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

use blackLabel\GenericBundle\Entity\Log;

/**
 * Commentaire_prime
 *
 * @ORM\Table(name="commentaire_prime", indexes={
 *      @ORM\Index(name="prime_idx", columns={"prime_id"}),
 *      @ORM\Index(name="historique_idx", columns={"historique_id"})
 * })
 * @ORM\Entity(repositoryClass="blackLabel\CommentaireBundle\Repository\Commentaire_primeRepository")
 */
class Commentaire_prime extends Log
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
     * @ORM\Column(name="prime_id", type="integer")
     */
    private $primeId;

    /**
     * @var string
     *
     * @ORM\Column(name="content", type="text")
     * @Assert\Length(max = 245)
     */
    private $content;



    /**
     * Commentaire_prime constructor.
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
     * @return Commentaire_prime
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
     * Set primeId
     *
     * @param integer $primeId
     *
     * @return Commentaire_prime
     */
    public function setPrimeId($primeId)
    {
        $this->primeId = $primeId;

        return $this;
    }

    /**
     * Get primeId
     *
     * @return integer
     */
    public function getPrimeId()
    {
        return $this->primeId;
    }

    /**
     * Set content
     *
     * @param string $content
     *
     * @return Commentaire_prime
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

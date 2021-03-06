<?php

namespace blackLabel\HistoriqueBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use blackLabel\GenericBundle\Entity\Log;

/**
 * Historique_prime
 *
 * @ORM\Table(name="historique_prime", indexes={
 *      @ORM\Index(name="prime_idx", columns={"prime_id"}),
 *      @ORM\Index(name="statut_idx", columns={"statut_id"})
 * })
 * @ORM\Entity(repositoryClass="blackLabel\HistoriqueBundle\Repository\Historique_primeRepository")
 */
class Historique_prime extends Log
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
     * @ORM\Column(name="prime_id", type="integer")
     */
    private $primeId;

    /**
     * @var string
     *
     * @ORM\Column(name="action", type="string", length=255)
     */
    private $action;

    /**
     * @var array
     *
     * @ORM\Column(name="content", type="array", nullable=true)
     */
    private $content;

    /**
     * @var int
     *
     * @ORM\Column(name="statut_id", type="integer")
     */
    private $statutId;

    /**
     * @var string
     *
     * @ORM\Column(name="statut_slug", type="string", length=255)
     */
    private $statutSlug;



    /**
     * Historique_prime constructor.
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
     * Set primeId
     *
     * @param integer $primeId
     *
     * @return Historique_prime
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
     * Set action
     *
     * @param string $action
     *
     * @return Historique_prime
     */
    public function setAction($action)
    {
        $this->action = $action;

        return $this;
    }

    /**
     * Get action
     *
     * @return string
     */
    public function getAction()
    {
        return $this->action;
    }

    /**
     * Set content
     *
     * @param array $content
     *
     * @return Historique_prime
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content
     *
     * @return array
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set statutId
     *
     * @param integer $statutId
     *
     * @return Historique_prime
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
     * Set statutSlug
     *
     * @param string $statutSlug
     *
     * @return Historique_prime
     */
    public function setStatutSlug($statutSlug)
    {
        $this->statutSlug = $statutSlug;

        return $this;
    }

    /**
     * Get statutSlug
     *
     * @return string
     */
    public function getStatutSlug()
    {
        return $this->statutSlug;
    }
}

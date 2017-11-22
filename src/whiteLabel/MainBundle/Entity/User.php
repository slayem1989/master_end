<?php

namespace whiteLabel\MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * User
 *
 * @ORM\Table(name="user")
 * @ORM\Entity(repositoryClass="whiteLabel\MainBundle\Repository\UserRepository")
 * 
 * @UniqueEntity(fields={"username"}, message="Le nom d'utilisateur est déjà utilisé.")
 */
class User extends BaseUser
{

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_creation", type="datetime", nullable=true)
     */
    private $dateCreation;

    /**
     * @var string
     *
     * @ORM\Column(name="auteur_creation", type="string", length=255, nullable=true)
     */
    private $auteurCreation;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_modif", type="datetime", nullable=true)
     */
    private $dateModif;

    /**
     * @var string
     *
     * @ORM\Column(name="auteur_modif", type="string", length=255, nullable=true)
     */
    private $auteurModif;

    /**
     * @var string
     *
     * @ORM\Column(name="firstname", type="text", length=255, nullable=true)
     */
    private $firstname;

    /**
     * @var string
     *
     * @ORM\Column(name="lastname", type="text", length=255, nullable=true)
     */
    private $lastname;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_inactif", type="date", nullable=true)
     */
    private $dateInactif;

    /**
     * @var
     *
     * @ORM\ManyToMany(targetEntity="Cluster", inversedBy="users")
     * @ORM\JoinTable(name="users_groups",
     *      joinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="group_id", referencedColumnName="id")}
     * )
     */
    protected $groups;


    
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->groups = new ArrayCollection();
        $this->roles = array('ROLE_MEMBER');

        $this->dateCreation = new \Datetime();
        $this->dateModif    = new \Datetime();
        $this->dateInactif  = new \Datetime();

        if (array_key_exists('login', $_SESSION) && $_SESSION['login']) {
            $this->auteurCreation = $_SESSION['login']->getUsername();
            $this->auteurModif    = $_SESSION['login']->getUsername();
        } else {
            $this->auteurCreation = "Bénéficiaire";
            $this->auteurModif    = "Bénéficiaire";
        }
    }



    /**
     * @param $id
     * @return $this
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
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
     * @param $group
     * @return $this
     */
    public function addGoup($group)
    {
        $this->groups[] = $group;
        $group->setUser($this);

        return $this;
    }

    /**
     * @param $groups
     */
    public function setGroups($groups)
    {
        $this->groups->clear();
        foreach ($groups as $group) {
            $this->addGroup($group);
        }
    }

    /**
     * @return ArrayCollection
     */
    public function getGroups()
    {
        return $this->groups;
    }

    /**
     * Set firstname
     *
     * @param string $firstname
     *
     * @return User
     */
    public function setFirstname($firstname)
    {
        $this->firstname = $firstname;

        return $this;
    }

    /**
     * Get firstname
     *
     * @return string
     */
    public function getFirstname()
    {
        return $this->firstname;
    }

    /**
     * Set lastname
     *
     * @param string $lastname
     *
     * @return User
     */
    public function setLastname($lastname)
    {
        $this->lastname = $lastname;

        return $this;
    }

    /**
     * Get lastname
     *
     * @return string
     */
    public function getLastname()
    {
        return $this->lastname;
    }

    /**
     * Set dateCreation
     *
     * @param \DateTime $dateCreation
     *
     * @return User
     */
    public function setDateCreation($dateCreation)
    {
        $this->dateCreation = $dateCreation;

        return $this;
    }

    /**
     * Get dateCreation
     *
     * @return \DateTime
     */
    public function getDateCreation()
    {
        return $this->dateCreation;
    }

    /**
     * Set auteurCreation
     *
     * @param string $auteurCreation
     *
     * @return User
     */
    public function setAuteurCreation($auteurCreation)
    {
        $this->auteurCreation = $auteurCreation;

        return $this;
    }

    /**
     * Get auteurCreation
     *
     * @return string
     */
    public function getAuteurCreation()
    {
        return $this->auteurCreation;
    }

    /**
     * Set dateModif
     *
     * @param \DateTime $dateModif
     *
     * @return User
     */
    public function setDateModif($dateModif)
    {
        $this->dateModif = $dateModif;

        return $this;
    }

    /**
     * Get dateModif
     *
     * @return \DateTime
     */
    public function getDateModif()
    {
        return $this->dateModif;
    }

    /**
     * Set auteurModif
     *
     * @param string $auteurModif
     *
     * @return User
     */
    public function setAuteurModif($auteurModif)
    {
        $this->auteurModif = $auteurModif;

        return $this;
    }

    /**
     * Get auteurModif
     *
     * @return string
     */
    public function getAuteurModif()
    {
        return $this->auteurModif;
    }

    /**
     * Set dateInactif
     *
     * @param \DateTime $dateInactif
     *
     * @return User
     */
    public function setDateInactif($dateInactif)
    {
        $this->dateInactif = $dateInactif;

        return $this;
    }

    /**
     * Get dateInactif
     *
     * @return \DateTime
     */
    public function getDateInactif()
    {
        return $this->dateInactif;
    }
}

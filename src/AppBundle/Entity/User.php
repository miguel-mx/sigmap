<?php
// src/AppBundle/Entity/User.php

namespace AppBundle\Entity;

use FOS\UserBundle\Entity\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Table(name="fos_user")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\UserRepository")
 *
 */
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=80)
     * @Assert\NotBlank(message="Please enter your name.", groups={"Registration", "Profile"})
     */
    protected $name;

    /**
     * @var string
     *
     * @ORM\Column(name="surname", type="string", length=80)
     * @Assert\NotBlank(message="Please enter your name.", groups={"Registration", "Profile"})
     */
    protected $surname;

    /**
     * @var string
     *
     * @ORM\Column(name="gender", type="string", length=8)
     * @Assert\NotBlank()
     */
    protected $gender;

    /**
     * @var string
     *
     * @ORM\Column(name="citizenship", type="string", length=120)
     *
     */
    protected $citizenship;

    /**
     * @var string
     *
     * @ORM\Column(name="affiliation", type="string", length=150)
     */
    protected $affiliation;

    /**
     * @var boolean
     *
     * @ORM\Column(name="student", type="boolean")
     */
    protected $student;

    /**
     * @var date
     *
     * @ORM\Column(name="arrival", type="date", nullable=true)
     */
    protected $arrival;


    /**
     * @var date
     *
     * @ORM\Column(name="departure", type="date", nullable=true)
     */
    protected $departure;

    /**
     * @var date
     *
     * @ORM\Column(name="createdAt", type="date")
     */
    protected $createdAt;

    /**
     * @var date
     *
     * @ORM\Column(name="modifiedAt", type="date")
     */
    protected $modifiedAt;

    /**
     * @var text
     *
     * @ORM\Column(name="diet", type="text", nullable=true)
     */
    protected $diet;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=150, nullable=true)
     */
    protected $title;

    /**
     * @var text
     *
     * @ORM\Column(name="abstract", type="text", nullable=true)
     */
    protected $abstract;

    /**
     * @var boolean
     *
     * @ORM\Column(name="accepted", type="boolean", nullable=true)
     */
    protected $accepted;

    /**
     * @var boolean
     *
     * @ORM\Column(name="poster", type="boolean", nullable=true)
     */
    protected $poster;

    /**
     * @Gedmo\Slug(fields={"name", "surname"}, updatable=false)
     * @ORM\Column(length=255, unique=true)
     */
    protected $slug;

    /**
     * One User has One Support Application.
     * @ORM\OneToOne(targetEntity="Support", mappedBy="user")
     */
    private $support;

    /**
     * One User has One Payment.
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\Prometeo", mappedBy="user")
     */
    private $payment;

    /**
     * Dinner
     * @ORM\Column(name="dinner", type="integer", nullable=true)
     */
    protected $dinner;

    /**
     * Morelia Trip
     * @ORM\Column(name="morelia", type="integer", nullable=true)
     */
    protected $morelia;

    /**
     * Pátzcuaro trip
     * @ORM\Column(name="patzcuaro", type="integer", nullable=true)
     */
    protected $patzcuaro;


    public function __construct()
    {
        parent::__construct();
        // your own logic
    }

    /**
     * Set name
     *
     * @param string $name
     * @return User
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set surname
     *
     * @param string $surname
     * @return User
     */
    public function setSurname($surname)
    {
        $this->surname = $surname;

        return $this;
    }

    /**
     * Get surname
     *
     * @return string 
     */
    public function getSurname()
    {
        return $this->surname;
    }

    /**
     * Set gender
     *
     * @param string $gender
     * @return User
     */
    public function setGender($gender)
    {
        $this->gender = $gender;

        return $this;
    }

    /**
     * Get gender
     *
     * @return string 
     */
    public function getGender()
    {
        return $this->gender;
    }

    /**
     * Set citizenship
     *
     * @param string $citizenship
     * @return User
     */
    public function setCitizenship($citizenship)
    {
        $this->citizenship = $citizenship;

        return $this;
    }

    /**
     * Get citizenship
     *
     * @return string 
     */
    public function getCitizenship()
    {
        return $this->citizenship;
    }

    /**
     * Set affiliation
     *
     * @param string $affiliation
     * @return User
     */
    public function setAffiliation($affiliation)
    {
        $this->affiliation = $affiliation;

        return $this;
    }

    /**
     * Get affiliation
     *
     * @return string 
     */
    public function getAffiliation()
    {
        return $this->affiliation;
    }

    /**
     * Set arrival
     *
     * @param \DateTime $arrival
     * @return User
     */
    public function setArrival($arrival)
    {
        $this->arrival = $arrival;

        return $this;
    }

    /**
     * Get arrival
     *
     * @return \DateTime 
     */
    public function getArrival()
    {
        return $this->arrival;
    }

    /**
     * Set departure
     *
     * @param \DateTime $departure
     * @return User
     */
    public function setDeparture($departure)
    {
        $this->departure = $departure;

        return $this;
    }

    /**
     * Get departure
     *
     * @return \DateTime 
     */
    public function getDeparture()
    {
        return $this->departure;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return User
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set modifiedAt
     *
     * @param \DateTime $modifiedAt
     * @return User
     */
    public function setModifiedAt($modifiedAt)
    {
        $this->modifiedAt = $modifiedAt;

        return $this;
    }

    /**
     * Get modifiedAt
     *
     * @return \DateTime
     */
    public function getModifiedAt()
    {
        return $this->modifiedAt;
    }

    /**
     * Set diet
     *
     * @param string $diet
     * @return User
     */
    public function setDiet($diet)
    {
        $this->diet = $diet;

        return $this;
    }

    /**
     * Get diet
     *
     * @return string 
     */
    public function getDiet()
    {
        return $this->diet;
    }

    /**
     * Set slug
     *
     * @param string $slug
     * @return User
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Get slug
     *
     * @return string 
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * @ORM\PrePersist
     */
    public function setCreatedAtValue()
    {
        $this->createdAt = new \DateTime();
        $this->modifiedAt = new \DateTime();
    }

    /**
     * Set student
     *
     * @param boolean $student
     * @return User
     */
    public function setStudent($student)
    {
        $this->student = $student;

        return $this;
    }

    /**
     * Get student
     *
     * @return boolean 
     */
    public function getStudent()
    {
        return $this->student;
    }

    /**
     * Set title
     *
     * @param string $title
     * @return User
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string 
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set abstract
     *
     * @param string $abstract
     * @return User
     */
    public function setAbstract($abstract)
    {
        $this->abstract = $abstract;

        return $this;
    }

    /**
     * Get abstract
     *
     * @return string 
     */
    public function getAbstract()
    {
        return $this->abstract;
    }

    /**
     * Set accepted
     *
     * @param boolean $accepted
     * @return User
     */
    public function setAccepted($accepted)
    {
        $this->accepted = $accepted;

        return $this;
    }

    /**
     * Get accepted
     *
     * @return boolean 
     */
    public function getAccepted()
    {
        return $this->accepted;
    }

    /**
     * Set poster
     *
     * @param boolean $poster
     * @return User
     */
    public function setPoster($poster)
    {
        $this->poster = $poster;

        return $this;
    }

    /**
     * Get poster
     *
     * @return boolean
     */
    public function getPoster()
    {
        return $this->poster;
    }

    /**
     * Set support
     *
     * @param \AppBundle\Entity\Support $support
     * @return User
     */
    public function setSupport(\AppBundle\Entity\Support $support = null)
    {
        $this->support = $support;

        return $this;
    }

    /**
     * Get support
     *
     * @return \AppBundle\Entity\Support 
     */
    public function getSupport()
    {
        return $this->support;
    }

    /**
     * Set payment
     *
     * @param \AppBundle\Entity\Prometeo $payment
     *
     * @return User
     */
    public function setPayment(\AppBundle\Entity\Prometeo $payment = null)
    {
        $this->payment = $payment;

        return $this;
    }

    /**
     * Get payment
     *
     * @return \AppBundle\Entity\Prometeo
     */
    public function getPayment()
    {
        return $this->payment;
    }

    public function __toString()
    {
        return $this->getName() . ' ' . $this->getSurname();
    }

    /**
     * Set dinner
     *
     * @param integer $dinner
     *
     * @return User
     */
    public function setDinner($dinner)
    {
        $this->dinner = $dinner;

        return $this;
    }

    /**
     * Get dinner
     *
     * @return integer
     */
    public function getDinner()
    {
        return $this->dinner;
    }

    /**
     * Set morelia
     *
     * @param integer $morelia
     *
     * @return User
     */
    public function setMorelia($morelia)
    {
        $this->morelia = $morelia;

        return $this;
    }

    /**
     * Get morelia
     *
     * @return integer
     */
    public function getMorelia()
    {
        return $this->morelia;
    }

    /**
     * Set patzcuaro
     *
     * @param integer $patzcuaro
     *
     * @return User
     */
    public function setPatzcuaro($patzcuaro)
    {
        $this->patzcuaro = $patzcuaro;

        return $this;
    }

    /**
     * Get patzcuaro
     *
     * @return integer
     */
    public function getPatzcuaro()
    {
        return $this->patzcuaro;
    }
}

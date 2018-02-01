<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Support
 *
 * @ORM\Table(name="support")
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Entity(repositoryClass="AppBundle\Repository\SupportRepository")
 */
class Support
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
     * @ORM\Column(name="type", type="string", length=80)
     */
    private $type;

    /**
     * @var string
     *
     * @ORM\Column(name="studies", type="string", length=50)
     */
    private $studies;

    /**
     * @var string
     *
     * @ORM\Column(name="reasons", type="text")
     */
    private $reasons;

    /**
     * @var bool
     *
     * @ORM\Column(name="approved", type="boolean", nullable=true)
     */
    private $approved;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="appliedDate", type="date")
     */
    private $appliedDate;

    /**
     * One Support aplication has One User.
     * @ORM\OneToOne(targetEntity="User", inversedBy="support")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;


    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set type
     *
     * @param string $type
     * @return Support
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string 
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set studies
     *
     * @param string $studies
     * @return Support
     */
    public function setStudies($studies)
    {
        $this->studies = $studies;

        return $this;
    }

    /**
     * Get studies
     *
     * @return string 
     */
    public function getStudies()
    {
        return $this->studies;
    }

    /**
     * Set reasons
     *
     * @param string $reasons
     * @return Support
     */
    public function setReasons($reasons)
    {
        $this->reasons = $reasons;

        return $this;
    }

    /**
     * Get reasons
     *
     * @return string 
     */
    public function getReasons()
    {
        return $this->reasons;
    }

    /**
     * Set approved
     *
     * @param boolean $approved
     * @return Support
     */
    public function setApproved($approved)
    {
        $this->approved = $approved;

        return $this;
    }

    /**
     * Get approved
     *
     * @return boolean 
     */
    public function getApproved()
    {
        return $this->approved;
    }

    /**
     * @ORM\PrePersist
     */
    public function setAppliedAtValue()
    {
        $this->appliedDate = new \DateTime();
    }

    /**
     * Set appliedDate
     *
     * @param \DateTime $appliedDate
     * @return Support
     */
    public function setAppliedDate($appliedDate)
    {
        $this->appliedDate = $appliedDate;

        return $this;
    }

    /**
     * Get appliedDate
     *
     * @return \DateTime 
     */
    public function getAppliedDate()
    {
        return $this->appliedDate;
    }

    /**
     * Set user
     *
     * @param \AppBundle\Entity\User $user
     * @return Support
     */
    public function setUser(\AppBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \AppBundle\Entity\User 
     */
    public function getUser()
    {
        return $this->user;
    }
}

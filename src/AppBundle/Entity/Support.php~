<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Support
 *
 * @ORM\Table(name="support")
 * @ORM\Entity
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

    //  Profesores

    /**
     * @var string
     *
     * @ORM\Column(name="prof1", type="string", length=50)
     */
    private $prof1;

    /**
     * @var string
     *
     * @ORM\Column(name="mailprof1", type="string", length=60)
     * @Assert\Email(
     *     message = "The email '{{ value }}' is not a valid email.",
     *     checkMX = true)
     */
    private $mailprof1;

    /**
     * @var string
     *
     * @ORM\Column(name="prof2", type="string", length=50)
     */
    private $prof2;

    /**
     * @var string
     *
     * @ORM\Column(name="mailprof2", type="string", length=60)
     * @Assert\Email(
     *     message = "The email '{{ value }}' is not a valid email.",
     *     checkMX = true)
     */
    private $mailprof2;

    /**
     * One Support has Many Recomendations.
     * @ORM\OneToMany(targetEntity="Recommendation", mappedBy="support")
     */
    private $recommendations;

    public function __construct() {
        $this->recommendations = new ArrayCollection();
    }

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

    /**
     * Set prof1
     *
     * @param string $prof1
     * @return Support
     */
    public function setProf1($prof1)
    {
        $this->prof1 = $prof1;

        return $this;
    }

    /**
     * Get prof1
     *
     * @return string 
     */
    public function getProf1()
    {
        return $this->prof1;
    }

    /**
     * Set mailprof1
     *
     * @param string $mailprof1
     * @return Support
     */
    public function setMailprof1($mailprof1)
    {
        $this->mailprof1 = $mailprof1;

        return $this;
    }

    /**
     * Get mailprof1
     *
     * @return string 
     */
    public function getMailprof1()
    {
        return $this->mailprof1;
    }

    /**
     * Set prof2
     *
     * @param string $prof2
     * @return Support
     */
    public function setProf2($prof2)
    {
        $this->prof2 = $prof2;

        return $this;
    }

    /**
     * Get prof2
     *
     * @return string 
     */
    public function getProf2()
    {
        return $this->prof2;
    }

    /**
     * Set mailprof2
     *
     * @param string $mailprof2
     * @return Support
     */
    public function setMailprof2($mailprof2)
    {
        $this->mailprof2 = $mailprof2;

        return $this;
    }

    /**
     * Get mailprof2
     *
     * @return string 
     */
    public function getMailprof2()
    {
        return $this->mailprof2;
    }

    /**
     * Add recommendations
     *
     * @param \AppBundle\Entity\Recommendation $recommendations
     * @return Support
     */
    public function addRecommendation(\AppBundle\Entity\Recommendation $recommendations)
    {
        $this->recommendations[] = $recommendations;

        return $this;
    }

    /**
     * Remove recommendations
     *
     * @param \AppBundle\Entity\Recommendation $recommendations
     */
    public function removeRecommendation(\AppBundle\Entity\Recommendation $recommendations)
    {
        $this->recommendations->removeElement($recommendations);
    }

    /**
     * Get recommendations
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getRecommendations()
    {
        return $this->recommendations;
    }

     /**
     * isRecomended
     *
     * @return Recommendation
     */
    public function isRecomended($email)
    {
        foreach($this->recommendations as $r ) {
            if($r->getEmail() == $email)
                return $r;
        }
        return NULL;
    }
}

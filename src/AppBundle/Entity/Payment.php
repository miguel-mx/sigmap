<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Defuse\Crypto\Crypto;

/**
 * Payment
 *
 * @ORM\Table(name="payment")
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Entity(repositoryClass="AppBundle\Repository\PaymentRepository")
 */
class Payment
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
     * @var \DateTime
     *
     * @ORM\Column(name="birthdate", type="date")
     */
    private $birthdate;

    /**
     * @var string
     *
     * @ORM\Column(name="rfc", type="string", length=15)
     */
    private $rfc;

    /**
     * @var string
     *
     * @ORM\Column(name="empresa", type="string", length=12, nullable=true)
     */
    private $empresa;

    /**
     * @var string
     *
     * @ORM\Column(name="calle", type="string", length=50, nullable=false)
     */
    private $calle;

    /**
     * @var string
     *
     * @ORM\Column(name="numexterior", type="string", length=10)
     */
    private $numexterior;

    /**
     * @var string
     *
     * @ORM\Column(name="numinterior", type="string", length=10, nullable=true)
     */
    private $numinterior;

    /**
     * @var string
     *
     * @ORM\Column(name="colonia", type="string", length=60, nullable=false)
     */
    private $colonia;

    /**
     * @var int
     *
     * @ORM\Column(name="cpostal", type="string", length=5)
     * @Assert\Regex(
     *     pattern="/0-9/",
     *     match=false,
     *     message="Solo puede contener números"
     * )
     *
     *
     */
    private $cpostal;

    /**
     * @var string
     *
     * @ORM\Column(name="pais", type="string", length=80, nullable=false)
     */
    private $pais;

    /**
     * @var string
     *
     * @ORM\Column(name="estado", type="string", length=80, nullable=false)
     */
    private $estado;

    /**
     * @var string
     *
     * @ORM\Column(name="delegacion", type="string", length=80, nullable=false)
     */
    private $delegacion;

    /**
     * @var string
     *
     * @ORM\Column(name="telefono", type="string", length=10, nullable=false)
     * @Assert\Regex(
     *     pattern="/0-9/",
     *     match=false,
     *     message="Solo puede contener números"
     * )
     *
     */
    private $telefono;

    /**
     * @var string
     *
     * @ORM\Column(name="idproducto", type="string", length=15)
     */
    private $idproducto;

    /**
     * @var string
     *
     * @ORM\Column(name="isocode", type="string", length=2)
     */
    private $isocode;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="createdAt", type="date", nullable=false)
     */
    private $createdAt;

    /**
     * @var string
     *
     * @ORM\Column(name="response", type="string", length=200)
     */
    private $response;

    /**
     * One Payment has One User.
     * @ORM\OneToOne(targetEntity="User", inversedBy="payment")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;


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
     * Set birthdate
     *
     * @param \DateTime $birthdate
     *
     * @return Payment
     */
    public function setBirthdate($birthdate)
    {
        $this->birthdate = $birthdate;

        return $this;
    }

    /**
     * Get birthdate
     *
     * @return \DateTime
     */
    public function getBirthdate()
    {
        return $this->birthdate;
    }

    /**
     * Set rfc
     *
     * @param string $rfc
     *
     * @return Payment
     */
    public function setRfc($rfc)
    {
        $this->rfc = $rfc;

        return $this;
    }

    /**
     * Get rfc
     *
     * @return string
     */
    public function getRfc()
    {
        return $this->rfc;
    }

    /**
     * Set empresa
     *
     * @param string $empresa
     *
     * @return Payment
     */
    public function setEmpresa($empresa)
    {
        $this->empresa = $empresa;

        return $this;
    }

    /**
     * Get empresa
     *
     * @return string
     */
    public function getEmpresa()
    {
        return $this->empresa;
    }

    /**
     * Set calle
     *
     * @param string $calle
     *
     * @return Payment
     */
    public function setCalle($calle)
    {
        $this->calle = $calle;

        return $this;
    }

    /**
     * Get calle
     *
     * @return string
     */
    public function getCalle()
    {
        return $this->calle;
    }

    /**
     * Set numexterior
     *
     * @param string $numexterior
     *
     * @return Payment
     */
    public function setNumexterior($numexterior)
    {
        $this->numexterior = $numexterior;

        return $this;
    }

    /**
     * Get numexterior
     *
     * @return string
     */
    public function getNumexterior()
    {
        return $this->numexterior;
    }

    /**
     * Set numinterior
     *
     * @param string $numinterior
     *
     * @return Payment
     */
    public function setNuminterior($numinterior)
    {
        $this->numinterior = $numinterior;

        return $this;
    }

    /**
     * Get numinterior
     *
     * @return string
     */
    public function getNuminterior()
    {
        return $this->numinterior;
    }

    /**
     * Set colonia
     *
     * @param string $colonia
     *
     * @return Payment
     */
    public function setColonia($colonia)
    {
        $this->colonia = $colonia;

        return $this;
    }

    /**
     * Get colonia
     *
     * @return string
     */
    public function getColonia()
    {
        return $this->colonia;
    }

    /**
     * Set cpostal
     *
     * @param string $cpostal
     *
     * @return Payment
     */
    public function setCpostal($cpostal)
    {
        $this->cpostal = $cpostal;

        return $this;
    }

    /**
     * Get cpostal
     *
     * @return string
     */
    public function getCpostal()
    {
        return $this->cpostal;
    }

    /**
     * Set pais
     *
     * @param string $pais
     *
     * @return Payment
     */
    public function setPais($pais)
    {
        $this->pais = $pais;

        return $this;
    }

    /**
     * Get pais
     *
     * @return string
     */
    public function getPais()
    {
        return $this->pais;
    }

    /**
     * Set estado
     *
     * @param string $estado
     *
     * @return Payment
     */
    public function setEstado($estado)
    {
        $this->estado = $estado;

        return $this;
    }

    /**
     * Get estado
     *
     * @return string
     */
    public function getEstado()
    {
        return $this->estado;
    }

    /**
     * Set delegacion
     *
     * @param string $delegacion
     *
     * @return Payment
     */
    public function setDelegacion($delegacion)
    {
        $this->delegacion = $delegacion;

        return $this;
    }

    /**
     * Get delegacion
     *
     * @return string
     */
    public function getDelegacion()
    {
        return $this->delegacion;
    }

    /**
     * Set telefono
     *
     * @param string $telefono
     *
     * @return Payment
     */
    public function setTelefono($telefono)
    {
        $this->telefono = $telefono;

        return $this;
    }

    /**
     * Get telefono
     *
     * @return string
     */
    public function getTelefono()
    {
        return $this->telefono;
    }

    /**
     * Set idproducto
     *
     * @param string $idproducto
     *
     * @return Payment
     */
    public function setIdproducto($idproducto)
    {
        $this->idproducto = $idproducto;

        return $this;
    }

    /**
     * Get idproducto
     *
     * @return string
     */
    public function getIdproducto()
    {
        return $this->idproducto;
    }

    /**
     * Set isocode
     *
     * @param string $isocode
     *
     * @return Payment
     */
    public function setIsocode($isocode)
    {
        $this->isocode = $isocode;

        return $this;
    }

    /**
     * Get isocode
     *
     * @return string
     */
    public function getIsocode()
    {
        return $this->isocode;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return Payment
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
     * Set response
     *
     * @param string $response
     *
     * @return Payment
     */
    public function setResponse($response)
    {
        $this->response = $response;

        return $this;
    }

    /**
     * Get response
     *
     * @return string
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * Set user
     *
     * @param \AppBundle\Entity\User $user
     *
     * @return Payment
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
     * @ORM\PrePersist
     */
    public function setCreatedAtValue()
    {
        $this->createdAt = new \DateTime();
    }

}

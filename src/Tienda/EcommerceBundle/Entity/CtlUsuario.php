<?php

namespace Tienda\EcommerceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CtlUsuario
 *
 * @ORM\Table(name="ctl_usuario", indexes={@ORM\Index(name="fk_ctl_usuario_persona1_idx", columns={"persona_id"})})
 * @ORM\Entity
 */
class CtlUsuario
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="username", type="string", length=255, nullable=true)
     */
    private $username;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=255, nullable=true)
     */
    private $password;

    /**
     * @var string
     *
     * @ORM\Column(name="salt", type="string", length=255, nullable=true)
     */
    private $salt;

    /**
     * @var integer
     *
     * @ORM\Column(name="estado", type="integer", nullable=true)
     */
    private $estado;

    /**
     * @var \Persona
     *
     * @ORM\ManyToOne(targetEntity="Persona")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="persona_id", referencedColumnName="id")
     * })
     */
    private $persona;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="CtlRol", inversedBy="ctlUsuario")
     * @ORM\JoinTable(name="rol_usuario",
     *   joinColumns={
     *     @ORM\JoinColumn(name="ctl_usuario_id", referencedColumnName="id")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="ctl_rol_id", referencedColumnName="id")
     *   }
     * )
     */
    private $ctlRol;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->ctlRol = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set username
     *
     * @param string $username
     * @return CtlUsuario
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Get username
     *
     * @return string 
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Set password
     *
     * @param string $password
     * @return CtlUsuario
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get password
     *
     * @return string 
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set salt
     *
     * @param string $salt
     * @return CtlUsuario
     */
    public function setSalt($salt)
    {
        $this->salt = $salt;

        return $this;
    }

    /**
     * Get salt
     *
     * @return string 
     */
    public function getSalt()
    {
        return $this->salt;
    }

    /**
     * Set estado
     *
     * @param integer $estado
     * @return CtlUsuario
     */
    public function setEstado($estado)
    {
        $this->estado = $estado;

        return $this;
    }

    /**
     * Get estado
     *
     * @return integer 
     */
    public function getEstado()
    {
        return $this->estado;
    }

    /**
     * Set persona
     *
     * @param \Tienda\EcommerceBundle\Entity\Persona $persona
     * @return CtlUsuario
     */
    public function setPersona(\Tienda\EcommerceBundle\Entity\Persona $persona = null)
    {
        $this->persona = $persona;

        return $this;
    }

    /**
     * Get persona
     *
     * @return \Tienda\EcommerceBundle\Entity\Persona 
     */
    public function getPersona()
    {
        return $this->persona;
    }

    /**
     * Add ctlRol
     *
     * @param \Tienda\EcommerceBundle\Entity\CtlRol $ctlRol
     * @return CtlUsuario
     */
    public function addCtlRol(\Tienda\EcommerceBundle\Entity\CtlRol $ctlRol)
    {
        $this->ctlRol[] = $ctlRol;

        return $this;
    }

    /**
     * Remove ctlRol
     *
     * @param \Tienda\EcommerceBundle\Entity\CtlRol $ctlRol
     */
    public function removeCtlRol(\Tienda\EcommerceBundle\Entity\CtlRol $ctlRol)
    {
        $this->ctlRol->removeElement($ctlRol);
    }

    /**
     * Get ctlRol
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getCtlRol()
    {
        return $this->ctlRol;
    }
}

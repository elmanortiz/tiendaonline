<?php

namespace Tienda\EcommerceBundle\Entity;

use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\AdvancedUserInterface;
use Doctrine\ORM\Mapping as ORM;

/**
 * CtlUsuario
 *
 * @ORM\Table(name="ctl_usuario", indexes={@ORM\Index(name="fk_ctl_usuario_persona1_idx", columns={"persona_id"})})
 * @ORM\Entity
 */
class CtlUsuario implements AdvancedUserInterface, \Serializable
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
     * @ORM\Column(name="username", type="string", length=255, nullable=false)
     */
    private $username;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=255, nullable=false)
     */
    private $password;

    /**
     * @var string
     *
     * @ORM\Column(name="salt", type="string", length=255, nullable=false)
     */
    private $salt;
        
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="ultimointento", type="datetime", nullable=true)
     */
    private $ultimointento;
    
    /**
     * @var integer
     *
     * @ORM\Column(name="intentos", type="integer", nullable=true)
     */
    private $intentos;
    
    /**
     * @var boolean
     *
     * @ORM\Column(name="estado", type="boolean", nullable=false)
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
    
    private $isEnabled; // = false; 
            
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
     * Set ultimointento
     *
     * @param \DateTime $ultimointento
     * @return CtlUsuario
     */
    public function setUltimointento($ultimointento)
    {
        $this->ultimointento = $ultimointento;

        return $this;
    }

    /**
     * Get ultimointento
     *
     * @return \DateTime 
     */
    public function getUltimointento()
    {
        return $this->ultimointento;
    }

    /**
     * Set intentos
     *
     * @param integer $intentos
     * @return CtlUsuario
     */
    public function setIntentos($intentos)
    {
        $this->intentos = $intentos;

        return $this;
    }

    /**
     * Get intentos
     *
     * @return integer 
     */
    public function getIntentos()
    {
        return $this->intentos;
    }
    
    /**
     * Set estado
     *
     * @param boolean $estado
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
     * @return boolean 
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
    
    /**
     * Get roles
     *
     * @return Doctrine\Common\Collections\Collection
     */
    public function getRoles()
    {
        return $this->ctlRol->toArray(); //IMPORTANTE: el mecanismo de seguridad de Sf2 requiere ésto como un array
    }        
    
     /**
     * Compares this user to another to determine if they are the same.
     *
     * @param UserInterface $user The user
     * @return boolean True if equal, false othwerwise.
     */
    public function equals(UserInterface $user) {
        return md5($this->getUsername()) == md5($user->getUsername());
 
    }
 
    /**
     * Erases the user credentials.
     */
    public function eraseCredentials() {
 
    }    
    
    /**
     * @see \Serializable::serialize()
     */
    public function serialize()
    {
        return serialize(array(
            $this->id,
        ));
    }
    
    /**
     * @see \Serializable::unserialize()
     */
    public function unserialize($serialized)
    {
        list (
            $this->id,
            ) = unserialize($serialized);
    }
    
    public function isAccountNonExpired()
    {
        return true;
    }

    public function isAccountNonLocked()
    {
        return  !$this->isEnabled;
    }

    public function isCredentialsNonExpired()
    {
        return true;
    }

    public function isEnabled()
    {
        if ((int)$this->estado == 1)
            $this->isEnabled = true;
        else
            $this->isEnabled  = false;
        return  $this->isEnabled;
    }
    
    public function __toString() {
        return $this->username ? $this->username : '';
    }            
}
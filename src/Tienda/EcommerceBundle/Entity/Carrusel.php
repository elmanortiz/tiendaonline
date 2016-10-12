<?php

namespace Tienda\EcommerceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Carrusel
 *
 * @ORM\Table(name="carrusel")
 * @ORM\Entity
 */
class Carrusel
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
     * @ORM\Column(name="nombre", type="string", length=255, nullable=true)
     */
    private $nombre;

    /**
     * @var boolean
     *
     * @ORM\Column(name="estado", type="boolean", nullable=true)
     */
    private $estado;
    
    /**
     * @var boolean
     *
     * @ORM\Column(name="tipoimagen", type="boolean", nullable=true)
     */
    private $tipoimagen;



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
     * Set nombre
     *
     * @param string $nombre
     * @return Carrusel
     */
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;

        return $this;
    }

    /**
     * Get nombre
     *
     * @return string 
     */
    public function getNombre()
    {
        return $this->nombre;
    }

    /**
     * Set estado
     *
     * @param boolean $estado
     * @return Carrusel
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
     * Set estado
     *
     * @param boolean $estado
     * @return Carrusel
     */
    public function setTipoImagen($tipoimagen)
    {
        $this->tipoimagen= $tipoimagen;

        return $this;
    }

    /**
     * Get estado
     *
     * @return boolean 
     */
    public function getTipoImagen()
    {
        return $this->tipoimagen;
    }
}

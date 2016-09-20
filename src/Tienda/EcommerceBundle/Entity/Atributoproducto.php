<?php

namespace Tienda\EcommerceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Atributoproducto
 *
 * @ORM\Table(name="atributoproducto", indexes={@ORM\Index(name="fk_atributoproducto_producto1_idx", columns={"producto_id"})})
 * @ORM\Entity
 */
class Atributoproducto
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
     * @ORM\Column(name="nombre", type="string", length=100, nullable=true)
     */
    private $nombre;

    /**
     * @var integer
     *
     * @ORM\Column(name="porcentaje", type="integer", nullable=true)
     */
    private $porcentaje;

    /**
     * @var \Producto
     *
     * @ORM\ManyToOne(targetEntity="Producto")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="producto_id", referencedColumnName="id")
     * })
     */
    private $producto;



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
     * @return Atributoproducto
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
     * Set porcentaje
     *
     * @param integer $porcentaje
     * @return Atributoproducto
     */
    public function setPorcentaje($porcentaje)
    {
        $this->porcentaje = $porcentaje;

        return $this;
    }

    /**
     * Get porcentaje
     *
     * @return integer 
     */
    public function getPorcentaje()
    {
        return $this->porcentaje;
    }

    /**
     * Set producto
     *
     * @param \Tienda\EcommerceBundle\Entity\Producto $producto
     * @return Atributoproducto
     */
    public function setProducto(\Tienda\EcommerceBundle\Entity\Producto $producto = null)
    {
        $this->producto = $producto;

        return $this;
    }

    /**
     * Get producto
     *
     * @return \Tienda\EcommerceBundle\Entity\Producto 
     */
    public function getProducto()
    {
        return $this->producto;
    }
}

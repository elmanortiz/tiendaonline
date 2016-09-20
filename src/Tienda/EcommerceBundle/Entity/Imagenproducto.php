<?php

namespace Tienda\EcommerceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Imagenproducto
 *
 * @ORM\Table(name="imagenproducto", indexes={@ORM\Index(name="fk_imagenproducto_producto1_idx", columns={"producto_id"})})
 * @ORM\Entity
 */
class Imagenproducto
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
     * @ORM\Column(name="imagen1", type="string", length=255, nullable=true)
     */
    private $imagen1;

    /**
     * @var string
     *
     * @ORM\Column(name="imagen2", type="string", length=255, nullable=true)
     */
    private $imagen2;

    /**
     * @var string
     *
     * @ORM\Column(name="imagen3", type="string", length=255, nullable=true)
     */
    private $imagen3;

    /**
     * @var string
     *
     * @ORM\Column(name="imagen4", type="string", length=255, nullable=true)
     */
    private $imagen4;

    /**
     * @var string
     *
     * @ORM\Column(name="imagen5", type="string", length=255, nullable=true)
     */
    private $imagen5;

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
     * Set imagen1
     *
     * @param string $imagen1
     * @return Imagenproducto
     */
    public function setImagen1($imagen1)
    {
        $this->imagen1 = $imagen1;

        return $this;
    }

    /**
     * Get imagen1
     *
     * @return string 
     */
    public function getImagen1()
    {
        return $this->imagen1;
    }

    /**
     * Set imagen2
     *
     * @param string $imagen2
     * @return Imagenproducto
     */
    public function setImagen2($imagen2)
    {
        $this->imagen2 = $imagen2;

        return $this;
    }

    /**
     * Get imagen2
     *
     * @return string 
     */
    public function getImagen2()
    {
        return $this->imagen2;
    }

    /**
     * Set imagen3
     *
     * @param string $imagen3
     * @return Imagenproducto
     */
    public function setImagen3($imagen3)
    {
        $this->imagen3 = $imagen3;

        return $this;
    }

    /**
     * Get imagen3
     *
     * @return string 
     */
    public function getImagen3()
    {
        return $this->imagen3;
    }

    /**
     * Set imagen4
     *
     * @param string $imagen4
     * @return Imagenproducto
     */
    public function setImagen4($imagen4)
    {
        $this->imagen4 = $imagen4;

        return $this;
    }

    /**
     * Get imagen4
     *
     * @return string 
     */
    public function getImagen4()
    {
        return $this->imagen4;
    }

    /**
     * Set imagen5
     *
     * @param string $imagen5
     * @return Imagenproducto
     */
    public function setImagen5($imagen5)
    {
        $this->imagen5 = $imagen5;

        return $this;
    }

    /**
     * Get imagen5
     *
     * @return string 
     */
    public function getImagen5()
    {
        return $this->imagen5;
    }

    /**
     * Set producto
     *
     * @param \Tienda\EcommerceBundle\Entity\Producto $producto
     * @return Imagenproducto
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

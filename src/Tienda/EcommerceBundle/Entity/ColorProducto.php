<?php

namespace Tienda\EcommerceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ColorProducto
 *
 * @ORM\Table(name="color_producto", indexes={@ORM\Index(name="fk_color_producto_color1_idx", columns={"color_id"})}),@ORM\Table(name="color_producto", indexes={@ORM\Index(name=" fk_color_producto_producto1_idx", columns={"producto_id"})})
 * @ORM\Entity
 */
class ColorProducto
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
     * @var \Color
     *
     * @ORM\ManyToOne(targetEntity="Color")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="color_id", referencedColumnName="id")
     * })
     */
    private $color_id;
    
    /**
     * @var \Producto
     *
     * @ORM\ManyToOne(targetEntity="Producto")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="producto_id", referencedColumnName="id")
     * })
     */
    private $producto_id;

    
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
     * Set color
     *
     * @param \Tienda\EcommerceBundle\Entity\Color $color_id
     * @return Color
     */
    public function setColor(\Tienda\EcommerceBundle\Entity\Color $color_id = null)
    {
        $this->color_id = $color_id;

        return $this;
    }
    
    /**
     * Get color
     *
     * @return \Tienda\EcommerceBundle\Entity\Color 
     */
    public function getColor()
    {
        return $this->color_id;
    }
        
    /**
     * Set producto
     *
     * @param \Tienda\EcommerceBundle\Entity\Producto $producto
     * @return Producto
     */
    public function setProducto(\Tienda\EcommerceBundle\Entity\Producto $producto_id = null)
    {
        $this->producto_id = $producto_id;

        return $this;
    }
    
    /**
     * Get producto
     *
     * @return \Tienda\EcommerceBundle\Entity\Producto 
     */
    public function getProducto()
    {
        return $this->producto_id;
    }
}

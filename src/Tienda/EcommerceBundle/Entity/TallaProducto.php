<?php

namespace Tienda\EcommerceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TallaProducto
 *
 * @ORM\Table(name="talla_producto", indexes={@ORM\Index(name="fk_talla_producto_talla1_idx", columns={"talla_id"})}),@ORM\Table(name="talla_producto", indexes={@ORM\Index(name="fk_talla_producto_producto1_idx", columns={"producto_id"})})
 * @ORM\Entity
 */
class TallaProducto
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
     * @var integer
     *
     * @ORM\Column(name="stock", type="integer", nullable=true)
     */
    private $stock;

    /**
     * @var \Talla
     *
     * @ORM\ManyToOne(targetEntity="Talla")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="talla_id", referencedColumnName="id")
     * })
     */
    private $talla_id;
    
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
     * Set stock
     *
     * @param integer $stock
     * @return Producto
     */
    public function setStock($stock)
    {
        $this->stock = $stock;

        return $this;
    }

    /**
     * Get stock
     *
     * @return integer 
     */
    public function getStock()
    {
        return $this->stock;
    }

    /**
     * Set talla
     *
     * @param \Tienda\EcommerceBundle\Entity\Talla $talla_id
     * @return Talla
     */
    public function setTalla(\Tienda\EcommerceBundle\Entity\Talla $talla_id = null)
    {
        $this->talla_id = $talla_id;

        return $this;
    }
    
    /**
     * Get talla
     *
     * @return \Tienda\EcommerceBundle\Entity\Talla 
     */
    public function getTalla()
    {
        return $this->talla_id;
    }
        
    /**
     * Set producto
     *
     * @param \Tienda\EcommerceBundle\Entity\Producto $producto_id
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

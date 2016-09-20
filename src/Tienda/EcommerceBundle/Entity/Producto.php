<?php

namespace Tienda\EcommerceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Producto
 *
 * @ORM\Table(name="producto", indexes={@ORM\Index(name="fk_producto_categoria_idx", columns={"categoria_id"}), @ORM\Index(name="fk_producto_color1_idx", columns={"color_id"}), @ORM\Index(name="fk_producto_talla1_idx", columns={"talla_id"})})
 * @ORM\Entity
 */
class Producto
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
     * @ORM\Column(name="nombre", type="string", length=200, nullable=true)
     */
    private $nombre;

    /**
     * @var string
     *
     * @ORM\Column(name="precio", type="decimal", precision=15, scale=2, nullable=true)
     */
    private $precio;

    /**
     * @var string
     *
     * @ORM\Column(name="numeroReferencia", type="string", length=30, nullable=true)
     */
    private $numeroreferencia;

    /**
     * @var boolean
     *
     * @ORM\Column(name="estado", type="boolean", nullable=true)
     */
    private $estado;

    /**
     * @var string
     *
     * @ORM\Column(name="descripcion", type="string", length=600, nullable=true)
     */
    private $descripcion;

    /**
     * @var string
     *
     * @ORM\Column(name="link", type="string", length=200, nullable=true)
     */
    private $link;

    /**
     * @var string
     *
     * @ORM\Column(name="ingredientes", type="string", length=500, nullable=true)
     */
    private $ingredientes;

    /**
     * @var string
     *
     * @ORM\Column(name="presentacion", type="string", length=150, nullable=true)
     */
    private $presentacion;

    /**
     * @var integer
     *
     * @ORM\Column(name="stock", type="integer", nullable=true)
     */
    private $stock;

    /**
     * @var \Categoria
     *
     * @ORM\ManyToOne(targetEntity="Categoria")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="categoria_id", referencedColumnName="id")
     * })
     */
    private $categoria;

    /**
     * @var \Color
     *
     * @ORM\ManyToOne(targetEntity="Color")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="color_id", referencedColumnName="id")
     * })
     */
    private $color;

    /**
     * @var \Talla
     *
     * @ORM\ManyToOne(targetEntity="Talla")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="talla_id", referencedColumnName="id")
     * })
     */
    private $talla;



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
     * @return Producto
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
     * Set precio
     *
     * @param string $precio
     * @return Producto
     */
    public function setPrecio($precio)
    {
        $this->precio = $precio;

        return $this;
    }

    /**
     * Get precio
     *
     * @return string 
     */
    public function getPrecio()
    {
        return $this->precio;
    }

    /**
     * Set numeroreferencia
     *
     * @param string $numeroreferencia
     * @return Producto
     */
    public function setNumeroreferencia($numeroreferencia)
    {
        $this->numeroreferencia = $numeroreferencia;

        return $this;
    }

    /**
     * Get numeroreferencia
     *
     * @return string 
     */
    public function getNumeroreferencia()
    {
        return $this->numeroreferencia;
    }

    /**
     * Set estado
     *
     * @param boolean $estado
     * @return Producto
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
     * Set descripcion
     *
     * @param string $descripcion
     * @return Producto
     */
    public function setDescripcion($descripcion)
    {
        $this->descripcion = $descripcion;

        return $this;
    }

    /**
     * Get descripcion
     *
     * @return string 
     */
    public function getDescripcion()
    {
        return $this->descripcion;
    }

    /**
     * Set link
     *
     * @param string $link
     * @return Producto
     */
    public function setLink($link)
    {
        $this->link = $link;

        return $this;
    }

    /**
     * Get link
     *
     * @return string 
     */
    public function getLink()
    {
        return $this->link;
    }

    /**
     * Set ingredientes
     *
     * @param string $ingredientes
     * @return Producto
     */
    public function setIngredientes($ingredientes)
    {
        $this->ingredientes = $ingredientes;

        return $this;
    }

    /**
     * Get ingredientes
     *
     * @return string 
     */
    public function getIngredientes()
    {
        return $this->ingredientes;
    }

    /**
     * Set presentacion
     *
     * @param string $presentacion
     * @return Producto
     */
    public function setPresentacion($presentacion)
    {
        $this->presentacion = $presentacion;

        return $this;
    }

    /**
     * Get presentacion
     *
     * @return string 
     */
    public function getPresentacion()
    {
        return $this->presentacion;
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
     * Set categoria
     *
     * @param \Tienda\EcommerceBundle\Entity\Categoria $categoria
     * @return Producto
     */
    public function setCategoria(\Tienda\EcommerceBundle\Entity\Categoria $categoria = null)
    {
        $this->categoria = $categoria;

        return $this;
    }

    /**
     * Get categoria
     *
     * @return \Tienda\EcommerceBundle\Entity\Categoria 
     */
    public function getCategoria()
    {
        return $this->categoria;
    }

    /**
     * Set color
     *
     * @param \Tienda\EcommerceBundle\Entity\Color $color
     * @return Producto
     */
    public function setColor(\Tienda\EcommerceBundle\Entity\Color $color = null)
    {
        $this->color = $color;

        return $this;
    }

    /**
     * Get color
     *
     * @return \Tienda\EcommerceBundle\Entity\Color 
     */
    public function getColor()
    {
        return $this->color;
    }

    /**
     * Set talla
     *
     * @param \Tienda\EcommerceBundle\Entity\Talla $talla
     * @return Producto
     */
    public function setTalla(\Tienda\EcommerceBundle\Entity\Talla $talla = null)
    {
        $this->talla = $talla;

        return $this;
    }

    /**
     * Get talla
     *
     * @return \Tienda\EcommerceBundle\Entity\Talla 
     */
    public function getTalla()
    {
        return $this->talla;
    }
}

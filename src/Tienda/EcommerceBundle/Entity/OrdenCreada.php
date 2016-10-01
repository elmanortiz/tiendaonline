<?php

namespace Tienda\EcommerceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * OrdenCreada
 *
 * @ORM\Table(name="orden_creada", indexes={@ORM\Index(name="fk_orden_creada_estado_paquete1_idx", columns={"estado_paquete_id"}), @ORM\Index(name="fk_orden_creada_cliente1_idx", columns={"cliente_id"}), @ORM\Index(name="fk_orden_creada_municipio1_idx", columns={"municipio_id"})})
 * @ORM\Entity
 */
class OrdenCreada
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="bigint", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var integer
     *
     * @ORM\Column(name="cookie", type="bigint", nullable=true)
     */
    private $cookie;
    
    /**
     * @var integer
     *
     * @ORM\Column(name="id_venta", type="bigint", nullable=true)
     */
    private $idVenta;

    /**
     * @var string
     *
     * @ORM\Column(name="nombre_prod", type="string", length=500, nullable=true)
     */
    private $nombreProd;

    /**
     * @var string
     *
     * @ORM\Column(name="precio", type="decimal", precision=15, scale=2, nullable=true)
     */
    private $precio;

    /**
     * @var integer
     *
     * @ORM\Column(name="cantidad", type="integer", nullable=true)
     */
    private $cantidad;
    
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha_registro", type="datetime", nullable=false)
     */
    private $fechaRegistro;

    /**
     * @var string
     *
     * @ORM\Column(name="direccion", type="string", length=200, nullable=false)
     */
    private $direccion;

    /**
     * @var string
     *
     * @ORM\Column(name="numero_referencia", type="string", length=30, nullable=true)
     */
    private $numeroReferencia;
    
    /**
     * @var string
     *
     * @ORM\Column(name="talla", type="string", length=50, nullable=true)
     */
    private $talla;

    /**
     * @var string
     *
     * @ORM\Column(name="color", type="string", length=30, nullable=true)
     */
    private $color;

    /**
     * @var string
     *
     * @ORM\Column(name="imagen", type="string", length=255, nullable=true)
     */
    private $imagen;

    /**
     * @var string
     *
     * @ORM\Column(name="estado", type="string", length=45, nullable=true)
     */
    private $estado;

    /**
     * @var \Cliente
     *
     * @ORM\ManyToOne(targetEntity="Cliente")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="cliente_id", referencedColumnName="id")
     * })
     */
    private $cliente;

    /**
     * @var \EstadoPaquete
     *
     * @ORM\ManyToOne(targetEntity="EstadoPaquete")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="estado_paquete_id", referencedColumnName="id")
     * })
     */
    private $estadoPaquete;

    /**
     * @var \Municipio
     *
     * @ORM\ManyToOne(targetEntity="Municipio")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="municipio_id", referencedColumnName="id")
     * })
     */
    private $municipio;
    
    /**
     * @var \Producto
     *
     * @ORM\ManyToOne(targetEntity="Producto")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="producto", referencedColumnName="id")
     * })
     */
    private $producto;
    
    /**
     * @var \TipoOrden
     *
     * @ORM\ManyToOne(targetEntity="TipoOrden")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="tipo_orden", referencedColumnName="id")
     * })
     */
    private $tipoOrden;
    
    /**
     * @var \Shipping
     *
     * @ORM\ManyToOne(targetEntity="Shipping")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="shipping", referencedColumnName="id")
     * })
     */
    private $shipping;   



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
     * Set cookie
     *
     * @param integer $cookie
     * @return OrdenCreada
     */
    public function setCookie($cookie)
    {
        $this->cookie = $cookie;

        return $this;
    }

    /**
     * Get cookie
     *
     * @return integer 
     */
    public function getCookie()
    {
        return $this->cookie;
    }

    /**
     * Set idVenta
     *
     * @param integer $idVenta
     * @return OrdenCreada
     */
    public function setIdVenta($idVenta)
    {
        $this->idVenta = $idVenta;

        return $this;
    }

    /**
     * Get idVenta
     *
     * @return integer 
     */
    public function getIdVenta()
    {
        return $this->idVenta;
    }    
    
    /**
     * Set nombreProd
     *
     * @param string $nombreProd
     * @return OrdenCreada
     */
    public function setNombreProd($nombreProd)
    {
        $this->nombreProd = $nombreProd;

        return $this;
    }
        
    /**
     * Get nombreProd
     *
     * @return string 
     */
    public function getNombreProd()
    {
        return $this->nombreProd;
    }

    /**
     * Set precio
     *
     * @param string $precio
     * @return OrdenCreada
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
     * Set cantidad
     *
     * @param integer $cantidad
     * @return OrdenCreada
     */
    public function setCantidad($cantidad)
    {
        $this->cantidad = $cantidad;

        return $this;
    }

    /**
     * Get cantidad
     *
     * @return integer 
     */
    public function getCantidad()
    {
        return $this->cantidad;
    }
    
    /**
     * Set fechaRegistro
     *
     * @param \DateTime $fechaRegistro
     * @return OrdenCreada
     */
    public function setFechaRegistro($fechaRegistro)
    {
        $this->fechaRegistro = $fechaRegistro;

        return $this;
    }

    /**
     * Get fechaRegistro
     *
     * @return \DateTime 
     */
    public function getFechaRegistro()
    {
        return $this->fechaRegistro;
    }

    /**
     * Set direccion
     *
     * @param string $direccion
     * @return OrdenCreada
     */
    public function setDireccion($direccion)
    {
        $this->direccion = $direccion;

        return $this;
    }

    /**
     * Get direccion
     *
     * @return string 
     */
    public function getDireccion()
    {
        return $this->direccion;
    }

    /**
     * Set numeroReferencia
     *
     * @param string $numeroReferencia
     * @return OrdenCreada  
     */
    public function setNumeroReferencia($numeroReferencia)
    {
        $this->numeroReferencia = $numeroReferencia;

        return $this;
    }

    /**
     * Get numeroReferencia
     *
     * @return string 
     */
    public function getNumeroReferencia()
    {
        return $this->numeroReferencia;
    }

    /**
     * Set imagen
     *
     * @param string $imagen
     * @return OrdenCreada
     */
    public function setImagen($imagen)
    {
        $this->imagen = $imagen;

        return $this;
    }

    /**
     * Get imagen
     *
     * @return string 
     */
    public function getImagen()
    {
        return $this->imagen;
    }

    /**
     * Set estado
     *
     * @param string $estado
     * @return OrdenCreada
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
     * Set talla
     *
     * @param string $talla
     * @return OrdenCreada
     */
    public function setTalla($talla)
    {
        $this->talla = $talla;

        return $this;
    }

    /**
     * Get talla
     *
     * @return string 
     */
    public function getTalla()
    {
        return $this->talla;
    }

    /**
     * Set color
     *
     * @param string $color
     * @return OrdenCreada  
     */
    public function setColor($color)
    {
        $this->color = $color;

        return $this;
    }

    /**
     * Get numeroReferencia
     *
     * @return string 
     */
    public function getColor()
    {
        return $this->color;
    }

    /**
     * Set cliente
     *
     * @param \Tienda\EcommerceBundle\Entity\Cliente $cliente
     * @return OrdenCreada
     */
    public function setCliente(\Tienda\EcommerceBundle\Entity\Cliente $cliente = null)
    {
        $this->cliente = $cliente;

        return $this;
    }

    /**
     * Get cliente
     *
     * @return \Tienda\EcommerceBundle\Entity\Cliente 
     */
    public function getCliente()
    {
        return $this->cliente;
    }

    /**
     * Set estadoPaquete
     *
     * @param \Tienda\EcommerceBundle\Entity\EstadoPaquete $estadoPaquete
     * @return OrdenCreada
     */
    public function setEstadoPaquete(\Tienda\EcommerceBundle\Entity\EstadoPaquete $estadoPaquete = null)
    {
        $this->estadoPaquete = $estadoPaquete;

        return $this;
    }

    /**
     * Get estadoPaquete
     *
     * @return \Tienda\EcommerceBundle\Entity\EstadoPaquete 
     */
    public function getEstadoPaquete()
    {
        return $this->estadoPaquete;
    }

    /**
     * Set municipio
     *
     * @param \Tienda\EcommerceBundle\Entity\Municipio $municipio
     * @return OrdenCreada
     */
    public function setMunicipio(\Tienda\EcommerceBundle\Entity\Municipio $municipio = null)
    {
        $this->municipio = $municipio;

        return $this;
    }

    /**
     * Get municipio
     *
     * @return \Tienda\EcommerceBundle\Entity\Municipio 
     */
    public function getMunicipio()
    {
        return $this->municipio;
    }
    
    /**
     * Set producto
     *
     * @param \Tienda\EcommerceBundle\Entity\Producto $producto
     * @return OrdenCreada
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
    
    /**
     * Set tipoOrden
     *
     * @param \Tienda\EcommerceBundle\Entity\TipoOrden $tipoOrden
     * @return OrdenCreada
     */
    public function setTipoOrden(\Tienda\EcommerceBundle\Entity\TipoOrden $tipoOrden = null)
    {
        $this->tipoOrden = $tipoOrden;

        return $this;
    }

    /**
     * Get tipoOrden
     *
     * @return \Tienda\EcommerceBundle\Entity\TipoOrden 
     */
    public function getTipoOrden()
    {
        return $this->tipoOrden;
    }
    
    /**
     * Set shipping
     *
     * @param \Tienda\EcommerceBundle\Entity\Producto $shipping
     * @return OrdenCreada
     */
    public function setShipping(\Tienda\EcommerceBundle\Entity\Producto $shipping = null)
    {
        $this->shipping = $shipping;

        return $this;
    }

    /**
     * Get shipping
     *
     * @return \Tienda\EcommerceBundle\Entity\Shipping 
     */
    public function getShipping()
    {
        return $this->shipping;
    }
}

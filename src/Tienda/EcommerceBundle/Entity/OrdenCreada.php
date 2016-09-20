<?php

namespace Tienda\EcommerceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * OrdenCreada
 *
 * @ORM\Table(name="orden_creada", indexes={@ORM\Index(name="fk_orden_creada_departamento1_idx", columns={"departamento_id"}), @ORM\Index(name="fk_orden_creada_estado_paquete1_idx", columns={"estado_paquete_id"})})
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
     * @var string
     *
     * @ORM\Column(name="numero_referencia", type="string", length=30, nullable=true)
     */
    private $numeroReferencia;

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
     * @var \Departamento
     *
     * @ORM\ManyToOne(targetEntity="Departamento")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="departamento_id", referencedColumnName="id")
     * })
     */
    private $departamento;

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
     * Set departamento
     *
     * @param \Tienda\EcommerceBundle\Entity\Departamento $departamento
     * @return OrdenCreada
     */
    public function setDepartamento(\Tienda\EcommerceBundle\Entity\Departamento $departamento = null)
    {
        $this->departamento = $departamento;

        return $this;
    }

    /**
     * Get departamento
     *
     * @return \Tienda\EcommerceBundle\Entity\Departamento 
     */
    public function getDepartamento()
    {
        return $this->departamento;
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
}

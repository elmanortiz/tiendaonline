<?php

namespace Tienda\EcommerceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Orden
 *
 * @ORM\Table(name="orden")
 * @ORM\Entity
 */
class Orden
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
     * @var integer
     *
     * @ORM\Column(name="estado", type="integer", nullable=true)
     */
    private $estado;



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
     * @return Orden
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
     * @return Orden
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
     * @return Orden
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
     * @return Orden
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
     * @return Orden
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
     * @return Orden
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
     * @param integer $estado
     * @return Orden
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
}

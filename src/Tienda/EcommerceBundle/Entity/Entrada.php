<?php

namespace Tienda\EcommerceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Entrada
 *
 * @ORM\Table(name="entrada", indexes={@ORM\Index(name="fk_entrada_categoriablog1_idx", columns={"categoriablog_id"})})
 * @ORM\Entity
 */
class Entrada
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
     * @ORM\Column(name="escritopor", type="string", length=100, nullable=true)
     */
    private $escritopor;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha", type="date", nullable=true)
     */
    private $fecha;

    /**
     * @var string
     *
     * @ORM\Column(name="contenido", type="text", length=65535, nullable=true)
     */
    private $contenido;

    /**
     * @var \Categoriablog
     *
     * @ORM\ManyToOne(targetEntity="Categoriablog")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="categoriablog_id", referencedColumnName="id")
     * })
     */
    private $categoriablog;



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
     * @return Entrada
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
     * Set escritopor
     *
     * @param string $escritopor
     * @return Entrada
     */
    public function setEscritopor($escritopor)
    {
        $this->escritopor = $escritopor;

        return $this;
    }

    /**
     * Get escritopor
     *
     * @return string 
     */
    public function getEscritopor()
    {
        return $this->escritopor;
    }

    /**
     * Set fecha
     *
     * @param \DateTime $fecha
     * @return Entrada
     */
    public function setFecha($fecha)
    {
        $this->fecha = $fecha;

        return $this;
    }

    /**
     * Get fecha
     *
     * @return \DateTime 
     */
    public function getFecha()
    {
        return $this->fecha;
    }

    /**
     * Set contenido
     *
     * @param string $contenido
     * @return Entrada
     */
    public function setContenido($contenido)
    {
        $this->contenido = $contenido;

        return $this;
    }

    /**
     * Get contenido
     *
     * @return string 
     */
    public function getContenido()
    {
        return $this->contenido;
    }

    /**
     * Set categoriablog
     *
     * @param \Tienda\EcommerceBundle\Entity\Categoriablog $categoriablog
     * @return Entrada
     */
    public function setCategoriablog(\Tienda\EcommerceBundle\Entity\Categoriablog $categoriablog = null)
    {
        $this->categoriablog = $categoriablog;

        return $this;
    }

    /**
     * Get categoriablog
     *
     * @return \Tienda\EcommerceBundle\Entity\Categoriablog 
     */
    public function getCategoriablog()
    {
        return $this->categoriablog;
    }
}

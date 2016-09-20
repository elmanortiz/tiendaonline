<?php

namespace Tienda\EcommerceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Imagenblog
 *
 * @ORM\Table(name="imagenblog", indexes={@ORM\Index(name="fk_imagenblog_entrada1_idx", columns={"entrada_id"})})
 * @ORM\Entity
 */
class Imagenblog
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
     * @var \Entrada
     *
     * @ORM\ManyToOne(targetEntity="Entrada")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="entrada_id", referencedColumnName="id")
     * })
     */
    private $entrada;



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
     * @return Imagenblog
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
     * Set entrada
     *
     * @param \Tienda\EcommerceBundle\Entity\Entrada $entrada
     * @return Imagenblog
     */
    public function setEntrada(\Tienda\EcommerceBundle\Entity\Entrada $entrada = null)
    {
        $this->entrada = $entrada;

        return $this;
    }

    /**
     * Get entrada
     *
     * @return \Tienda\EcommerceBundle\Entity\Entrada 
     */
    public function getEntrada()
    {
        return $this->entrada;
    }
}

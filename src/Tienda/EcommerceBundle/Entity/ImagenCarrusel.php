<?php

namespace Tienda\EcommerceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ImagenCarrusel
 *
 * @ORM\Table(name="imagen_carrusel", indexes={@ORM\Index(name="fk_imagen_carrusel_carrusel1_idx", columns={"carrusel_id"})})
 * @ORM\Entity
 */
class ImagenCarrusel
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
     * @ORM\Column(name="imagen", type="string", length=255, nullable=true)
     */
    private $imagen;

    /**
     * @var \Carrusel
     *
     * @ORM\ManyToOne(targetEntity="Carrusel")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="carrusel_id", referencedColumnName="id")
     * })
     */
    private $carrusel;



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
     * Set imagen
     *
     * @param string $imagen
     * @return ImagenCarrusel
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
     * Set carrusel
     *
     * @param \Tienda\EcommerceBundle\Entity\Carrusel $carrusel
     * @return ImagenCarrusel
     */
    public function setCarrusel(\Tienda\EcommerceBundle\Entity\Carrusel $carrusel = null)
    {
        $this->carrusel = $carrusel;

        return $this;
    }

    /**
     * Get carrusel
     *
     * @return \Tienda\EcommerceBundle\Entity\Carrusel 
     */
    public function getCarrusel()
    {
        return $this->carrusel;
    }
}

<?php

namespace Tienda\EcommerceBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductoType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nombre')
            ->add('precio')
            ->add('numeroreferencia')
            ->add('estado')
            ->add('descripcion')
            ->add('link')
            ->add('ingredientes')
            ->add('presentacion')
            ->add('stock')
            ->add('categoria')
            ->add('color')
            ->add('talla')
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Tienda\EcommerceBundle\Entity\Producto'
        ));
    }
}

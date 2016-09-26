<?php

namespace Tienda\EcommerceBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OrdenCreadaType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('cookie')
            ->add('nombreProd')
            ->add('precio')
            ->add('cantidad')
            ->add('direccion')
            ->add('numeroReferencia')
            ->add('imagen')
            ->add('estado')
            ->add('cliente')
            ->add('estadoPaquete')
            ->add('municipio')
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Tienda\EcommerceBundle\Entity\OrdenCreada'
        ));
    }
}

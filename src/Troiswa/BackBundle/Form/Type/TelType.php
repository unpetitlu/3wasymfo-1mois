<?php
namespace Troiswa\BackBundle\Form\Type;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TelType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'attr' => array('class' => 'phonenumber') // On ajoute la classe
        ));
    }


    public function getName()
    {
        return 'tel';
    }

    public function getParent()
    {
        return 'text';
    }
}
<?php

namespace Troiswa\BackBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Troiswa\BackBundle\Form\DataTransformer\CouponToUserCouponTransformer;
use Troiswa\BackBundle\Repository\UserCouponRepository;

class UserEditType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $transformer = new CouponToUserCouponTransformer();


        $builder
            ->add('firstname')
            ->add('lastname')
            ->add('email')
            ->add('birthday')
            ->add('phone')
            ->add('login')
            ->add('address')
            ->add('sexe', 'gender')
            ->add('groups', 'entity',
                [
                    'class' => 'TroiswaBackBundle:Group',
                    'property' => 'name',
                    'multiple' => true
                ]);

        $builder->add(
            $builder->create('usercoupon', 'entity',
                [
                    'class' => 'TroiswaBackBundle:UserCoupon',
                    'query_builder' => function(UserCouponRepository $er) {
                        return $er->getALlCouponFormType();
                    },
                    'required' => false,
                    'multiple' => true,
                    'by_reference' => false
                ])
                ->addModelTransformer($transformer)
        );
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Troiswa\BackBundle\Entity\User'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'troiswa_backbundle_user_edit';
    }
}

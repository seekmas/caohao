<?php

namespace Bear\AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class InfoType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('date' , null , ['label' => '预约时间' , 'attr' => ['class' => 'date']])
            ->add('time' , 'choice' , [
                'choices' =>
                    [
                        '9:00-9:45' => '9:00-9:45',
                        '9:45-10:30' => '9:45-10:30',
                        '10:30-11:15' => '10:30-11:15',
                        '11:15-12:00' => '11:15-12:00',
                        '13:00-13:45' => '13:00-13:45',
                        '13:45-14:30' => '13:45-14:30',
                        '14:30-15:15' => '14:30-15:15',
                        '15:15-16:00' => '15:15-16:00',
                        '16:00-16:45' => '16:00-16:45',
                        '16:45-17:30' => '16:45-17:30',
                        '17:30-18:00' => '17:30-18:00' ,
                    ],
            ])
            ->add('name')
            ->add('gender' , 'choice' , [
                'choices' => array(0 => '女', 1 => '男'),
            ])
            ->add('age')
            ->add('phone')
            ->add('email')
        ;

    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Bear\AppBundle\Entity\Info'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'bear_app_bundle_info';
    }
}

<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class JobType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('company')
            ->add('type', ChoiceType::class, array(
                'choices'  => array(
                    'Full-time' => 'Full-time',
                    'Part-time' => 'Part-time',
                    'Freelance' => 'Freelance',
                ),
                // *this line is important*
                'choices_as_values' => true,
            ))
            ->add('url')
            ->add('position')
            ->add('location')
            ->add('jobDescription', TextareaType::class)
            ->add('howToApply', TextareaType::class)
            ->add('email')
            ->add('category');
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Job'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_job';
    }


}

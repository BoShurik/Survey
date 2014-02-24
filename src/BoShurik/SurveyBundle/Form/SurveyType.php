<?php

namespace BoShurik\SurveyBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class SurveyType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('questions', 'collection', array(
                'type'         => new QuestionType(),
                'allow_add'    => true,
                'allow_delete' => true
            ))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'BoShurik\SurveyBundle\Entity\Survey',
        ));
    }

    public function getName()
    {
        return 'survey';
    }
}

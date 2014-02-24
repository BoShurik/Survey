<?php

namespace BoShurik\SurveyBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class QuestionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('expanded', null, array(
                'required' => false
            ))
            ->add('multiple', null, array(
                'required' => false
            ))
            ->add('choices', 'collection', array(
                'type'         => new ChoiceType(),
                'allow_add'    => true,
                'allow_delete' => true,
                'by_reference' => false
            ))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'BoShurik\SurveyBundle\Entity\Question',
        ));
    }

    public function getName()
    {
        return 'question';
    }
}

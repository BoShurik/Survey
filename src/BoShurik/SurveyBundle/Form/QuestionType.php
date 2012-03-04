<?php

namespace BoShurik\SurveyBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

use BoShurik\SurveyBundle\Form\ChoiceType;

class QuestionType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
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
                'allow_delete' => true
            ))
        ;
    }

    public function getDefaultOptions(array $options)
    {
        return array(
            'data_class' => 'BoShurik\SurveyBundle\Entity\Question',
        );
    }

    public function getName()
    {
        return 'question_type';
    }
}

<?php

namespace BoShurik\SurveyBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

use BoShurik\SurveyBundle\Form\QuestionType;

class SurveyType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
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

    public function getDefaultOptions(array $options)
    {
        return array(
            'data_class' => 'BoShurik\SurveyBundle\Entity\Survey',
        );
    }

    public function getName()
    {
        return 'survey_type';
    }
}

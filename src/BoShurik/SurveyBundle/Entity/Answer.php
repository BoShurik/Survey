<?php

namespace BoShurik\SurveyBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * BoShurik\SurveyBundle\Entity\Answer
 *
 * @ORM\Table(name="answer")
 * @ORM\Entity(repositoryClass="BoShurik\SurveyBundle\Repository\AnswerRepository")
 */
class Answer
{
    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;


    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }
}
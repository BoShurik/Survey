<?php

namespace BoShurik\SurveyBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * BoShurik\SurveyBundle\Entity\Choice
 *
 * @ORM\Table(name="choice")
 * @ORM\Entity(repositoryClass="BoShurik\SurveyBundle\Repository\ChoiceRepository")
 */
class Choice
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
     * @var string $name
     *
     * @ORM\Column(name="name", type="string", length="255")
     */
    private $name;

    /**
     * @var Question $question
     *
     * @ORM\ManyToOne(targetEntity="Question", inversedBy="choices")
     * @ORM\JoinColumn(name="question", referencedColumnName="id", onDelete="cascade", onUpdate="cascade")
     */
    private $question;


    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param \BoShurik\SurveyBundle\Entity\Question $question
     */
    public function setQuestion($question)
    {
        $this->question = $question;
    }

    /**
     * @return \BoShurik\SurveyBundle\Entity\Question
     */
    public function getQuestion()
    {
        return $this->question;
    }
}
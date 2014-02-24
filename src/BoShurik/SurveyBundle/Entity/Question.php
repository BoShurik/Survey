<?php

namespace BoShurik\SurveyBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * BoShurik\SurveyBundle\Entity\Question
 *
 * @ORM\Table(name="question")
 * @ORM\Entity(repositoryClass="BoShurik\SurveyBundle\Repository\QuestionRepository")
 */
class Question
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
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var boolean $expanded
     *
     * @ORM\Column(name="expanded", type="boolean")
     */
    private $expanded;

    /**
     * @var boolean $multiple
     *
     * @ORM\Column(name="multiple", type="boolean")
     */
    private $multiple;

    /**
     * @var Survey $survey
     *
     * @ORM\ManyToOne(targetEntity="Survey", inversedBy="questions")
     * @ORM\JoinColumn(name="survey", referencedColumnName="id", onDelete="cascade")
     */
    private $survey;

    /**
     * @var \Doctrine\Common\Collections\ArrayCollection $choices
     *
     * @ORM\OneToMany(targetEntity="Choice", mappedBy="question", cascade={"all"}, orphanRemoval=true)
     */
    private $choices;

    public function __construct()
    {
        $this->expanded = false;
        $this->multiple = false;

        $this->choices = new ArrayCollection();
    }


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
     * @param boolean $expanded
     */
    public function setExpanded($expanded)
    {
        $this->expanded = $expanded;
    }

    /**
     * @return boolean
     */
    public function getExpanded()
    {
        return $this->expanded;
    }

    /**
     * @param boolean $multiple
     */
    public function setMultiple($multiple)
    {
        $this->multiple = $multiple;
    }

    /**
     * @return boolean
     */
    public function getMultiple()
    {
        return $this->multiple;
    }

    /**
     * @param \BoShurik\SurveyBundle\Entity\Survey $survey
     */
    public function setSurvey($survey)
    {
        $this->survey = $survey;
    }

    /**
     * @return \BoShurik\SurveyBundle\Entity\Survey
     */
    public function getSurvey()
    {
        return $this->survey;
    }

    /**
     * @param \Doctrine\Common\Collections\ArrayCollection $choices
     */
    public function setChoices($choices)
    {
        $this->choices = $choices;
    }

    /**
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getChoices()
    {
        return $this->choices;
    }

    public function addChoice(Choice $choice)
    {
        $choice->setQuestion($this);
        $this->choices->add($choice);
    }

    public function removeChoice(Choice $choice)
    {
        $this->choices->removeElement($choice);
    }
}
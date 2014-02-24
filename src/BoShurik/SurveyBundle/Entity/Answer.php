<?php

namespace BoShurik\SurveyBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use BoShurik\UserBundle\Entity\User;
use Doctrine\Common\Collections\ArrayCollection;

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
     * @var \BoShurik\UserBundle\Entity\User $user
     *
     * @ORM\ManyToOne(targetEntity="BoShurik\UserBundle\Entity\User")
     * @ORM\JoinColumn(name="user", referencedColumnName="id", onDelete="cascade")
     */
    private $user;

    /**
     * @var Survey $survey
     *
     * @ORM\ManyToOne(targetEntity="Survey")
     * @ORM\JoinColumn(name="survey", referencedColumnName="id", onDelete="cascade")
     */
    private $survey;

    /**
     * @var \Doctrine\Common\Collections\ArrayCollection $choices
     *
     * @ORM\ManyToMany(targetEntity="Choice")
     * @ORM\JoinTable(name="answer_choice",
     *      joinColumns={@ORM\JoinColumn(name="answer", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="choice", referencedColumnName="id")})
     */
    private $choices;

    public function __construct(User $user, Survey $survey)
    {
        $this->user = $user;
        $this->survey = $survey;

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
     * @param \BoShurik\UserBundle\Entity\User $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }

    /**
     * @return \BoShurik\UserBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
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
        $this->choices->add($choice);
    }
}
<?php

namespace BoShurik\SurveyBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * BoShurik\SurveyBundle\Entity\Choice
 *
 * @ORM\Table()
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
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }
}
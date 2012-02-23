<?php

namespace BoShurik\SurveyBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;


class SiteController extends Controller
{
    
    public function indexAction()
    {
        return $this->render('BoShurikSurveyBundle:Site:index.html.twig');
    }
}

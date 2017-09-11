<?php

namespace AppBundle\Controller;

use AppBundle\Helpers\JobRenderer;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class CategoryController extends Controller
{
    /**
     * @Route("/category/{name}")
     */
    public function getAction($name)
    {
        $jobs = $this->getDoctrine()->getRepository('AppBundle:Job')->getByCategoryName($name);
        $renderer = new JobRenderer($jobs);
        return $this->render('AppBundle:Category:get.html.twig', array(
            'jobs' => $renderer->render()
        ));
    }

}

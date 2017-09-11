<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;

class InformationController extends Controller
{
    /**
     * @Route("/information/{slug}")
     */
    public function getAction($slug)
    {
        $information = $this->getDoctrine()->getRepository('information.php')->findOneBy([
            'slug' => $slug
        ]);
        return $this->render('AppBundle:Information:get.html.twig', array(
            'information' => $information
        ));
    }

}

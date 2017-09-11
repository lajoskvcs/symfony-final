<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class MenuController extends Controller
{
    public function generateAction($place)
    {
        $menu = $this->getDoctrine()->getRepository('AppBundle:Menu')->getMenuByPlace($place);
        return $this->render('AppBundle:Menu:generate.html.twig', array(
            'menu' => $menu
        ));
    }

}

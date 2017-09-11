<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Job;
use AppBundle\Helpers\JobRenderer;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class JobController extends Controller
{

    /**
     * @Route("/")
     */
    public function indexAction()
    {
        $jobs = $this->getDoctrine()->getRepository('AppBundle:Job')->getLatestJobs(10);
        $renderer = new JobRenderer($jobs);
        return $this->render('AppBundle:Job:index.html.twig', array(
            'jobs' => $renderer->render()
        ));
    }

    /**
     * @Route("/search")
     */
    public function searchAction(Request $request)
    {
        if ($request->getMethod() == 'POST') {
            $values = $request->request->get('keywords');
            $jobs = $this->getDoctrine()->getRepository('AppBundle:Job')->searchFor($values);
            $renderer = new JobRenderer($jobs);
            return $this->render('AppBundle:Job:search.html.twig', array(
                "search_for" => $values,
                "jobs" => $renderer->render()
            ));
        }
        return new RedirectResponse('/');
    }

    /**
     * @Route("/job/post")
     * @Method({"GET", "POST"})
     */
    public function postAction(Request $request)
    {
        $auth_checker = $this->get('security.authorization_checker');
        $isGranted = $auth_checker->isGranted('IS_AUTHENTICATED_REMEMBERED');
        if (!$isGranted) {
            return $this->redirectToRoute('fos_user_security_login');
        }
        $job = new Job();
        $form = $this->createForm('AppBundle\Form\JobType', $job);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($job);
            $em->flush();
            return $this->redirectToRoute('app_job_get', array('id' => $job->getId()));
        }
        return $this->render('AppBundle:Job:new.html.twig', array(
            'job' => $job,
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/job/{id}")
     */
    public function getAction($id)
    {
        $job = $this->getDoctrine()->getRepository('AppBundle:Job')->find($id);
        return $this->render('AppBundle:Job:get.html.twig', array(
            "job" => $job
        ));
    }
}

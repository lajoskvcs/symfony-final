<?php

namespace AdminBundle\Controller;

use AppBundle\Entity\Information;
use AppBundle\Entity\Job;
use AppBundle\Entity\Menu;
use AppBundle\Helpers\JobRenderer;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

class JobController extends Controller
{
    /**
     * @Route("/admin/job/")
     */
    public function indexAction()
    {
        $jobs = $this->getDoctrine()->getRepository('AppBundle:Job')->getAll();
        $renderer = new JobRenderer($jobs);
        return $this->render('AdminBundle:job:index.html.twig', array(
            'jobs' => $renderer->render()
        ));
    }

    /**
     *
     * @param Request $request
     *
     * @return array
     *
     * @Route("/admin/job/new")
     * @Method({"GET", "POST"})
     * @Template()
     */
    public function newAction(Request $request)
    {
        $job = new Menu();
        $form = $this->createForm('AppBundle\Form\JobType', $job);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($job);
            $em->flush();
            return $this->redirectToRoute('admin_job_index');
        }
        return array(
            'menu' => $job,
            'form' => $form->createView(),
        );
    }

    /**
     * @Route("/admin/job/{id}/edit")
     * @Method({"GET", "POST"})
     * @Template()
     */
    public function editAction(Request $request, Job $job)
    {
        $deleteForm = $this->createDeleteForm($job);
        $editForm = $this->createForm('AppBundle\Form\JobType', $job);
        $editForm->handleRequest($request);
        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($job);
            $em->flush();
            return $this->redirectToRoute('admin_job_edit', array('id' => $job->getId()));
        }
        return array(
            'job' => $job,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a Post entity.
     *
     * @param Request $request
     * @param menu    $menu
     *
     * @return RedirectResponse
     *
     * @Route("/admin/job/{id}")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Job $job)
    {
        $form = $this->createDeleteForm($job);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($job);
            $em->flush();
        }
        return $this->redirectToRoute('admin_job_index');
    }
    /**
     * Creates a form to delete a menu entity.
     *
     * @param Job $job
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Job $job)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_job_delete', array('id' => $job->getId())))
            ->setMethod('DELETE')
            ->getForm();
    }
}

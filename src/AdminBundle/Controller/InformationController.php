<?php

namespace AdminBundle\Controller;

use AppBundle\Entity\Information;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

class InformationController extends Controller
{
    /**
     * @Route("/admin/information/")
     */
    public function indexAction()
    {
        $categories = $this->getDoctrine()->getRepository('AppBundle:Information')->findAll();
        return $this->render('AdminBundle:information:index.html.twig', array(
            'categories' => $categories
        ));
    }

    /**
     * Creates a new Post entity.
     *
     * @param Request $request
     *
     * @return array
     *
     * @Route("/admin/information/new")
     * @Method({"GET", "POST"})
     * @Template()
     */
    public function newAction(Request $request)
    {
        $information = new information();
        $form = $this->createForm('AppBundle\Form\InformationType', $information);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($information);
            $em->flush();
            return $this->redirectToRoute('admin_information_index');
        }
        return array(
            'information' => $information,
            'form' => $form->createView(),
        );
    }

    /**
     * @Route("/admin/information/{id}/edit")
     * @Method({"GET", "POST"})
     * @Template()
     */
    public function editAction(Request $request, information $information)
    {
        $deleteForm = $this->createDeleteForm($information);
        $editForm = $this->createForm('AppBundle\Form\InformationType', $information);
        $editForm->handleRequest($request);
        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($information);
            $em->flush();
            return $this->redirectToRoute('admin_information_edit', array('id' => $information->getId()));
        }
        return array(
            'information' => $information,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a Post entity.
     *
     * @param Request $request
     * @param information    $information
     *
     * @return RedirectResponse
     *
     * @Route("/admin/information/{id}")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, information $information)
    {
        $form = $this->createDeleteForm($information);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($information);
            $em->flush();
        }
        return $this->redirectToRoute('admin_information_index');
    }
    /**
     * Creates a form to delete a information entity.
     *
     * @param information $information
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(information $information)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_information_delete', array('id' => $information->getId())))
            ->setMethod('DELETE')
            ->getForm();
    }
}

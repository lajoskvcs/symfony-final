<?php

namespace AdminBundle\Controller;

use AppBundle\Entity\Information;
use AppBundle\Entity\Menu;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

class MenuController extends Controller
{
    /**
     * @Route("/admin/menu/")
     */
    public function indexAction()
    {
        $footer = $this->getDoctrine()->getRepository('AppBundle:Menu')->getMenuByPlace("footer");
        $header = $this->getDoctrine()->getRepository('AppBundle:Menu')->getMenuByPlace("header");
        return $this->render('AdminBundle:menu:index.html.twig', array(
            'header' => $header,
            'footer' => $footer
        ));
    }

    /**
     * Creates a new Post entity.
     *
     * @param Request $request
     *
     * @return array
     *
     * @Route("/admin/menu/new")
     * @Method({"GET", "POST"})
     * @Template()
     */
    public function newAction(Request $request)
    {
        $menu = new Menu();
        $form = $this->createForm('AppBundle\Form\MenuType', $menu);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($menu);
            $em->flush();
            return $this->redirectToRoute('admin_menu_index');
        }
        return array(
            'menu' => $menu,
            'form' => $form->createView(),
        );
    }

    /**
     * @Route("/admin/menu/{id}/edit")
     * @Method({"GET", "POST"})
     * @Template()
     */
    public function editAction(Request $request, menu $menu)
    {
        $deleteForm = $this->createDeleteForm($menu);
        $editForm = $this->createForm('AppBundle\Form\MenuType', $menu);
        $editForm->handleRequest($request);
        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($menu);
            $em->flush();
            return $this->redirectToRoute('admin_menu_edit', array('id' => $menu->getId()));
        }
        return array(
            'menu' => $menu,
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
     * @Route("/admin/menu/{id}")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, menu $menu)
    {
        $form = $this->createDeleteForm($menu);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($menu);
            $em->flush();
        }
        return $this->redirectToRoute('admin_menu_index');
    }
    /**
     * Creates a form to delete a menu entity.
     *
     * @param menu $menu
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(menu $menu)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_menu_delete', array('id' => $menu->getId())))
            ->setMethod('DELETE')
            ->getForm();
    }
}

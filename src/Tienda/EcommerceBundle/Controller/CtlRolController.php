<?php

namespace Tienda\EcommerceBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Tienda\EcommerceBundle\Entity\CtlRol;
use Tienda\EcommerceBundle\Form\CtlRolType;

/**
 * CtlRol controller.
 *
 * @Route("/admin/rol")
 */
class CtlRolController extends Controller
{
    /**
     * Lists all CtlRol entities.
     *
     * @Route("/", name="admin_rol_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $ctlRols = $em->getRepository('TiendaEcommerceBundle:CtlRol')->findAll();

        return $this->render('ctlrol/index.html.twig', array(
            'ctlRols' => $ctlRols,
        ));
    }

    /**
     * Creates a new CtlRol entity.
     *
     * @Route("/new", name="admin_rol_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $ctlRol = new CtlRol();
        $form = $this->createForm('Tienda\EcommerceBundle\Form\CtlRolType', $ctlRol);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($ctlRol);
            $em->flush();

            return $this->redirectToRoute('admin_rol_show', array('id' => $ctlRol->getId()));
        }

        return $this->render('ctlrol/new.html.twig', array(
            'ctlRol' => $ctlRol,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a CtlRol entity.
     *
     * @Route("/{id}", name="admin_rol_show")
     * @Method("GET")
     */
    public function showAction(CtlRol $ctlRol)
    {
        $deleteForm = $this->createDeleteForm($ctlRol);

        return $this->render('ctlrol/show.html.twig', array(
            'ctlRol' => $ctlRol,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing CtlRol entity.
     *
     * @Route("/{id}/edit", name="admin_rol_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, CtlRol $ctlRol)
    {
        $deleteForm = $this->createDeleteForm($ctlRol);
        $editForm = $this->createForm('Tienda\EcommerceBundle\Form\CtlRolType', $ctlRol);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($ctlRol);
            $em->flush();

            return $this->redirectToRoute('admin_rol_edit', array('id' => $ctlRol->getId()));
        }

        return $this->render('ctlrol/edit.html.twig', array(
            'ctlRol' => $ctlRol,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a CtlRol entity.
     *
     * @Route("/{id}", name="admin_rol_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, CtlRol $ctlRol)
    {
        $form = $this->createDeleteForm($ctlRol);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($ctlRol);
            $em->flush();
        }

        return $this->redirectToRoute('admin_rol_index');
    }

    /**
     * Creates a form to delete a CtlRol entity.
     *
     * @param CtlRol $ctlRol The CtlRol entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(CtlRol $ctlRol)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_rol_delete', array('id' => $ctlRol->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}

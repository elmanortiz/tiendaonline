<?php

namespace Tienda\EcommerceBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Tienda\EcommerceBundle\Entity\Categoria;
use Tienda\EcommerceBundle\Form\CategoriaType;

/**
 * Categoria controller.
 *
 * @Route("/admin/categoria")
 */
class CategoriaController extends Controller
{
    /**
     * Lists all Categoria entities.
     *
     * @Route("/", name="admin_categoria_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $categorias = $em->getRepository('TiendaEcommerceBundle:Categoria')->findAll();

        return $this->render('categoria/index.html.twig', array(
            'categorias' => $categorias,
        ));
    }

    /**
     * Creates a new Categoria entity.
     *
     * @Route("/new", name="admin_categoria_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $categorium = new Categoria();
        $form = $this->createForm('Tienda\EcommerceBundle\Form\CategoriaType', $categorium);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($categorium);
            $em->flush();

            return $this->redirectToRoute('admin_categoria_show', array('id' => $categorium->getId()));
        }

        return $this->render('categoria/new.html.twig', array(
            'categorium' => $categorium,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Categoria entity.
     *
     * @Route("/{id}", name="admin_categoria_show")
     * @Method("GET")
     */
    public function showAction(Categoria $categorium)
    {
        $deleteForm = $this->createDeleteForm($categorium);

        return $this->render('categoria/show.html.twig', array(
            'categorium' => $categorium,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Categoria entity.
     *
     * @Route("/{id}/edit", name="admin_categoria_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Categoria $categorium)
    {
        $deleteForm = $this->createDeleteForm($categorium);
        $editForm = $this->createForm('Tienda\EcommerceBundle\Form\CategoriaType', $categorium);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($categorium);
            $em->flush();

            return $this->redirectToRoute('admin_categoria_edit', array('id' => $categorium->getId()));
        }

        return $this->render('categoria/edit.html.twig', array(
            'categorium' => $categorium,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Categoria entity.
     *
     * @Route("/{id}", name="admin_categoria_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Categoria $categorium)
    {
        $form = $this->createDeleteForm($categorium);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($categorium);
            $em->flush();
        }

        return $this->redirectToRoute('admin_categoria_index');
    }

    /**
     * Creates a form to delete a Categoria entity.
     *
     * @param Categoria $categorium The Categoria entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Categoria $categorium)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_categoria_delete', array('id' => $categorium->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
    
    /**
     * 
     *
     * @Route("/categoria/data/as", name="admin_categoria_data", options={"expose"=true})
     */
    public function dataCategoriaAction(Request $request)
    {        
        $start = $request->query->get('start');
        $draw = $request->query->get('draw');
        $longitud = $request->query->get('length');
        $busqueda = $request->query->get('search');
        
        $em = $this->getDoctrine()->getEntityManager();
        $rowsTotal = $em->getRepository('TiendaEcommerceBundle:Categoria')->findAll();
        
        $row['draw']=$draw++;  
        $row['recordsTotal'] = count($rowsTotal);
        $row['recordsFiltered']= count($rowsTotal);
        $row['data']= array();

        $arrayFiltro = explode(' ',$busqueda['value']);
        
        $orderParam = $request->query->get('order');
        $orderBy = $orderParam[0]['column'];
        $orderDir = $orderParam[0]['dir'];

        $orderByText="";
        switch(intval($orderBy)){
            case 1:
                $orderByText = "name";
                break;
        }
        
        $busqueda['value'] = str_replace(' ', '%', $busqueda['value']);
        if($busqueda['value']!=''){                                
            $dql = "SELECT CONCAT('<div style=\"text-align: left;\">', pri.nombre, '</div>') as name, "
                    . "'<a ><i style=\"cursor:pointer;\"  class=\"infoOriginSource fa fa-info-circle\"></i></a>' as link, "
                    . "CONCAT('<div id=\"',pri.id,'\" style=\"text-align:left\"><input style=\"z-index:5;\" class=\"chkItem\" type=\"checkbox\"></div>') as check, "
                    . "case "
                    . "when pri.estado = 1 then 'Activo' "
                    . "else 'Inactivo' "
                    . "as state "
                    . "FROM ERPCRMBundle:CtlPrioridad pri "
                    . "WHERE pri.estado = 1 AND CONCAT(upper(pri.nombre), ' ' , upper(pri.estado)) LIKE upper(:busqueda) "
                    . "ORDER BY ".$orderByText." ".$orderDir;

            $row['data'] = $em->createQuery($dql)
                    ->setParameters(array('busqueda'=>"%".$busqueda['value']."%"))
                    ->getResult();

            $row['recordsFiltered']= count($row['data']);

            $dql = "SELECT CONCAT('<div style=\"text-align: left;\">', pri.nombre, '</div>') as name, "
                    . "'<a ><i style=\"cursor:pointer;\"  class=\"infoOriginSource fa fa-info-circle\"></i></a>' as link, "
                    . "CONCAT('<div id=\"',pri.id,'\" style=\"text-align:left\"><input style=\"z-index:5;\" class=\"chkItem\" type=\"checkbox\"></div>') as check, "
                    . "case "
                    . "when pri.estado = 1 then 'Active' "
                    . "else 'Inactive' "
                    . "as state "
                    . "FROM ERPCRMBundle:CtlPrioridad pri "
                    . "WHERE pri.estado = 1 AND CONCAT(upper(pri.nombre),' ', upper(pri.estado)) LIKE upper(:busqueda) "
                    . "ORDER BY ".$orderByText." ".$orderDir;

            $row['data'] = $em->createQuery($dql)
                    ->setParameters(array('busqueda'=>"%".$busqueda['value']."%"))
                    ->setFirstResult($start)
                    ->setMaxResults($longitud)
                    ->getResult();              
        }
        else{
            $dql = "SELECT CONCAT('<div style=\"text-align: left;\">', pri.nombre, '</div>') as name, "
                    . "'<a ><i style=\"cursor:pointer;\"  class=\"infoOriginSource fa fa-info-circle\"></i></a>' as link, "
                    . "CONCAT('<div id=\"',pri.id,'\" style=\"text-align:left\"><input style=\"z-index:5;\" class=\"chkItem\" type=\"checkbox\"></div>') as check, "
                    . "case "
                    . "when pri.estado = 1 then 'Active' "
                    . "else 'Inactive' "
                    . "as state "
                    . "FROM ERPCRMBundle:CtlPrioridad pri "
                    . "WHERE pri.estado = 1 ORDER BY ".$orderByText." ".$orderDir;
            
            $row['data'] = $em->createQuery($dql)
                    ->setFirstResult($start)
                    ->setMaxResults($longitud)
                    ->getResult();
        }
        
        return new Response(json_encode($row));
    }
}

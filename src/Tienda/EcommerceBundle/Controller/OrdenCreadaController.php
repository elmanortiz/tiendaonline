<?php

namespace Tienda\EcommerceBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Tienda\EcommerceBundle\Entity\OrdenCreada;
use Tienda\EcommerceBundle\Form\OrdenCreadaType;

/**
 * OrdenCreada controller.
 *
 * @Route("/admin/orden-creada")
 */
class OrdenCreadaController extends Controller
{
    /**
     * Lists all OrdenCreada entities.
     *
     * @Route("/", name="admin_ordencre_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $ordenCreadas = $em->getRepository('TiendaEcommerceBundle:OrdenCreada')->findAll();

        return $this->render('ordencreada/index.html.twig', array(
            'ordenCreadas' => $ordenCreadas,
        ));
    }
    
    /**
     * Listado de los pedidos realizados
     *
     * @Route("/historial", name="admin_historial_pedidos")
     * @Method("GET")
     */
    public function historialPedidosAction()
    {
        

        return $this->render('ordencreada/historialPedidos.html.twig', array(
//            'ordenCreadas' => $ordenCreadas,
        ));
    }

    /**
     * Creates a new OrdenCreada entity.
     *
     * @Route("/new", name="admin_ordencre_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $ordenCreada = new OrdenCreada();
        $form = $this->createForm('Tienda\EcommerceBundle\Form\OrdenCreadaType', $ordenCreada);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($ordenCreada);
            $em->flush();

            return $this->redirectToRoute('admin_orden_show', array('id' => $ordenCreada->getId()));
        }

        return $this->render('ordencreada/new.html.twig', array(
            'ordenCreada' => $ordenCreada,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a OrdenCreada entity.
     *
     * @Route("/{id}", name="admin_ordencre_show")
     * @Method("GET")
     */
    public function showAction(OrdenCreada $ordenCreada)
    {
        $deleteForm = $this->createDeleteForm($ordenCreada);

        return $this->render('ordencreada/show.html.twig', array(
            'ordenCreada' => $ordenCreada,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing OrdenCreada entity.
     *
     * @Route("/{id}/edit", name="admin_ordencre_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, OrdenCreada $ordenCreada)
    {
        $deleteForm = $this->createDeleteForm($ordenCreada);
        $editForm = $this->createForm('Tienda\EcommerceBundle\Form\OrdenCreadaType', $ordenCreada);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($ordenCreada);
            $em->flush();

            return $this->redirectToRoute('admin_orden_edit', array('id' => $ordenCreada->getId()));
        }

        return $this->render('ordencreada/edit.html.twig', array(
            'ordenCreada' => $ordenCreada,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a OrdenCreada entity.
     *
     * @Route("/{id}", name="admin_ordencre_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, OrdenCreada $ordenCreada)
    {
        $form = $this->createDeleteForm($ordenCreada);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($ordenCreada);
            $em->flush();
        }

        return $this->redirectToRoute('admin_ordencre_index');
    }

    /**
     * Creates a form to delete a OrdenCreada entity.
     *
     * @param OrdenCreada $ordenCreada The OrdenCreada entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(OrdenCreada $ordenCreada)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_orden_delete', array('id' => $ordenCreada->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
    
    /**
     * 
     *
     * @Route("/historial-pedidos/data/as", name="admin_historialpedidos_data", options={"expose"=true})
     */
    public function dataHistorialPedidosAction(Request $request)
    {        
        $start = $request->query->get('start');
        $draw = $request->query->get('draw');
        $longitud = $request->query->get('length');
        $busqueda = $request->query->get('search');
        
        $em = $this->getDoctrine()->getEntityManager();
        $rowsTotal = $em->getRepository('TiendaEcommerceBundle:OrdenCreada')->findAll();
        
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
            case 0:
                $orderByText = "cookie";
                break;
            case 1:
                $orderByText = "cliente";
                break;
            case 2:
                $orderByText = "direccion";
                break;
            case 3:
                $orderByText = "total";
                break;
        }
        
        $busqueda['value'] = str_replace(' ', '%', $busqueda['value']);
        if($busqueda['value']!=''){                                            
            $sql = "select CONCAT('<div id=\"',ped.cookie,'\" style=\"text-align: center;\">',ped.fecha_registro, '</div>') as fecha, "
                    . "sum((ped.precio * ped.cantidad)) + (shi.valor) as total, "
                    . "CONCAT('<div style=\"text-align: left;\">', ped.direccion,', ', mun.nombre, '</div>') as direccion, "                    
                    . "CONCAT('<div style=\"text-align: left;\">', cli.nombre, ' ', cli.apellido, '</div>') as cliente "
                    . "from orden_creada ped inner join municipio mun on ped.municipio_id = mun.id "
                    . "inner join departamento dep1 on mun.departamento_id = dep1.id "
                    . "inner join cliente cli on ped.cliente_id = cli.id "
                    . "inner join shipping shi on ped.shipping = shi.id "
                    . "where CONCAT(upper(ped.direccion), ' ' , upper(cli.nombre), ' ' , upper(cli.apellido), ' ' , upper(mun.nombre)) LIKE upper('%".$busqueda['value']."%') "
                    . "group by ped.cookie "
                    . "ORDER BY ".$orderByText." ".$orderDir
                    . " LIMIT $start, $longitud ";
            
            $stm1 = $this->container->get('database_connection')->prepare($sql);
            $stm1->execute();
            $row['data'] = $stm1->fetchAll();

            $row['recordsFiltered']= count($row['data']);
            
            $sql = "select CONCAT('<div id=\"',ped.cookie,'\" style=\"text-align: center;\">',ped.fecha_registro, '</div>') as fecha, "
                    . "sum((ped.precio * ped.cantidad)) + (shi.valor) as total, "
                    . "CONCAT('<div style=\"text-align: left;\">', ped.direccion,', ', mun.nombre, '</div>') as direccion, "                    
                    . "CONCAT('<div style=\"text-align: left;\">', cli.nombre, ' ', cli.apellido, '</div>') as cliente "
                    . "from orden_creada ped inner join municipio mun on ped.municipio_id = mun.id "
                    . "inner join departamento dep1 on mun.departamento_id = dep1.id "
                    . "inner join cliente cli on ped.cliente_id = cli.id "
                    . "inner join shipping shi on ped.shipping = shi.id "
                    . "where CONCAT(upper(ped.direccion), ' ' , upper(cli.nombre), ' ' , upper(cli.apellido), ' ' , upper(mun.nombre)) LIKE upper('%".$busqueda['value']."%') "
                    . "group by ped.cookie "
                    . "ORDER BY ".$orderByText." ".$orderDir
                    . " LIMIT $start, $longitud ";
            
            $stm = $this->container->get('database_connection')->prepare($sql);
            $stm->execute();
            $row['data'] = $stm->fetchAll();
        }
        else{            
            $sql = "select CONCAT('<div id=\"',ped.cookie,'\" style=\"text-align: center;\">', DATE_FORMAT(ped.fecha_registro, '%d/%m/%Y %h:%i:%s %p'), '</div>') as fecha, "
                    . "sum((ped.precio * ped.cantidad)) + (shi.valor) as total, "
                    . "CONCAT('<div style=\"text-align: left;\">', ped.direccion,', ', mun.nombre, '</div>') as direccion, "                    
                    . "CONCAT('<div style=\"text-align: left;\">', cli.nombre, ' ', cli.apellido, '</div>') as cliente "
                    . "from orden_creada ped inner join municipio mun on ped.municipio_id = mun.id "
                    . "inner join departamento dep1 on mun.departamento_id = dep1.id "
                    . "inner join cliente cli on ped.cliente_id = cli.id "
                    . "inner join shipping shi on ped.shipping = shi.id "
                    . "group by ped.cookie "
                    . "ORDER BY ".$orderByText." ".$orderDir
                    . " LIMIT $start, $longitud ";
            
            $stm = $this->container->get('database_connection')->prepare($sql);
            $stm->execute();
            $row['data'] = $stm->fetchAll();
        }
        
        return new Response(json_encode($row));
    }
}

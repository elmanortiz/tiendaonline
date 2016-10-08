<?php

namespace Tienda\EcommerceBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Tienda\EcommerceBundle\Entity\Shipping;
use Tienda\EcommerceBundle\Form\ShippingType;

/**
 * Shipping controller.
 *
 * @Route("/admin/gestion-shipping")
 */
class ShippingController extends Controller
{
    /**
     * Lists all Shipping entities.
     *
     * @Route("/", name="admin_gestionshipping_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $departamentos = $em->getRepository('TiendaEcommerceBundle:Departamento')->findAll();

        return $this->render('shipping/index.html.twig', array(
            'departamentos' => $departamentos,
        ));
    }

    /**
     * Creates a new Shipping entity.
     *
     * @Route("/new", name="admin_gestionshipping_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $shipping = new Shipping();
        $form = $this->createForm('Tienda\EcommerceBundle\Form\ShippingType', $shipping);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($shipping);
            $em->flush();

            return $this->redirectToRoute('admin_gestionshipping_show', array('id' => $shipping->getId()));
        }

        return $this->render('shipping/new.html.twig', array(
            'shipping' => $shipping,
            'form' => $form->createView(),
        ));
    }
    
    /**
     * Creates a new Shipping entity.
     *
     * @Route("/registro-shipping", name="admin_registro_shipping", options={"expose"=true})
     * @Method({"GET", "POST"})
     */
    public function registroShippingAction(Request $request)
    {
        $isAjax = $this->get('Request')->isXMLhttpRequest();
        if($isAjax){
            try {
                $em = $this->getDoctrine()->getManager();
                
                $parameters = $request->request->all();
                $valorShipping = $parameters['valor'];
                $deptoShipping = $parameters['depto'];
                $id = $parameters['id'];
                
                $sql = "SELECT dep.id FROM TiendaEcommerceBundle:Shipping shi "
                        . "JOIN shi.departamento dep "
                        . "WHERE dep.id = :busqueda";
                
                $objectDuplicate = $em->createQuery($sql)
                                     ->setParameters(array('busqueda' => $deptoShipping))
                                     ->getResult(); 
                
                if ($id!='') {
                    $shipping = $em->getRepository('TiendaEcommerceBundle:Shipping')->find($id);
                    $shipping->setEstado(0);

                    $em->merge($shipping);
                    $em->flush();
                    
                    $shippingNvo = new Shipping();
                    $shippingNvo->setValor($valorShipping);
                    $shippingNvo->setDepartamento($shipping->getDepartamento());
                    $shippingNvo->setEstado(1);

                    $em->persist($shippingNvo);
                    $em->flush();

                    $serverUpdate = $this->getParameter('app.serverMsgUpdate');
                    $data['msg']=$serverUpdate; 
                    $data['id']=$shippingNvo->getId();
                } else {
                    if (!count($objectDuplicate)) {
                        $depto = $em->getRepository('TiendaEcommerceBundle:Departamento')->find($deptoShipping);
                        
                        $shipping = new Shipping();
                        $shipping->setValor($valorShipping);
                        $shipping->setDepartamento($depto);
                        $shipping->setEstado(1);
                        
                        $em->persist($shipping);
                        $em->flush();
                        
                        $serverSave = $this->getParameter('app.serverMsgSave');
                        $data['msg']=$serverSave;
                        $data['id']=$shipping->getId();
                    } else {
                        $data['error'] = $this->getParameter('app.serverDuplicateDepto');                        
                    }                                        
                }
                
                $response = new JsonResponse();
                $response->setData(array(
                                  'msg'   => $data
                               ));  
            
                return $response; 
            } catch (Exception $e) {
                if(method_exists($e,'getErrorCode')){
                    switch (intval($e->getErrorCode())){
                        case 2003: 
                            $data['error'] = $this->getParameter('app.serverOffline');
                        break;
                        case 1062: 
                            $data['error'] = $this->getParameter('app.serverDuplicateDepto');
                        break;
                        default :
                            $data['error'] = $e->getMessage();                     
                        break;
                        }      
                }
                else{
                    $data['error']=$e->getMessage();
                }
                    
                $response = new JsonResponse();
                $response->setData(array(
                                  'msg'   => $data
                               ));  
            }                
        } else {    
            return new Response('0');              
        }
    }

    /**
     * Finds and displays a Shipping entity.
     *
     * @Route("/{id}", name="admin_gestionshipping_show")
     * @Method("GET")
     */
    public function showAction(Shipping $shipping)
    {
        $deleteForm = $this->createDeleteForm($shipping);

        return $this->render('shipping/show.html.twig', array(
            'shipping' => $shipping,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Shipping entity.
     *
     * @Route("/{id}/edit", name="admin_gestionshipping_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Shipping $shipping)
    {
        $deleteForm = $this->createDeleteForm($shipping);
        $editForm = $this->createForm('Tienda\EcommerceBundle\Form\ShippingType', $shipping);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($shipping);
            $em->flush();

            return $this->redirectToRoute('admin_gestionshipping_edit', array('id' => $shipping->getId()));
        }

        return $this->render('shipping/edit.html.twig', array(
            'shipping' => $shipping,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Shipping entity.
     *
     * @Route("/{id}", name="admin_gestionshipping_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Shipping $shipping)
    {
        $form = $this->createDeleteForm($shipping);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($shipping);
            $em->flush();
        }

        return $this->redirectToRoute('admin_gestionshipping_index');
    }

    /**
     * Creates a form to delete a Shipping entity.
     *
     * @param Shipping $shipping The Shipping entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Shipping $shipping)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_gestionshipping_delete', array('id' => $shipping->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
    
    /**
     * 
     *
     * @Route("/shipping/data/as", name="admin_gestionshipping_data", options={"expose"=true})
     */
    public function dataShippingAction(Request $request)
    {        
        $start = $request->query->get('start');
        $draw = $request->query->get('draw');
        $longitud = $request->query->get('length');
        $busqueda = $request->query->get('search');
        
        $em = $this->getDoctrine()->getEntityManager();
        $rowsTotal = $em->getRepository('TiendaEcommerceBundle:Shipping')->findAll(array('estado' => 1));
        
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
                $orderByText = "dep.nombre";
                break;
            case 1:
                $orderByText = "valor";
                break;
        }
        
        $busqueda['value'] = str_replace(' ', '%', $busqueda['value']);
        if($busqueda['value']!=''){                                
            $dql = "SELECT CONCAT('<div style=\"text-align: right;\">', shi.valor, '</div>') as valor, "
//                    . "'<a ><i style=\"cursor:pointer;\"  class=\"infoTalla fa fa-info-circle\"></i></a>' as link, "
//                    . "CONCAT('<div id=\"',shi.id,'\" style=\"text-align:left\"><input style=\"z-index:5;\" class=\"chkItem\" type=\"checkbox\"></div>') as check, "
                    . "CONCAT('<div id=\"',shi.id,'\" style=\"text-align: left;\">', dep.nombre, '</div>') as depto "
//                    . "case "
//                    . "when tal.estado = 1 then 'Activo' "
//                    . "else 'Inactivo' "
//                    . "as state "
                    . "FROM TiendaEcommerceBundle:Shipping shi "
                    . "JOIN shi.departamento dep "
                    . "WHERE shi.estado = 1 AND CONCAT(upper(shi.valor), ' ' , upper(dep.nombre)) LIKE upper(:busqueda) "
                    . "ORDER BY ".$orderByText." ".$orderDir;

            $row['data'] = $em->createQuery($dql)
                    ->setParameters(array('busqueda'=>"%".$busqueda['value']."%"))
                    ->getResult();

            $row['recordsFiltered']= count($row['data']);

            $dql = "SELECT CONCAT('<div style=\"text-align: right;\">', shi.valor, '</div>') as valor, "
//                    . "'<a ><i style=\"cursor:pointer;\"  class=\"infoOriginSource fa fa-info-circle\"></i></a>' as link, "
//                    . "CONCAT('<div id=\"',shi.id,'\" style=\"text-align:left\"><input style=\"z-index:5;\" class=\"chkItem\" type=\"checkbox\"></div>') as check, "
                    . "CONCAT('<div id=\"',shi.id,'\" style=\"text-align: left;\">', dep.nombre, '</div>') as depto "
//                    . "case "
//                    . "when shi.estado = 1 then 'Activo' "
//                    . "else 'Inactivo' "
//                    . "as state "
                    . "FROM TiendaEcommerceBundle:Shipping shi "
                    . "JOIN shi.departamento dep "
                    . "WHERE shi.estado = 1 AND CONCAT(upper(shi.valor), ' ' , upper(dep.nombre)) LIKE upper(:busqueda) "
                    . "ORDER BY ".$orderByText." ".$orderDir;

            $row['data'] = $em->createQuery($dql)
                    ->setParameters(array('busqueda'=>"%".$busqueda['value']."%"))
                    ->setFirstResult($start)
                    ->setMaxResults($longitud)
                    ->getResult();              
        }
        else{
            $dql = "SELECT CONCAT('<div style=\"text-align: right;\">', shi.valor, '</div>') as valor, "
//                    . "'<a ><i style=\"cursor:pointer;\"  class=\"infoOriginSource fa fa-info-circle\"></i></a>' as link, "
//                    . "CONCAT('<div id=\"',shi.id,'\" style=\"text-align:left\"><input style=\"z-index:5;\" class=\"chkItem\" type=\"checkbox\"></div>') as check, "
                    . "CONCAT('<div id=\"',shi.id,'\" style=\"text-align: left;\">', dep.nombre, '</div>') as depto "
//                    . "case "
//                    . "when shi.estado = 1 then 'Activo' "
//                    . "else 'Inactivo' "
//                    . "as state "
                    . "FROM TiendaEcommerceBundle:Shipping shi "
                    . "JOIN shi.departamento dep "
                    . "WHERE shi.estado = 1 "
                    . "ORDER BY ".$orderByText." ".$orderDir;
            
            $row['data'] = $em->createQuery($dql)
                    ->setFirstResult($start)
                    ->setMaxResults($longitud)
                    ->getResult();
        }
        
        return new Response(json_encode($row));
    }
    
    /**
     * Recuperar el shipping del departamento solicitado
     *
     * @Route("/recuperar/shipping-depto-solicitada", name="admin_recuperar_shipping", options={"expose"=true}))
     * @Method("POST")
     */
    public function recuperarShippingAction(Request $request)
    {
        try {
            $id=$request->get("id");
            $response = new JsonResponse();
            
            $em = $this->getDoctrine()->getManager();
            $shipping = $em->getRepository('TiendaEcommerceBundle:Shipping')->find($id);
            if(count($shipping)){
                
                $data['valor']=$shipping->getValor();
                $data['depto']=$shipping->getDepartamento()->getNombre();
                $data['id']=$shipping->getId();
            }
            else{
                $data['error']="Error";
            }
                        
            $response->setData($data); 
            
        } catch (\Exception $e) {
            switch (intval($e->getCode()))
                    {
                        case 2003: 
                            $data['error'] = $this->getParameter('app.serverOffline');
                        break;
                        default :
                            $data['error'] = $e->getMessage();                     
                        break;
                    }      
            $response->setData($data);
        }
        
        return $response;
        
    }
}

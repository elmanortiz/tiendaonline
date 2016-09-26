<?php

namespace Tienda\EcommerceBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Tienda\EcommerceBundle\Entity\Talla;
use Tienda\EcommerceBundle\Form\TallaType;

/**
 * Talla controller.
 *
 * @Route("/admin/gestion-tallas")
 */
class TallaController extends Controller
{
    /**
     * Lists all Talla entities.
     *
     * @Route("/", name="admin_gestiontalla_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        return $this->render('talla/index.html.twig', array(
//            'tallas' => $tallas,
        ));
    }

    /**
     * Creates a new Talla entity.
     *
     * @Route("/new", name="admin_gestiontalla_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $talla = new Talla();
        $form = $this->createForm('Tienda\EcommerceBundle\Form\TallaType', $talla);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($talla);
            $em->flush();

            return $this->redirectToRoute('admin_gestiontalla_show', array('id' => $talla->getId()));
        }

        return $this->render('talla/new.html.twig', array(
            'talla' => $talla,
            'form' => $form->createView(),
        ));
    }
    
    /**
     * Creates a new Categoria entity.
     *
     * @Route("/registro-talla", name="admin_registro_talla", options={"expose"=true})
     * @Method({"GET", "POST"})
     */
    public function registroTallaAction(Request $request)
    {
        $isAjax = $this->get('Request')->isXMLhttpRequest();
        if($isAjax){
            try {
                $em = $this->getDoctrine()->getManager();
                
                $parameters = $request->request->all();
                $nombreTalla = $parameters['name'];
                $id = $parameters['id'];
                
                $sql = "SELECT upper(tal.nombre) FROM TiendaEcommerceBundle:Talla tal "
                        . "WHERE upper(tal.nombre) LIKE upper(:busqueda) AND tal.estado = 1";
                
                $objectDuplicate = $em->createQuery($sql)
                                     ->setParameters(array('busqueda'=>"%".strtoupper($nombreTalla)."%"))
                                     ->getResult(); 
                
                if ($id!='') {
                    $talla = $em->getRepository('TiendaEcommerceBundle:Talla')->find($id);
                    $talla->setNombre($nombreTalla);

                    $em->merge($talla);
                    $em->flush();

                    $serverUpdate = $this->getParameter('app.serverMsgUpdate');
                    $data['msg']=$serverUpdate; 
                    $data['id']=$talla->getId();
                } else {
                    if (!count($objectDuplicate)) {
                        $talla = new Talla();
                        $talla->setNombre($nombreTalla);
                        $talla->setEstado(1);

                        $em->persist($talla);
                        $em->flush();
                        
                        $serverSave = $this->getParameter('app.serverMsgSave');
                        $data['msg']=$serverSave;
                        $data['id']=$talla->getId();
                    } else {
                        $data['error'] = $this->getParameter('app.serverDuplicateName');                        
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
                            $data['error'] = $this->getParameter('app.serverDuplicateName');
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
     * Finds and displays a Talla entity.
     *
     * @Route("/{id}", name="admin_gestiontalla_show")
     * @Method("GET")
     */
    public function showAction(Talla $talla)
    {
        $deleteForm = $this->createDeleteForm($talla);

        return $this->render('talla/show.html.twig', array(
            'talla' => $talla,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Talla entity.
     *
     * @Route("/{id}/edit", name="admin_gestiontalla_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Talla $talla)
    {
        $deleteForm = $this->createDeleteForm($talla);
        $editForm = $this->createForm('Tienda\EcommerceBundle\Form\TallaType', $talla);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($talla);
            $em->flush();

            return $this->redirectToRoute('admin_gestiontalla_edit', array('id' => $talla->getId()));
        }

        return $this->render('talla/edit.html.twig', array(
            'talla' => $talla,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Talla entity.
     *
     * @Route("/{id}", name="admin_gestiontalla_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Talla $talla)
    {
        $form = $this->createDeleteForm($talla);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($talla);
            $em->flush();
        }

        return $this->redirectToRoute('admin_gestiontalla_index');
    }

    /**
     * Creates a form to delete a Talla entity.
     *
     * @param Talla $talla The Talla entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Talla $talla)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_gestiontalla_delete', array('id' => $talla->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
    
    /**
     * 
     *
     * @Route("/talla/data/as", name="admin_gestiontalla_data", options={"expose"=true})
     */
    public function dataTallasAction(Request $request)
    {        
        $start = $request->query->get('start');
        $draw = $request->query->get('draw');
        $longitud = $request->query->get('length');
        $busqueda = $request->query->get('search');
        
        $em = $this->getDoctrine()->getEntityManager();
        $rowsTotal = $em->getRepository('TiendaEcommerceBundle:Talla')->findAll();
        
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
            $dql = "SELECT CONCAT('<div style=\"text-align: left;\">', tal.nombre, '</div>') as name, "
                    . "'<a ><i style=\"cursor:pointer;\"  class=\"infoTalla fa fa-info-circle\"></i></a>' as link, "
                    . "CONCAT('<div id=\"',tal.id,'\" style=\"text-align:left\"><input style=\"z-index:5;\" class=\"chkItem\" type=\"checkbox\"></div>') as check, "
                    . "case "
                    . "when tal.estado = 1 then 'Activo' "
                    . "else 'Inactivo' "
                    . "as state "
                    . "FROM TiendaEcommerceBundle:Talla tal "
                    . "WHERE tal.estado = 1 AND CONCAT(upper(tal.nombre), ' ' , upper(tal.estado)) LIKE upper(:busqueda) "
                    . "ORDER BY ".$orderByText." ".$orderDir;

            $row['data'] = $em->createQuery($dql)
                    ->setParameters(array('busqueda'=>"%".$busqueda['value']."%"))
                    ->getResult();

            $row['recordsFiltered']= count($row['data']);

            $dql = "SELECT CONCAT('<div style=\"text-align: left;\">', tal.nombre, '</div>') as name, "
                    . "'<a ><i style=\"cursor:pointer;\"  class=\"infoOriginSource fa fa-info-circle\"></i></a>' as link, "
                    . "CONCAT('<div id=\"',tal.id,'\" style=\"text-align:left\"><input style=\"z-index:5;\" class=\"chkItem\" type=\"checkbox\"></div>') as check, "
                    . "case "
                    . "when tal.estado = 1 then 'Activo' "
                    . "else 'Inactivo' "
                    . "as state "
                    . "FROM TiendaEcommerceBundle:Talla tal "
                    . "WHERE tal.estado = 1 AND CONCAT(upper(tal.nombre),' ', upper(tal.estado)) LIKE upper(:busqueda) "
                    . "ORDER BY ".$orderByText." ".$orderDir;

            $row['data'] = $em->createQuery($dql)
                    ->setParameters(array('busqueda'=>"%".$busqueda['value']."%"))
                    ->setFirstResult($start)
                    ->setMaxResults($longitud)
                    ->getResult();              
        }
        else{
            $dql = "SELECT CONCAT('<div style=\"text-align: left;\">', tal.nombre, '</div>') as name, "
                    . "'<a ><i style=\"cursor:pointer;\"  class=\"infoOriginSource fa fa-info-circle\"></i></a>' as link, "
                    . "CONCAT('<div id=\"',tal.id,'\" style=\"text-align:left\"><input style=\"z-index:5;\" class=\"chkItem\" type=\"checkbox\"></div>') as check, "
                    . "case "
                    . "when tal.estado = 1 then 'Activo' "
                    . "else 'Inactivo' "
                    . "as state "
                    . "FROM TiendaEcommerceBundle:Talla tal "
                    . "WHERE tal.estado = 1 ORDER BY ".$orderByText." ".$orderDir;
            
            $row['data'] = $em->createQuery($dql)
                    ->setFirstResult($start)
                    ->setMaxResults($longitud)
                    ->getResult();
        }
        
        return new Response(json_encode($row));
    }
    
    /**
     * Recuperar las categorias
     *
     * @Route("/recuperar/categoria-solicitada", name="admin_recuperar_tallas", options={"expose"=true}))
     * @Method("POST")
     */
    public function recuperarTallasAction(Request $request)
    {
        try {
            $id=$request->get("id");
            $response = new JsonResponse();
            
            $em = $this->getDoctrine()->getManager();
            $talla = $em->getRepository('TiendaEcommerceBundle:Talla')->find($id);
            if(count($talla)){
                
                $data['name']=$talla->getNombre();
                $data['id']=$talla->getId();
            }
            else{
                $data['error']="Error";
            }
                        
            $response->setData($data); 
            
        } catch (\Exception $e) {
            switch (intval($e->getErrorCode()))
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
    
    /**
     * Eliminar las tallas seleccionadas del sistema
     *
     * @Route("/Tallas/delete", name="admin_eliminar_tallas",  options={"expose"=true}))
     * @Method("POST")
     */
    public function deleteTallasAction(Request $request)
    {
        try {
            $ids=$request->get("param1");
            $response = new JsonResponse();
            
            $em = $this->getDoctrine()->getManager();
            foreach ($ids as $key => $id) {
                $object = $em->getRepository('TiendaEcommerceBundle:Talla')->find($id);    
                if(count($object)){
                    $object->setEstado(0);
                    $em->merge($object);
                    $em->flush();    
                    
                    $serverDelete = $this->getParameter('app.serverMsgDelete');
                    $data['msg']=$serverDelete;
                }
                else{
                    $data['error']="Error";
                }
            }
            $response->setData($data); 
        } catch (\Exception $e) {
            $response = new JsonResponse();
            
            switch (intval($e->getErrorCode()))
                    {
                        case 2003: 
                            $data['error'] = $this->getParameter('app.serverOffline');
                        break;
                        default :
                            $data['error'] = $e->getMessage();                     
                        break;
                    }      
            $data['error']=$e->getMessage();
            $response->setData($data);
        }
        
        return $response;        
    }
}

<?php

namespace Tienda\EcommerceBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Tienda\EcommerceBundle\Entity\Color;
use Tienda\EcommerceBundle\Form\ColorType;

/**
 * Color controller.
 *
 * @Route("/admin/gestion-color")
 */
class ColorController extends Controller
{
    /**
     * Lists all Color entities.
     *
     * @Route("/", name="admin_color_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        return $this->render('color/index.html.twig', array(
//            'colors' => $colors,
        ));
    }

    /**
     * Creates a new Color entity.
     *
     * @Route("/new", name="admin_color_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $color = new Color();
        $form = $this->createForm('Tienda\EcommerceBundle\Form\ColorType', $color);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($color);
            $em->flush();

            return $this->redirectToRoute('admin_color_show', array('id' => $color->getId()));
        }

        return $this->render('color/new.html.twig', array(
            'color' => $color,
            'form' => $form->createView(),
        ));
    }
    
    /**
     * Creates a new Categoria entity.
     *
     * @Route("/registro-color", name="admin_registro_color", options={"expose"=true})
     * @Method({"GET", "POST"})
     */
    public function registroColorAction(Request $request)
    {
        $isAjax = $this->get('Request')->isXMLhttpRequest();
        if($isAjax){
            try {
                $em = $this->getDoctrine()->getManager();
                
                $parameters = $request->request->all();
                $nombreColor= $parameters['name'];
                $id = $parameters['id'];
                
                $sql = "SELECT upper(col.nombre) FROM TiendaEcommerceBundle:Color col "
                        . "WHERE upper(col.nombre) LIKE upper(:busqueda) AND col.estado = 1";
                
                $objectDuplicate = $em->createQuery($sql)
                                     ->setParameters(array('busqueda'=>"%".strtoupper($nombreColor)."%"))
                                     ->getResult(); 
                
                if ($id!='') {
                    $color = $em->getRepository('TiendaEcommerceBundle:Color')->find($id);
                    $color->setNombre($nombreColor);

                    $em->merge($color);
                    $em->flush();

                    $serverUpdate = $this->getParameter('app.serverMsgUpdate');
                    $data['msg']=$serverUpdate; 
                    $data['id']=$color->getId();
                } else {
                    if (!count($objectDuplicate)) {
                        $color = new Color();
                        $color->setNombre($nombreColor);
                        $color->setEstado(1);

                        $em->persist($color);
                        $em->flush();
                        
                        $serverSave = $this->getParameter('app.serverMsgSave');
                        $data['msg']=$serverSave;
                        $data['id']=$color->getId();
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
     * Finds and displays a Color entity.
     *
     * @Route("/{id}", name="admin_color_show")
     * @Method("GET")
     */
    public function showAction(Color $color)
    {
        $deleteForm = $this->createDeleteForm($color);

        return $this->render('color/show.html.twig', array(
            'color' => $color,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Color entity.
     *
     * @Route("/{id}/edit", name="admin_color_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Color $color)
    {
        $deleteForm = $this->createDeleteForm($color);
        $editForm = $this->createForm('Tienda\EcommerceBundle\Form\ColorType', $color);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($color);
            $em->flush();

            return $this->redirectToRoute('admin_color_edit', array('id' => $color->getId()));
        }

        return $this->render('color/edit.html.twig', array(
            'color' => $color,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Color entity.
     *
     * @Route("/{id}", name="admin_color_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Color $color)
    {
        $form = $this->createDeleteForm($color);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($color);
            $em->flush();
        }

        return $this->redirectToRoute('admin_color_index');
    }

    /**
     * Creates a form to delete a Color entity.
     *
     * @param Color $color The Color entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Color $color)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_color_delete', array('id' => $color->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
    
    /**
     * 
     *
     * @Route("/color/data/as", name="admin_colores_data", options={"expose"=true})
     */
    public function dataColoresAction(Request $request)
    {        
        $start = $request->query->get('start');
        $draw = $request->query->get('draw');
        $longitud = $request->query->get('length');
        $busqueda = $request->query->get('search');
        
        $em = $this->getDoctrine()->getEntityManager();
        $rowsTotal = $em->getRepository('TiendaEcommerceBundle:Color')->findAll();
        
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
            $dql = "SELECT CONCAT('<div style=\"text-align: left;\">', col.nombre, '</div>') as name, "
                    . "'<a ><i style=\"cursor:pointer;\"  class=\"infoOriginSource fa fa-info-circle\"></i></a>' as link, "
                    . "CONCAT('<div id=\"',col.id,'\" style=\"text-align:left\"><input style=\"z-index:5;\" class=\"chkItem\" type=\"checkbox\"></div>') as check, "
                    . "case "
                    . "when col.estado = 1 then 'Activo' "
                    . "else 'Inactivo' "
                    . "as state "
                    . "FROM TiendaEcommerceBundle:Color col "
                    . "WHERE col.estado = 1 AND CONCAT(upper(col.nombre), ' ' , upper(col.estado)) LIKE upper(:busqueda) "
                    . "ORDER BY ".$orderByText." ".$orderDir;

            $row['data'] = $em->createQuery($dql)
                    ->setParameters(array('busqueda'=>"%".$busqueda['value']."%"))
                    ->getResult();

            $row['recordsFiltered']= count($row['data']);

            $dql = "SELECT CONCAT('<div style=\"text-align: left;\">', col.nombre, '</div>') as name, "
                    . "'<a ><i style=\"cursor:pointer;\"  class=\"infoOriginSource fa fa-info-circle\"></i></a>' as link, "
                    . "CONCAT('<div id=\"',col.id,'\" style=\"text-align:left\"><input style=\"z-index:5;\" class=\"chkItem\" type=\"checkbox\"></div>') as check, "
                    . "case "
                    . "when col.estado = 1 then 'Activo' "
                    . "else 'Inactivo' "
                    . "as state "
                    . "FROM TiendaEcommerceBundle:Color col "
                    . "WHERE col.estado = 1 AND CONCAT(upper(col.nombre),' ', upper(col.estado)) LIKE upper(:busqueda) "
                    . "ORDER BY ".$orderByText." ".$orderDir;

            $row['data'] = $em->createQuery($dql)
                    ->setParameters(array('busqueda'=>"%".$busqueda['value']."%"))
                    ->setFirstResult($start)
                    ->setMaxResults($longitud)
                    ->getResult();              
        }
        else{
            $dql = "SELECT CONCAT('<div style=\"text-align: left;\">', col.nombre, '</div>') as name, "
                    . "'<a ><i style=\"cursor:pointer;\"  class=\"infoOriginSource fa fa-info-circle\"></i></a>' as link, "
                    . "CONCAT('<div id=\"',col.id,'\" style=\"text-align:left\"><input style=\"z-index:5;\" class=\"chkItem\" type=\"checkbox\"></div>') as check, "
                    . "case "
                    . "when col.estado = 1 then 'Activo' "
                    . "else 'Inactivo' "
                    . "as state "
                    . "FROM TiendaEcommerceBundle:Color col "
                    . "WHERE col.estado = 1 ORDER BY ".$orderByText." ".$orderDir;
            
            $row['data'] = $em->createQuery($dql)
                    ->setFirstResult($start)
                    ->setMaxResults($longitud)
                    ->getResult();
        }
        
        return new Response(json_encode($row));
    }
    
    /**
     * Recuperar los colores registrados
     *
     * @Route("/recuperar/color-solicitado", name="admin_recuperar_colores", options={"expose"=true}))
     * @Method("POST")
     */
    public function recuperarColoresAction(Request $request)
    {
        try {
            $id=$request->get("id");
            $response = new JsonResponse();
            
            $em = $this->getDoctrine()->getManager();
            $color = $em->getRepository('TiendaEcommerceBundle:Color')->find($id);
            if(count($color)){
                
                $data['name']=$color->getNombre();
                $data['id']=$color->getId();
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
     * Eliminar los colores seleccionados del sistema
     *
     * @Route("/colores/delete", name="admin_eliminar_colores",  options={"expose"=true}))
     * @Method("POST")
     */
    public function deleteColoresAction(Request $request)
    {
        try {
            $ids=$request->get("param1");
            $response = new JsonResponse();
            
            $em = $this->getDoctrine()->getManager();
            foreach ($ids as $key => $id) {
                $object = $em->getRepository('TiendaEcommerceBundle:Color')->find($id);    
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

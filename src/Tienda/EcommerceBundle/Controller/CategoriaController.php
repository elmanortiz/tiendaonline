<?php

namespace Tienda\EcommerceBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Tienda\EcommerceBundle\Entity\Categoria;
use Tienda\EcommerceBundle\Form\CategoriaType;

/**
 * Categoria controller.
 *
 * @Route("/admin/categoria-producto")
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
        return $this->render('categoria/index.html.twig', array(
            //'categorias' => $categorias,
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
     * Creates a new Categoria entity.
     *
     * @Route("/registro-categoria", name="admin_registro_categoria", options={"expose"=true})
     * @Method({"GET", "POST"})
     */
    public function registroCategoriaAction(Request $request)
    {
        $isAjax = $this->get('Request')->isXMLhttpRequest();
        if($isAjax){
            try {
                $em = $this->getDoctrine()->getManager();
                
                $parameters = $request->request->all();
                $nombreCategoria = $parameters['name'];
                $id = $parameters['id'];
                
                $sql = "SELECT upper(cat.nombre) FROM TiendaEcommerceBundle:Categoria cat "
                        . "WHERE upper(cat.nombre) LIKE upper(:busqueda) AND cat.estado = 1";
                
                $objectDuplicate = $em->createQuery($sql)
                                     ->setParameters(array('busqueda'=>"%".strtoupper($nombreCategoria)."%"))
                                     ->getResult(); 
                
                if ($id!='') {
                    $categoria = $em->getRepository('TiendaEcommerceBundle:Categoria')->find($id);
                    $categoria->setNombre($nombreCategoria);

                    $em->merge($categoria);
                    $em->flush();

                    $serverUpdate = $this->getParameter('app.serverMsgUpdate');
                    $data['msg']=$serverUpdate; 
                    $data['id']=$categoria->getId();
                } else {
                    if (!count($objectDuplicate)) {
                        $categoria = new Categoria();
                        $categoria->setNombre($nombreCategoria);
                        $categoria->setEstado(1);

                        $em->persist($categoria);
                        $em->flush();
                        
                        $serverSave = $this->getParameter('app.serverMsgSave');
                        $data['msg']=$serverSave;
                        $data['id']=$categoria->getId();
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
            $dql = "SELECT CONCAT('<div style=\"text-align: left;\">', cat.nombre, '</div>') as name, "
                    . "'<a ><i style=\"cursor:pointer;\"  class=\"infoOriginSource fa fa-info-circle\"></i></a>' as link, "
                    . "CONCAT('<div id=\"',cat.id,'\" style=\"text-align:left\"><input style=\"z-index:5;\" class=\"chkItem\" type=\"checkbox\"></div>') as check, "
                    . "case "
                    . "when cat.estado = 1 then 'Activo' "
                    . "else 'Inactivo' "
                    . "as state "
                    . "FROM TiendaEcommerceBundle:Categoria cat "
                    . "WHERE cat.estado = 1 AND CONCAT(upper(cat.nombre), ' ' , upper(cat.estado)) LIKE upper(:busqueda) "
                    . "ORDER BY ".$orderByText." ".$orderDir;

            $row['data'] = $em->createQuery($dql)
                    ->setParameters(array('busqueda'=>"%".$busqueda['value']."%"))
                    ->getResult();

            $row['recordsFiltered']= count($row['data']);

            $dql = "SELECT CONCAT('<div style=\"text-align: left;\">', cat.nombre, '</div>') as name, "
                    . "'<a ><i style=\"cursor:pointer;\"  class=\"infoOriginSource fa fa-info-circle\"></i></a>' as link, "
                    . "CONCAT('<div id=\"',cat.id,'\" style=\"text-align:left\"><input style=\"z-index:5;\" class=\"chkItem\" type=\"checkbox\"></div>') as check, "
                    . "case "
                    . "when cat.estado = 1 then 'Activo' "
                    . "else 'Inactivo' "
                    . "as state "
                    . "FROM TiendaEcommerceBundle:Categoria cat "
                    . "WHERE cat.estado = 1 AND CONCAT(upper(cat.nombre),' ', upper(cat.estado)) LIKE upper(:busqueda) "
                    . "ORDER BY ".$orderByText." ".$orderDir;

            $row['data'] = $em->createQuery($dql)
                    ->setParameters(array('busqueda'=>"%".$busqueda['value']."%"))
                    ->setFirstResult($start)
                    ->setMaxResults($longitud)
                    ->getResult();              
        }
        else{
            $dql = "SELECT CONCAT('<div style=\"text-align: left;\">', cat.nombre, '</div>') as name, "
                    . "'<a ><i style=\"cursor:pointer;\"  class=\"infoOriginSource fa fa-info-circle\"></i></a>' as link, "
                    . "CONCAT('<div id=\"',cat.id,'\" style=\"text-align:left\"><input style=\"z-index:5;\" class=\"chkItem\" type=\"checkbox\"></div>') as check, "
                    . "case "
                    . "when cat.estado = 1 then 'Activo' "
                    . "else 'Inactivo' "
                    . "as state "
                    . "FROM TiendaEcommerceBundle:Categoria cat "
                    . "WHERE cat.estado = 1 ORDER BY ".$orderByText." ".$orderDir;
            
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
     * @Route("/recuperar/categoria-solicitada", name="admin_recuperar_categorias", options={"expose"=true}))
     * @Method("POST")
     */
    public function recuperarCategoriaAction(Request $request)
    {
        try {
            $id=$request->get("id");
            $response = new JsonResponse();
            
            $em = $this->getDoctrine()->getManager();
            $categoria = $em->getRepository('TiendaEcommerceBundle:Categoria')->find($id);
            if(count($categoria)){
                
                $data['name']=$categoria->getNombre();
                $data['id']=$categoria->getId();
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
     * Eliinar categorias de producto seleccionadas
     *
     * @Route("/priority/delete", name="admin_eliminar_categorias",  options={"expose"=true}))
     * @Method("POST")
     */
    public function deletePriorityAction(Request $request)
    {
        try {
            $ids=$request->get("param1");
            $response = new JsonResponse();
            
            $em = $this->getDoctrine()->getManager();
            foreach ($ids as $key => $id) {
                $object = $em->getRepository('TiendaEcommerceBundle:Categoria')->find($id);    
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

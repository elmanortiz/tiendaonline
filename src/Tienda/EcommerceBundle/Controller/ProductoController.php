<?php
namespace Tienda\EcommerceBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Tienda\EcommerceBundle\Entity\Producto;
use Tienda\EcommerceBundle\Entity\Atributoproducto;
use Tienda\EcommerceBundle\Entity\Imagenproducto;
use Tienda\EcommerceBundle\Entity\ColorProducto;
use Tienda\EcommerceBundle\Entity\TallaProducto;
use Tienda\EcommerceBundle\Form\ProductoType;

/**
 * Producto controller.
 *
 * @Route("/admin/producto")
 */
class ProductoController extends Controller
{
    /**
     * Lists all Producto entities.
     *
     * @Route("/", name="admin_producto_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $productos = $em->getRepository('TiendaEcommerceBundle:Producto')->findAll();        
        return $this->render('producto/index.html.twig', array(
            'productos' => $productos,
        ));
    }

    /**
     * Creates a new Producto entity.
     *
     * @Route("/newprod", name="admin_producto_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
//        $producto = new Producto();
//        $form = $this->createForm('Tienda\EcommerceBundle\Form\ProductoType', $producto);
//        $form->handleRequest($request);        
//        if ($form->isSubmitted() && $form->isValid()) {
//            $em = $this->getDoctrine()->getManager();
//            $em->persist($producto);
//            $em->flush();
//
//            return $this->redirectToRoute('admin_producto_show', array('id' => $producto->getId()));
//        }
        
        $em = $this->getDoctrine()->getManager();
        $catprod = $em->getRepository('TiendaEcommerceBundle:Categoria')->findBy(array('estado'=>1));                                                
        $colores = $em->getRepository('TiendaEcommerceBundle:Color')->findBy(array('estado'=>1));                                                
        $tallas = $em->getRepository('TiendaEcommerceBundle:Talla')->findBy(array('estado'=>1));                                                
        return $this->render('producto/newprod.html.twig', array(            
            'categoriaproducto'=>$catprod,
            'colores'=>$colores,
            'tallas'=>$tallas
//            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Producto entity.
     *
     * @Route("/{id}", name="admin_producto_show")
     * @Method("GET")
     */
    public function showAction(Producto $producto)
    {
        $deleteForm = $this->createDeleteForm($producto);

        return $this->render('producto/show.html.twig', array(
            'producto' => $producto,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Producto entity.
     *
     * @Route("/{id}/edit", name="admin_producto_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Producto $producto)
    {
        $deleteForm = $this->createDeleteForm($producto);
        $editForm = $this->createForm('Tienda\EcommerceBundle\Form\ProductoType', $producto);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($producto);
            $em->flush();

            return $this->redirectToRoute('admin_producto_edit', array('id' => $producto->getId()));
        }

        return $this->render('producto/edit.html.twig', array(
            'producto' => $producto,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Producto entity.
     *
     * @Route("/{id}", name="admin_producto_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Producto $producto)
    {
        $form = $this->createDeleteForm($producto);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($producto);
            $em->flush();
        }

        return $this->redirectToRoute('admin_producto_index');
    }

    /**
     * Creates a form to delete a Producto entity.
     *
     * @param Producto $producto The Producto entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Producto $producto)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_producto_delete', array('id' => $producto->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
    
    /**
     * 
     *
     * @Route("/data/as", name="admin_producto_data")
     */
    public function dataproductoAction(Request $request) {

        $start = $request->query->get('start');
        $draw = $request->query->get('draw');
        $longitud = $request->query->get('length');
        $busqueda = $request->query->get('search');        
        $em = $this->getDoctrine()->getEntityManager();
        $rowsTotal = $em->getRepository('TiendaEcommerceBundle:Producto')->findBy(array('estado'=>1));

        $row['draw'] = $draw++;
        $row['recordsTotal'] = count($rowsTotal);
        $row['recordsFiltered'] = count($rowsTotal);
        $row['data'] = array();

        $arrayFiltro = explode(' ', $busqueda['value']);

        $orderParam = $request->query->get('order');
        $orderBy = $orderParam[0]['column'];
        $orderDir = $orderParam[0]['dir'];

        $orderByText = "";
        switch (intval($orderBy)) {
            case 1:
                $orderByText = "name";
                break;
            case 2:
                $orderByText = "categoria";
                break;
            case 4:
                $orderByText = "stock";
                break;
        }

        $busqueda['value'] = str_replace(' ', '%', $busqueda['value']);
        if ($busqueda['value'] != '') {

            $dql = "SELECT CONCAT('<div id=\"',pac.id,'\" style=\"text-align:center;\"><input style=\"z-index:5;\" class=\"chkItem\" type=\"checkbox\"></div>') as chk, CONCAT('<div id=\"',pac.id,'\" style=\"text-align:center;\">',pac.nombre,'</div>') as name, CONCAT('<div style=\"text-align:center;\">',pac.precio,'</div>') as precio, CONCAT('<div style=\"text-align:center;\">',pac.stock,'</div>') as stock, CONCAT('<div style=\"text-align:center;\">',cat.nombre,'</div>') as categoria, '<a ><i style=\"cursor:pointer;\"  class=\"infoPaciente fa fa-info-circle\"></i></a>' as actions FROM TiendaEcommerceBundle:Producto pac "
                    . "JOIN pac.categoria as cat "
                    . "WHERE pac.estado=1 AND (CONCAT(upper(pac.nombre),' ') LIKE upper(:busqueda) OR CONCAT(upper(pac.stock),' ') LIKE upper(:busqueda) OR CONCAT(upper(pac.stock),' ') LIKE upper(:busqueda) OR CONCAT(upper(cat.nombre),' ') LIKE upper(:busqueda)) "
                    . "ORDER BY " . $orderByText . " " . $orderDir;
            $row['data'] = $em->createQuery($dql)
                    ->setParameters(array('busqueda' => "%" . $busqueda['value'] . "%"))
                    ->getResult();

            $row['recordsFiltered'] = count($row['data']);

            $dql = "SELECT CONCAT('<div id=\"',pac.id,'\" style=\"text-align:center;\"><input style=\"z-index:5;\" class=\"chkItem\" type=\"checkbox\"></div>') as chk, CONCAT('<div id=\"',pac.id,'\" style=\"text-align:center;\">',pac.nombre,'</div>') as name,CONCAT('<div style=\"text-align:center;\">',pac.precio,'</div>') as precio, CONCAT('<div style=\"text-align:center;\">',pac.stock,'</div>') as stock, CONCAT('<div style=\"text-align:center;\">',cat.nombre,'</div>') as categoria, '<a ><i style=\"cursor:pointer;\"  class=\"infoPaciente fa fa-info-circle\"></i></a>' as actions FROM TiendaEcommerceBundle:Producto pac "
                    . "JOIN pac.categoria as cat "
                    . "WHERE pac.estado=1 AND (CONCAT(upper(pac.nombre),' ') LIKE upper(:busqueda) OR CONCAT(upper(pac.stock),' ') LIKE upper(:busqueda) OR CONCAT(upper(pac.stock),' ') LIKE upper(:busqueda) OR CONCAT(upper(cat.nombre),' ') LIKE upper(:busqueda)) "
                    . "ORDER BY " . $orderByText . " " . $orderDir;
            $row['data'] = $em->createQuery($dql)
                    ->setParameters(array('busqueda' => "%" . $busqueda['value'] . "%"))
                    ->setFirstResult($start)
                    ->setMaxResults($longitud)
                    ->getResult();
        } else {
            $dql = "SELECT CONCAT('<div id=\"',pac.id,'\" style=\"text-align:center;\"><input style=\"z-index:5;\" class=\"chkItem\" type=\"checkbox\"></div>') as chk, CONCAT('<div id=\"',pac.id,'\" style=\"text-align:center;\">',pac.nombre,'</div>') as name,CONCAT('<div style=\"text-align:center;\">',pac.precio,'</div>') as precio, CONCAT('<div style=\"text-align:center;\">',pac.stock,'</div>') as stock, CONCAT('<div style=\"text-align:center;\">',cat.nombre,'</div>') as categoria, '<a ><i style=\"cursor:pointer;\"  class=\"infoPaciente fa fa-info-circle\"></i></a>' as actions, pac.estado as estado FROM TiendaEcommerceBundle:Producto pac "
                    . "JOIN pac.categoria as cat "
                    . "WHERE pac.estado = 1 "
                    . "ORDER BY " . $orderByText . " " . $orderDir;
            $row['data'] = $em->createQuery($dql)
                    ->setFirstResult($start)
                    ->setMaxResults($longitud)
                    ->getResult();          
        }
        return new Response(json_encode($row));
    }
    
    /**
     * Lists all productos of inventory.
     *
     * @Route("/insertprod", name="inventariotienda_insertprod")
     * @Method({"GET", "POST"})
     */  
    public function insertarproductoAction(Request $request)
    {
        $parameters = $request->request->all();
        $em = $this->getDoctrine()->getManager();

        $producto = new Producto();        
        $atributoproducto = new Atributoproducto();
        $fotoproducto = new Imagenproducto(); 
        $colorproducto = new ColorProducto();
        $tallaproducto = new TallaProducto();
        
        $producto->setNombre($parameters['nombreprod']);
        $producto->setPrecio($parameters['precio']);
        $producto->setNumeroReferencia($parameters['codigo']);
        $producto->setEstado(1);
        $producto->setStock($parameters['stock']);

        if(isset($parameters['disponibilidad'])){            
            $producto->setDisponible(0); //Si existe la variable es porque se marco como no disponible
        }else{
            $producto->setDisponible(1);
        }
        if(isset($parameters['msjexistencia'])){           
            $producto->setMensaje($parameters['msjexistencia']);
        }
        
        $idcat = $parameters['categoria'];
        
        $catprod = $em->getRepository('TiendaEcommerceBundle:Categoria')->find($idcat);        
        $producto->setCategoria($catprod);
        $em->persist($producto);
        $em->flush();
        
        //Insertando datos en la tabla de atributos del producto
        $n=  count($parameters['atributo']);
        for($i=0;$i<$n;$i++){
            $atributoproducto->setNombre($parameters['atributo'][$i]);
            $atributoproducto->setPorcentaje($parameters['porcentaje'][$i]);
            $atributoproducto->setProducto($producto);//Id producto
            $em->persist($atributoproducto);
            $em->flush();            
            unset($atributoproducto);
            $atributoproducto = new Atributoproducto();
        }
                                                                      
        $path = $this->container->getParameter('photo.producto');
                
        $fecha = date('Y-m-d-His');
        //$extension = $entity->getFile()->getClientOriginalExtension();
        $nombreArchivo1 = "producto_".$fecha."_".$_FILES['userfile']['name'];
        $nombreArchivo2 = "producto_".$fecha."_".$_FILES['userfile2']['name'];
        $nombreArchivo3 = "producto_".$fecha."_".$_FILES['userfile3']['name'];
        
        if(move_uploaded_file($_FILES['userfile']['tmp_name'], $path.$nombreArchivo1)){
            $fotoproducto->setImagen1($nombreArchivo1);
        }
        if(move_uploaded_file($_FILES['userfile2']['tmp_name'], $path.$nombreArchivo2)){
            $fotoproducto->setImagen2($nombreArchivo2);
        }
        if(move_uploaded_file($_FILES['userfile3']['tmp_name'], $path.$nombreArchivo3)){
            $fotoproducto->setImagen3($nombreArchivo3);
        }
        
        //Insertando las fotos del producto
        $fotoproducto->setProducto($producto);
        $em->persist($fotoproducto);
        $em->flush();
        
        //insertando los colores del producto
        $ncolor=  count($parameters['color']);
        for($i=0;$i<$ncolor;$i++){
            $colorproducto->setProducto($producto);//Id producto
            $color = $em->getRepository('TiendaEcommerceBundle:Color')->find($parameters['color'][$i]);             
            $colorproducto->setColor($color);
            $em->persist($colorproducto);
            $em->flush();
            unset($colorproducto);
            $colorproducto = new ColorProducto();
        }
        
        //insertando las tallas del producto
        $ntalla = count($parameters['talla']);
        for ($i = 0; $i < $ntalla; $i++) {
            $tallaproducto->setProducto($producto); //Id producto
            $talla = $em->getRepository('TiendaEcommerceBundle:Talla')->find($parameters['talla'][$i]);
            $tallaproducto->setTalla($talla);
            $em->persist($tallaproducto);
            $em->flush();
            unset($tallaproducto);
            $tallaproducto = new TallaProducto();
        }

        //return $this->render('ERPCRMBundle:inventariobeard:newprod.html.twig', array('categoriaproducto'=>$catprod));
        return $this->redirect($this->generateUrl('admin_producto_index'));        
    }    
    
    /**
     * Delete data Producto.
     *
     * @Route("/delete", name="admin_producto_delete" , options={"expose"=true})
     * @Method({"GET", "POST"})
     */
    public function updateindustriaAction(Request $request) {

        //$response = new JsonResponse();
        $producto = new Producto();
        $isAjax = $this->get('Request')->isXMLhttpRequest();

        if ($isAjax) {
            $em = $this->getDoctrine()->getManager();
            $ids = $request->get('ids');

            foreach ($ids as $key => $value) {
                //var_dump($value);
                $producto = $em->getRepository('TiendaEcommerceBundle:Producto')->find($value);
                $producto->setEstado(0);
                $em->merge($producto);
                $em->flush();
            }

            $data['success'] = "Type of industry deleted";
            //$response->setData($data);
            //return $response;
            return new Response(json_encode($data));
        }
    }
            
    /**
     * Creates a show_edit Producto entity.
     *
     * @Route("/showedit/{id}", name="admin_showedit_producto", options={"expose"=true})
     * @Method({"GET", "POST"})
     */
    public function showeditAction(Request $request)
    {        
        $request = $this->getRequest();
        $idproducto = $request->get('id');
        
        /**Producto-categoria-imagen**/
        $em = $this->getDoctrine()->getManager(); 
        $sqlProducto = "select prod.id as id, prod.nombre as nombre, prod.precio as precio, prod.numeroReferencia as codigo, prod.stock, prod.disponible as dispo, prod.mensaje as msj, cat.id as idcat,
                                cat.nombre as nomcat,
                                imgprod.imagen1 as img1, imgprod.imagen2 as img2, imgprod.imagen3 as img3                                
                                from producto prod 
                                inner join categoria cat on cat.id = prod.categoria_id                                
                                inner join imagenproducto imgprod on prod.id = imgprod.producto_id                                
                                where prod.estado = 1 AND prod.id=".$idproducto;
        
        /**Para materiales**/
        $sqlAtributo ="select mate.id as id, mate.nombre as nommate, mate.porcentaje as por                                
                                from atributoproducto mate
                                inner join producto prod on prod.id = mate.producto_id                                                                
                                where prod.estado = 1 AND prod.id=".$idproducto;
        
        /**Para colores**/
        $sqlColores ="select   coprod.id as idcopro, coprod.color_id as coid, coprod.producto_id as proid, col.nombre as colnom
                                from producto prod
                                inner join color_producto coprod on coprod.producto_id = prod.id
                                inner join color col on col.id = coprod.color_id                                                                
                                where prod.estado = 1 AND prod.id=".$idproducto;
        
        /**Para tallas**/
        $sqlTallas ="select   taprod.id as idtapro, taprod.talla_id as taid, taprod.producto_id as proid, ta.nombre as tanom
                                from producto prod
                                inner join talla_producto taprod on taprod.producto_id = prod.id
                                inner join talla ta on ta.id = taprod.talla_id                                                                
                                where prod.estado = 1 AND prod.id=".$idproducto;
        
        /**Para producto**/
        $stm = $this->container->get('database_connection')->prepare($sqlProducto);
        $stm->execute();
        $result_productos = $stm->fetchAll();
        
        /**Para materiales**/
        $stm = $this->container->get('database_connection')->prepare($sqlAtributo);
        $stm->execute();
        $result_materiales = $stm->fetchAll();
        $nmateriales = count($result_materiales);
        var_dump($nmateriales);
        /**Para colores**/
        $stm = $this->container->get('database_connection')->prepare($sqlColores);
        $stm->execute();
        $result_colores = $stm->fetchAll();
        
        /**Para tallas**/
        $stm = $this->container->get('database_connection')->prepare($sqlTallas);
        $stm->execute();
        $result_tallas = $stm->fetchAll();
                 
        $colores = $em->getRepository('TiendaEcommerceBundle:Color')->findBy(array('estado'=>1));                                                
        $tallas = $em->getRepository('TiendaEcommerceBundle:Talla')->findBy(array('estado'=>1)); 
        $categorias = $em->getRepository('TiendaEcommerceBundle:Categoria')->findBy(array('estado'=>1));
                                
        return $this->render('producto/edit.html.twig', array(            
            'productos'=>$result_productos,
            'materiales'=>$result_materiales,
            'colores'=>$result_colores,
            'tallas'=>$result_tallas,
            'ctlcolores'=>$colores,
            'nmateriales'=>$nmateriales,
            'ctltallas'=>$tallas,
            'ctlcategorias'=>$categorias           
        ));
    }
    
    /***************Editar Producto********************************************/
    /**
     * Editar producto.
     *
     * @Route("/editprod", name="inventariotienda_editprod")
     * @Method({"GET", "POST"})
     */
    public function editarproductoAction(Request $request) {
        $parameters = $request->request->all();
        $em = $this->getDoctrine()->getManager();
                
        $producto = $em->getRepository('TiendaEcommerceBundle:Producto')->find($parameters['idproducto']);
                       
        $atributoproducto = new Atributoproducto();
        $fotoproducto = $em->getRepository('TiendaEcommerceBundle:Imagenproducto')->findOneBy(array('producto'=>$producto));
        $colorproducto = new ColorProducto();
        $tallaproducto = new TallaProducto();

        $producto->setNombre($parameters['nombreprod']);
        $producto->setPrecio($parameters['precio']);
        $producto->setNumeroReferencia($parameters['codigo']);
        $producto->setEstado(1);
        $producto->setStock($parameters['stock']);

        if (isset($parameters['disponibilidad'])) {
            $producto->setDisponible(0); //Si existe la variable es porque se marco como no disponible
        } else {
            $producto->setDisponible(1);
        }
        if (isset($parameters['msjexistencia'])) {
            $producto->setMensaje($parameters['msjexistencia']);
        }

        $idcat = $parameters['categoria'];

        $catprod = $em->getRepository('TiendaEcommerceBundle:Categoria')->find($parameters['categoria']);
        $producto->setCategoria($catprod);
        $em->merge($producto);
        $em->flush();
        
        //Eliminando todos los materiales                
        $attrdelete=$em->getRepository('TiendaEcommerceBundle:Atributoproducto')->findBy(array('producto'=>$producto));
        foreach ($attrdelete as $row){            
            $em->remove($row);
            $em->flush();
        }
                
        //Insertando datos en la tabla de atributos del producto
        if(isset($parameters['atributo'])){
            $n = count($parameters['atributo']);
            for ($i = 0; $i < $n; $i++) {
                $atributoproducto->setNombre($parameters['atributo'][$i]);
                $atributoproducto->setPorcentaje($parameters['porcentaje'][$i]);
                $atributoproducto->setProducto($producto); //Id producto
                $em->persist($atributoproducto);
                $em->flush();
                unset($atributoproducto);
                $atributoproducto = new Atributoproducto();
            }
        }
        
        $path = $this->container->getParameter('photo.producto');

        $fecha = date('Y-m-d-His');
        //$extension = $entity->getFile()->getClientOriginalExtension();
        
        $nombreArchivo1 = "producto_" . $fecha . "_" . $_FILES['userfile']['name'];
        $nombreArchivo2 = "producto_" . $fecha . "_" . $_FILES['userfile2']['name'];
        $nombreArchivo3 = "producto_" . $fecha . "_" . $_FILES['userfile3']['name'];
                               
        if (move_uploaded_file($_FILES['userfile']['tmp_name'], $path . $nombreArchivo1)) {
            $fotoproducto->setImagen1($nombreArchivo1);            
        }
        if (move_uploaded_file($_FILES['userfile2']['tmp_name'], $path . $nombreArchivo2)) {
            $fotoproducto->setImagen2($nombreArchivo2);
        }
        if (move_uploaded_file($_FILES['userfile3']['tmp_name'], $path . $nombreArchivo3)) {
            $fotoproducto->setImagen3($nombreArchivo3);
        }

        $em->merge($fotoproducto);
        $em->flush();

         //Eliminando todos los colores            
        $colordelete=$em->getRepository('TiendaEcommerceBundle:ColorProducto')->findBy(array('producto_id'=>$producto));
        foreach ($colordelete as $row){            
            $em->remove($row);
            $em->flush();
        }
        
        //insertando los colores del producto
        $ncolor = count($parameters['color']);
        for ($i = 0; $i < $ncolor; $i++) {
            $colorproducto->setProducto($producto); //Id producto
            $color = $em->getRepository('TiendaEcommerceBundle:Color')->find($parameters['color'][$i]);
            $colorproducto->setColor($color);
            $em->merge($colorproducto);
            $em->flush();
            unset($colorproducto);
            $colorproducto = new ColorProducto();
        }
        
        //Eliminando todos las tallas
        $talladelete=$em->getRepository('TiendaEcommerceBundle:TallaProducto')->findBy(array('producto_id'=>$producto));
        foreach ($talladelete as $row){           
            $em->remove($row);
            $em->flush();
        }
        
        //insertando las tallas del producto
        $ntalla = count($parameters['talla']);
        for ($i = 0; $i < $ntalla; $i++) {
            $tallaproducto->setProducto($producto); //Id producto
            $talla = $em->getRepository('TiendaEcommerceBundle:Talla')->find($parameters['talla'][$i]);
            $tallaproducto->setTalla($talla);
            $em->merge($tallaproducto);
            $em->flush();
            unset($tallaproducto);
            $tallaproducto = new TallaProducto();
        }

        //return $this->render('ERPCRMBundle:inventariobeard:newprod.html.twig', array('categoriaproducto'=>$catprod));
        return $this->redirect($this->generateUrl('admin_producto_index'));
    }
}
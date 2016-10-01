<?php
namespace Tienda\EcommerceBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Tienda\EcommerceBundle\Entity\OrdenCreada;
use Tienda\EcommerceBundle\Entity\Cliente;

/**
 * VentaTienda controller.
 *
 * @Route("/admin/venta-tienda")
 */
class VentaTiendaController extends Controller
{
    /**
     * Lists all OrdenCreada entities.
     *
     * @Route("/", name="admin_venta_tienda_index")
     * @Method("GET")
     */
    public function indexAction()
    {        
        return $this->render('ordencreada/index.html.twig');
    }
    
    /**
     * 
     *
     * @Route("/ventas/data/as", name="admin_ventastienda_data", options={"expose"=true})
     */
    public function dataVentasTiendaAction(Request $request)
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
            $sql = "select CONCAT('<div style=\"text-align: center;\">',ped.fecha_registro, '</div>') as fecha, "
                    . "sum((ped.precio * ped.cantidad)) as total, "
                    . "CONCAT('<div id=\"',ped.id_venta,'\" style=\"text-align: center;\">', ped.id_venta, '</div>') as referencia, "
                    . "CONCAT('<div style=\"text-align: left;\">', cli.nombre, ' ', cli.apellido, '</div>') as cliente "
                    . "from orden_creada ped inner join cliente cli on ped.cliente_id = cli.id "
                    . "where ped.tipo_orden = 2 and CONCAT(upper(ped.direccion), ' ' , upper(cli.nombre), ' ' , upper(cli.apellido), ' ' , upper(mun.nombre)) LIKE upper('%".$busqueda['value']."%') "
                    . "group by ped.id_venta "
                    . "ORDER BY ".$orderByText." ".$orderDir
                    . " LIMIT $start, $longitud ";
            
            $stm1 = $this->container->get('database_connection')->prepare($sql);
            $stm1->execute();
            $row['data'] = $stm1->fetchAll();

            $row['recordsFiltered']= count($row['data']);
            
            $sql = "select CONCAT('<div style=\"text-align: center;\">',ped.fecha_registro, '</div>') as fecha, "
                    . "sum((ped.precio * ped.cantidad)) as total, "
                    . "CONCAT('<div id=\"',ped.id_venta,'\" style=\"text-align: center;\">', ped.id_venta, '</div>') as referencia, "
                    . "CONCAT('<div style=\"text-align: left;\">', cli.nombre, ' ', cli.apellido, '</div>') as cliente "
                    . "from orden_creada ped inner join cliente cli on ped.cliente_id = cli.id "
                    . "where ped.tipo_orden = 2 and CONCAT(upper(ped.direccion), ' ' , upper(cli.nombre), ' ' , upper(cli.apellido), ' ' , upper(mun.nombre)) LIKE upper('%".$busqueda['value']."%') "
                    . "group by ped.id_venta "
                    . "ORDER BY ".$orderByText." ".$orderDir
                    . " LIMIT $start, $longitud ";
            
            $stm = $this->container->get('database_connection')->prepare($sql);
            $stm->execute();
            $row['data'] = $stm->fetchAll();
        }
        else{            
            $sql = "select CONCAT('<div style=\"text-align: center;\">', DATE_FORMAT(ped.fecha_registro, '%d/%m/%Y %h:%i:%s %p'), '</div>') as fecha, "
                    . "sum((ped.precio * ped.cantidad)) as total, "
                    . "CONCAT('<div id=\"',ped.id_venta,'\" style=\"text-align: center;\">', ped.id_venta, '</div>') as referencia, "
                    . "CONCAT('<div style=\"text-align: left;\">', cli.nombre, ' ', cli.apellido, '</div>') as cliente "
                    . "from orden_creada ped inner join cliente cli on ped.cliente_id = cli.id "
                    . "where ped.tipo_orden = 2 "
                    . "group by ped.id_venta "
                    . "ORDER BY ".$orderByText." ".$orderDir
                    . " LIMIT $start, $longitud ";
            
            $stm = $this->container->get('database_connection')->prepare($sql);
            $stm->execute();
            $row['data'] = $stm->fetchAll();
        }
        
        return new Response(json_encode($row));
    }
    
    /**
    * Ajax utilizado para buscar informacion del producto
    *  
    * @Route("/busqueda-producto-select/data", name="busqueda_producto_data", options={"expose"=true})
    */
    public function busquedaProductoAction(Request $request)
    {
        $busqueda = $request->query->get('q');
        
        $sql = "select pro.id objid, CONCAT(pro.nombre, ', color ', lower(col.nombre)) as nombre,
                pro.disponible as disponible 
                from color_producto cp
                inner join producto pro on cp.producto_id = pro.id
                inner join color col on cp.color_id = col.id
                where pro.estado = 1 and CONCAT(upper(pro.nombre), ' ' , upper(col.nombre)) LIKE upper('%".$busqueda."%') 
                order by pro.id asc 
                limit 0, 10";
                
        $stm = $this->container->get('database_connection')->prepare($sql);
        $stm->execute();
        $row['data'] = $stm->fetchAll();
        
        return new Response(json_encode($row));
    }
    
    /**
     * Recuperar el detalle del producto seleccionado
     *
     * @Route("/recuperar/detalle-producto-seleccionado", name="admin_recuperar_detalle_producto", options={"expose"=true}))
     * @Method("POST")
     */
    public function recuperarDetallePedidoAction(Request $request)
    {
        try {
            $id=$request->get("id");
            $response = new JsonResponse();
            $row['data']= array();
            
            $sql = "select pro.id as objid, pro.nombre as producto,
                    pro.precio as precio, tal.id as tallaid, tal.nombre as talla      
                    from talla_producto tp
                    inner join producto pro on tp.producto_id = pro.id
                    inner join talla tal on tp.talla_id = tal.id 
                    where pro.id = ".$id." ";
            
            $stm = $this->container->get('database_connection')->prepare($sql);
            $stm->execute();
            $row['data'] = $stm->fetchAll();
            
            if(!count($row['data'])){
                $row['error'] = "Error";
            }
            else{
                
            }
                        
            $response->setData($row); 
            
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
    
    /**
     * Creates a new Shipping entity.
     *
     * @Route("/registro-venta-tienda", name="admin_registro_venta_tienda", options={"expose"=true})
     * @Method({"GET", "POST"})
     */
    public function registroVentaTiendaAction(Request $request)
    {
        $isAjax = $this->get('Request')->isXMLhttpRequest();
        if($isAjax){
            try {
                $em = $this->getDoctrine()->getManager();
                
                $parameters = $request->request->all();
                
                $id = $parameters['id'];
                $registroCliente = $parameters['cliente-opt'];
                $clienteId = $parameters['sCliente'];
                $nombreCliente = $parameters['txtName'];
                $apellidoCliente = $parameters['txtLastname'];
                $correoCliente = $parameters['txtEmail'];
                $telefonoCliente = $parameters['txtPhone'];
                $cantidadArray = $parameters['cantidad'];
                $productoArray = $parameters['sProducto'];
                $tallaArray = $parameters['sTalla'];
                $precioArray = $parameters['precio'];
                
                if ($id!='') {
                    
                } else {
                    if($registroCliente == 0) {
                        $cliente = new Cliente();
                        
                        $cliente->setNombre($nombreCliente);
                        $cliente->setApellido($apellidoCliente);
                        $cliente->setEmail($correoCliente);
                        $cliente->setTelefono($telefonoCliente);
                        $cliente->setEstado(1);
                        
                        $em->persist($cliente);
                        $em->flush();
                    } else {
                        $cliente = $em->getRepository('TiendaEcommerceBundle:Cliente')->find($clienteId);
                    }
                    
                    $tipoOrden = $em->getRepository('TiendaEcommerceBundle:TipoOrden')->find(2);
                    
                    $sql = "select max(prod.id_venta) as id from orden_creada prod where prod.tipo_orden = 2";
                    $stm = $this->container->get('database_connection')->prepare($sql);
                    $stm->execute();
                    $venta = $stm->fetchAll();
                    
                    if($venta[0]['id']){
                        $corr = intval($venta[0]['id']) + 1;
                    } else {
                        $corr = 1;
                    }
                    
                    foreach ($productoArray as $key => $value) {
                        $ordenCreada = new OrdenCreada();
                        $producto = $em->getRepository('TiendaEcommerceBundle:Producto')->find($value);
                        
                        $sql = "select col.nombre from color_producto cpro inner join color col on cpro.color_id = col.id where cpro.producto_id = " . $producto->getId();
                        $stm = $this->container->get('database_connection')->prepare($sql);
                        $stm->execute();
                        $color = $stm->fetchAll();
                        
                        $ordenCreada->setFechaRegistro(new \DateTime('now'));
                        $ordenCreada->setCliente($cliente);
                        $ordenCreada->setProducto($producto);
                        $ordenCreada->setNombreProd($producto->getNombre());
                        $ordenCreada->setCantidad($cantidadArray[$key]);
                        $ordenCreada->setTalla($tallaArray[$key]);
                        $ordenCreada->setPrecio($precioArray[$key]);
                        $ordenCreada->setTipoOrden($tipoOrden);
                        $ordenCreada->setColor($color[0]['nombre']);
                        $ordenCreada->setEstado(1);
                        $ordenCreada->setIdVenta($corr);
                        
                        $em->persist($ordenCreada);
                        $em->flush();                                                
                    }
                    
                    $serverSave = $this->getParameter('app.serverMsgSave');
                    $data['msg'] = $serverSave;
                    $data['id'] = $corr;
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
     * Recuperar el shipping del departamento solicitado
     *
     * @Route("/recuperar/detalle-venta-tienda-seleccionada", name="admin_recuperar_detalle_ventatienda", options={"expose"=true}))
     * @Method("POST")
     */
    public function recuperarDetalleVentaTiendaAction(Request $request)
    {
        try {
            $id=$request->get("id");
//            var_dump($id);
//            die();
            $response = new JsonResponse();
            
            $sql = "select ped.id_venta, DATE_FORMAT(ped.fecha_registro, '%d/%m/%Y %h:%i:%s %p') as fecha, "
                    . "(ped.precio * ped.cantidad) as total, ped.talla, ped.cantidad, ped.precio, "
                    . "cli.id as clienteid, CONCAT(cli.nombre, ' ', cli.apellido) as cliente, "
                    . "ped.producto as productoId, CONCAT(ped.nombre_prod, ', color ', lower(ped.color)) as producto "
                    . "from orden_creada ped inner join cliente cli on ped.cliente_id = cli.id "
                    . "where ped.id_venta = $id";
            
            $stm = $this->container->get('database_connection')->prepare($sql);
            $stm->execute();
            $ventaTienda = $stm->fetchAll();
            
            
            if(count($ventaTienda)){
                $data['venta'] = $ventaTienda;
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

<?php

namespace Tienda\EcommerceBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Tienda\EcommerceBundle\Reporte\IngresosCategoria;
use Tienda\EcommerceBundle\Reporte\IngresosVentaPDF;

/**
 * Reporte controller.
 *
 * @Route("/admin/reporte")
 */
class ReporteController extends Controller
{
    /**
     * Muestra los ingresos por venta
     *
     * @Route("/ingresos-venta", name="admin_ingresos_venta")
        * @Method("GET")
     */
    public function ReporteIngresosVentaAction()
    {
        $em = $this->getDoctrine()->getEntityManager();
        
        $sql = "SELECT (case month(ord.fecha_registro) "
                    . "when 1 then 'Enero' "
                    . "when 2 then 'Febrero' "
                    . "when 3 then 'Marzo' "
                    . "when 4 then 'Abril' "
                    . "when 5 then 'Mayo' "
                    . "when 6 then 'Junio' "
                    . "when 7 then 'Julio' "
                    . "when 8 then 'Agosto' "
                    . "when 9 then 'Septiembre' "
                    . "when 10 then 'Octubre' "
                    . "when 11 then 'Noviembre' "
                    . "when 12 then 'Diciembre' "
                . "end ) mes, "
                . "year(ord.fecha_registro) as anio, "
                . "(case p.tven when (p.tven is null) then 0 else p.tven end) online, "
                . "(case s.tven when (s.tven is null) then 0 else s.tven end) venta, "
                . "(case r.tven when (r.tven is null) then 0 else r.tven end) total "
                . "FROM orden_creada ord "
                . "LEFT JOIN ( "
                    . "SELECT month(o1.fecha_registro) mes, year(o1.fecha_registro) as anio, "
                    . "SUM(o1.cantidad  * o1.precio) + shi.valor as tven "
                    . "FROM orden_creada o1 INNER JOIN shipping shi ON o1.shipping = shi.id "
                    . "WHERE o1.tipo_orden = 1 "
                    . "GROUP BY month(o1.fecha_registro), year(o1.fecha_registro) "
                . ") p ON month(ord.fecha_registro) = p.mes and year(ord.fecha_registro) = p.anio "
                . "LEFT JOIN  ( "
                    . "SELECT month(o2.fecha_registro) mes, year(o2.fecha_registro) as anio, "
                    . "SUM(o2.cantidad  * o2.precio) tven "
                    . "FROM orden_creada o2     "
                    . "WHERE o2.tipo_orden = 2 "
                    . "GROUP BY month(o2.fecha_registro), year(o2.fecha_registro) "
                . ") s ON month(ord.fecha_registro) = s.mes and year(ord.fecha_registro) = s.anio "
                . "LEFT JOIN  ( "
                    . "SELECT month(o3.fecha_registro) mes, year(o3.fecha_registro) as anio, "
                    . "SUM(o3.cantidad  * o3.precio) tven "
                    . "FROM orden_creada o3 "
                    . "GROUP BY month(o3.fecha_registro), year(o3.fecha_registro) "
                . ") r ON month(ord.fecha_registro) = r.mes and year(ord.fecha_registro) = r.anio "
                . "where ord.fecha_registro > DATE_SUB(now(), INTERVAL 6 MONTH) "
                . "group by month(ord.fecha_registro), year(ord.fecha_registro) "
                . "order by year(ord.fecha_registro), month(ord.fecha_registro)";
        
        $stmt = $em->getConnection()->prepare($sql);
        $stmt->execute();
        $row['data'] = $stmt->fetchAll();
        
        return $this->render('reportes/ingresosventa.html.twig', array(
                    'consolidado' => $row['data'],
                ));
    }
    
    /**
     * Muestra los ingresos por venta - Filtro
     *
     * @Route("/filtro-ingresos-venta", name="admin_ingresos_venta_filtro", options={"expose"=true})
     * @Method("POST")
     */
    public function FiltroIngresosVentaAction(Request $request)
    {
        try {
            $txtfechaInicio = $request->get("fechaInicio");
            $txtfechaFin = $request->get("fechaFin");
            
            $fechaInicio = explode('/', $txtfechaInicio);
            $fechaFin = explode('/', $txtfechaFin);
            
            $em = $this->getDoctrine()->getEntityManager();
            $response = new JsonResponse();
            $row['data']= array();
            
            $sql = "SELECT (case month(ord.fecha_registro) "
                        . "when 1 then 'Enero' "
                        . "when 2 then 'Febrero' "
                        . "when 3 then 'Marzo' "
                        . "when 4 then 'Abril' "
                        . "when 5 then 'Mayo' "
                        . "when 6 then 'Junio' "
                        . "when 7 then 'Julio' "
                        . "when 8 then 'Agosto' "
                        . "when 9 then 'Septiembre' "
                        . "when 10 then 'Octubre' "
                        . "when 11 then 'Noviembre' "
                        . "when 12 then 'Diciembre' "
                    . "end ) mes, "
                    . "year(ord.fecha_registro) as anio, "
                    . "(case p.tven when (p.tven is null) then 0 else p.tven end) online, "
                    . "(case s.tven when (s.tven is null) then 0 else s.tven end) venta, "
                    . "(case r.tven when (r.tven is null) then 0 else r.tven end) total "
                    . "FROM orden_creada ord "
                    . "LEFT JOIN ( "
                        . "SELECT month(o1.fecha_registro) mes, year(o1.fecha_registro) as anio, "
                        . "SUM(o1.cantidad  * o1.precio) + shi.valor as tven "
                        . "FROM orden_creada o1 INNER JOIN shipping shi ON o1.shipping = shi.id "
                        . "WHERE o1.tipo_orden = 1 "
                        . "and o1.fecha_registro >= '" .$fechaInicio[2] . "-" . $fechaInicio[1] . "-" . $fechaInicio[0] . "' and o1.fecha_registro <= '" .$fechaFin[2] . "-" . $fechaFin[1] . "-" . $fechaFin[0] . "' "
                        . "GROUP BY month(o1.fecha_registro), year(o1.fecha_registro) "
                    . ") p ON month(ord.fecha_registro) = p.mes and year(ord.fecha_registro) = p.anio "
                    . "LEFT JOIN  ( "
                        . "SELECT month(o2.fecha_registro) mes, year(o2.fecha_registro) as anio, "
                        . "SUM(o2.cantidad  * o2.precio) tven "
                        . "FROM orden_creada o2     "
                        . "WHERE o2.tipo_orden = 2 "
                        . "and o2.fecha_registro >= '" .$fechaInicio[2] . "-" . $fechaInicio[1] . "-" . $fechaInicio[0] . "' and o2.fecha_registro <= '" .$fechaFin[2] . "-" . $fechaFin[1] . "-" . $fechaFin[0] . "' "
                        . "GROUP BY month(o2.fecha_registro), year(o2.fecha_registro) "
                    . ") s ON month(ord.fecha_registro) = s.mes and year(ord.fecha_registro) = s.anio "
                    . "LEFT JOIN  ( "
                        . "SELECT month(o3.fecha_registro) mes, year(o3.fecha_registro) as anio, "
                        . "SUM(o3.cantidad  * o3.precio) tven "
                        . "FROM orden_creada o3     "
                        . "where o3.fecha_registro >= '" .$fechaInicio[2] . "-" . $fechaInicio[1] . "-" . $fechaInicio[0] . "' and o3.fecha_registro <= '" .$fechaFin[2] . "-" . $fechaFin[1] . "-" . $fechaFin[0] . "' "
                        . "GROUP BY month(o3.fecha_registro), year(o3.fecha_registro) "
                    . ") r ON month(ord.fecha_registro) = r.mes and year(ord.fecha_registro) = r.anio "
                    . "where ord.fecha_registro >= '" .$fechaInicio[2] . "-" . $fechaInicio[1] . "-" . $fechaInicio[0] . "' and ord.fecha_registro <= '" .$fechaFin[2] . "-" . $fechaFin[1] . "-" . $fechaFin[0] . "' "
                    . "group by month(ord.fecha_registro), year(ord.fecha_registro) "
                    . "order by year(ord.fecha_registro), month(ord.fecha_registro)";

            $stmt = $em->getConnection()->prepare($sql);
            $stmt->execute();
            $row['data'] = $stmt->fetchAll();
            
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
     * Muestra los ingresos por venta - PDF
     *
     * @Route("/ingresos-venta-pdf", name="admin_ingresos_venta_pdf", options={"expose"=true})
     * @Method("GET")
     */
    public function ReporteIngresosVentaPDFAction(Request $request)
    {
        $txtfechaInicio = $request->get("fechaInicio");
        $txtfechaFin = $request->get("fechaFin");

        $fechaInicio = explode('/', $txtfechaInicio);
        $fechaFin = explode('/', $txtfechaFin);

        $em = $this->getDoctrine()->getEntityManager();

        $row['data']= array();

        $sql = "SELECT (case month(ord.fecha_registro) "
                    . "when 1 then 'Enero' "
                    . "when 2 then 'Febrero' "
                    . "when 3 then 'Marzo' "
                    . "when 4 then 'Abril' "
                    . "when 5 then 'Mayo' "
                    . "when 6 then 'Junio' "
                    . "when 7 then 'Julio' "
                    . "when 8 then 'Agosto' "
                    . "when 9 then 'Septiembre' "
                    . "when 10 then 'Octubre' "
                    . "when 11 then 'Noviembre' "
                    . "when 12 then 'Diciembre' "
                . "end ) mes, "
                . "year(ord.fecha_registro) as anio, "
                . "(case p.tven when (p.tven is null) then 0 else p.tven end) online, "
                . "(case s.tven when (s.tven is null) then 0 else s.tven end) venta, "
                . "(case r.tven when (r.tven is null) then 0 else r.tven end) total "
                . "FROM orden_creada ord "
                . "LEFT JOIN ( "
                    . "SELECT month(o1.fecha_registro) mes, year(o1.fecha_registro) as anio, "
                    . "SUM(o1.cantidad  * o1.precio) + shi.valor as tven "
                    . "FROM orden_creada o1 INNER JOIN shipping shi ON o1.shipping = shi.id "
                    . "WHERE o1.tipo_orden = 1 ";
                
                if($txtfechaInicio != "" && $txtfechaFin != ""){
                    $sql.= "and o1.fecha_registro >= '" .$fechaInicio[2] . "-" . $fechaInicio[1] . "-" . $fechaInicio[0] . "' and o1.fecha_registro <= '" .$fechaFin[2] . "-" . $fechaFin[1] . "-" . $fechaFin[0] . "' ";
                } else {
                    $sql.= "and o1.fecha_registro > DATE_SUB(now(), INTERVAL 6 MONTH) ";
                }                
            $sql.=  "GROUP BY month(o1.fecha_registro), year(o1.fecha_registro) "
                . ") p ON month(ord.fecha_registro) = p.mes and year(ord.fecha_registro) = p.anio "
                . "LEFT JOIN  ( "
                    . "SELECT month(o2.fecha_registro) mes, year(o2.fecha_registro) as anio, "
                    . "SUM(o2.cantidad  * o2.precio) tven "
                    . "FROM orden_creada o2     "
                    . "WHERE o2.tipo_orden = 2 ";                
                if($txtfechaInicio != "" && $txtfechaFin != ""){
                    $sql.= "and o2.fecha_registro >= '" .$fechaInicio[2] . "-" . $fechaInicio[1] . "-" . $fechaInicio[0] . "' and o2.fecha_registro <= '" .$fechaFin[2] . "-" . $fechaFin[1] . "-" . $fechaFin[0] . "' ";
                } else {
                    $sql.= "and o2.fecha_registro > DATE_SUB(now(), INTERVAL 6 MONTH) ";
                }                
            $sql.= "GROUP BY month(o2.fecha_registro), year(o2.fecha_registro) "
                . ") s ON month(ord.fecha_registro) = s.mes and year(ord.fecha_registro) = s.anio "
                . "LEFT JOIN  ( "
                    . "SELECT month(o3.fecha_registro) mes, year(o3.fecha_registro) as anio, "
                    . "SUM(o3.cantidad  * o3.precio) tven "
                    . "FROM orden_creada o3 ";                
                if($txtfechaInicio != "" && $txtfechaFin != ""){
                    $sql.= "where o3.fecha_registro >= '" .$fechaInicio[2] . "-" . $fechaInicio[1] . "-" . $fechaInicio[0] . "' and o3.fecha_registro <= '" .$fechaFin[2] . "-" . $fechaFin[1] . "-" . $fechaFin[0] . "' ";
                } else {
                    $sql.= "where o3.fecha_registro > DATE_SUB(now(), INTERVAL 6 MONTH) ";
                }                
            $sql.= "GROUP BY month(o3.fecha_registro), year(o3.fecha_registro) "
               . ") r ON month(ord.fecha_registro) = r.mes and year(ord.fecha_registro) = r.anio "; 
               
           if($txtfechaInicio != "" && $txtfechaFin != ""){
                $sql.= "where ord.fecha_registro >= '" .$fechaInicio[2] . "-" . $fechaInicio[1] . "-" . $fechaInicio[0] . "' and ord.fecha_registro <= '" .$fechaFin[2] . "-" . $fechaFin[1] . "-" . $fechaFin[0] . "' ";
            } else {
                $sql.= "where ord.fecha_registro > DATE_SUB(now(), INTERVAL 6 MONTH) ";

                $txtfechaInicio = date('d/m/Y', strtotime('now -6 months'));
                $txtfechaFin = date('d/m/Y', strtotime('now'));
            }     
                    
            $sql.= "group by month(ord.fecha_registro), year(ord.fecha_registro) "
                . "order by year(ord.fecha_registro), month(ord.fecha_registro)";

        $stmt = $em->getConnection()->prepare($sql);
        $stmt->execute();
        $row['data'] = $stmt->fetchAll();
        
        $pdf = new IngresosVentaPDF('P','mm','Letter');
        $pdf->AliasNbPages();
        $pdf->SetMargins(20, 20);
        $pdf->AddPage();
        $pdf->SetTitle('Ingresos totales por mes');
        
        $pdf->SetFont('Times','B',13);
        $pdf->Cell(180,32, 'Del periodo de '.$txtfechaInicio.' al '.$txtfechaFin, 0, 0, 'C');
        
        $pdf->Ln(25);
        $pdf->Cell(15);
        
        $data = array();
        $online = 0;
        $venta = 0;
        $total = 0;
        foreach ($row['data'] as $key => $value) {
            $data[$key][0] = $value['mes'] . ' / ' . $value['anio'];
            
            if(!is_null($value['online'])) {
                $data[$key][1] = $value['online'];
                $online+=$value['online'];
            } else {
                $data[$key][1] = '0.00';
            }
            
            if(!is_null($value['venta'])) {
                $data[$key][2] = $value['venta'];
                $venta+=$value['venta'];
            } else {
                $data[$key][2] = '0.00';
            }
            
            if(!is_null($value['total'])) {
                $data[$key][3] = $value['total'];
                $total+=$value['total'];
            } else {
                $data[$key][3] = '0.00';
            }
        }
        
        $colsWidth = array(50, 35, 35, 35);
        $header = array(utf8_decode('Mes / Año'), 'Ventas en linea ($)', 'Ventas en tienda', 'Total de ventas ($)');
        $align = array('L', 'R', 'R', 'R');
        
        $this->crearTabla($pdf, $header, $data, 4, $colsWidth, TRUE, $align, 11, 'Times', 15, 20);
        $pdf->Cell(15);
        
        $pdf->SetFont('Times', 'B', 12);
        $pdf->Cell(50, 7, 'TOTAL', 1, 0, 'L');
        $pdf->Cell(35, 7, number_format($online,2,'.',''), 1, 0, 'R');
        $pdf->Cell(35, 7, number_format($venta,2,'.',''), 1, 0, 'R');
        $pdf->Cell(35, 7, number_format($total,2,'.',''), 1, 0, 'R');        
        
        $pdf->Output();
    }
    
    /**
     * Muestra los ingresos por categoria
     *
     * @Route("/ingresos-categoria", name="admin_ingresos_venta_categoria")
        * @Method("GET")
     */
    public function ReporteIngresosVentaCategoriaAction()
    {
        $em = $this->getDoctrine()->getEntityManager();
        
        $sql = "SELECT cat.nombre categoria, "
                . "(case p.tven when (p.tven is null) then 0 else p.tven end) online, "
                . "(case s.tven when (s.tven is null) then 0 else s.tven end) venta, "
                . "(case r.tven when (r.tven is null) then 0 else r.tven end) total "
                . "FROM orden_creada ord "
                . "INNER JOIN producto pro ON ord.producto = pro.id "
                . "INNER JOIN categoria cat ON pro.categoria_id = cat.id "
                . "LEFT JOIN ( "
                    . "SELECT cat1.id catid, SUM(o1.cantidad  * o1.precio) + shi.valor as tven "
                    . "FROM orden_creada o1 INNER JOIN producto pro1 ON o1.producto = pro1.id "
                        . "INNER JOIN categoria cat1 ON pro1.categoria_id = cat1.id INNER JOIN shipping shi ON o1.shipping = shi.id "
                    . "WHERE o1.tipo_orden = 1 and o1.fecha_registro > DATE_SUB(now(), INTERVAL 6 MONTH) " 
                    . "GROUP BY cat1.id "
                . ") p ON cat.id = p.catid "
                . "LEFT JOIN  ( "
                    . "SELECT cat2.id catid, SUM(o2.cantidad  * o2.precio) tven "
                    . "FROM orden_creada o2 INNER JOIN producto pro2 ON o2.producto = pro2.id "
                        . "INNER JOIN categoria cat2 ON pro2.categoria_id = cat2.id "
                    . "WHERE o2.tipo_orden = 2 and o2.fecha_registro > DATE_SUB(now(), INTERVAL 6 MONTH) "
                    . "GROUP BY cat2.id "
                . ") s ON cat.id = s.catid "
                . "LEFT JOIN  ( "
                    . "SELECT cat3.id catid, SUM(o3.cantidad  * o3.precio) tven "
                    . "FROM orden_creada o3 INNER JOIN producto pro3 ON o3.producto = pro3.id "
                        . "INNER JOIN categoria cat3 ON pro3.categoria_id = cat3.id "
                    . "WHERE o3.fecha_registro > DATE_SUB(now(), INTERVAL 6 MONTH) "
                    . "GROUP BY cat3.id "
                . ") r ON cat.id = r.catid "
                . "where ord.fecha_registro > DATE_SUB(now(), INTERVAL 6 MONTH) "
                . "group by cat.id "
                . "order by cat.id";
        
        $stmt = $em->getConnection()->prepare($sql);
        $stmt->execute();
        $row['data'] = $stmt->fetchAll();
        
        return $this->render('reportes/ingresosventacategoria.html.twig', array(
                    'consolidado' => $row['data'],
                ));
    }
    
    /**
     * Muestra los ingresos por categoria - PDF
     *
     * @Route("/ingresos-categoria-filtro", name="admin_ingresos_venta_categoria_filtro", options={"expose"=true})
     * @Method("POST")
     */
    public function ReporteIngresosVentaCategoriaFiltroAction(Request $request)
    {
        try {
            $txtfechaInicio = $request->get("fechaInicio");
            $txtfechaFin = $request->get("fechaFin");
            
            $fechaInicio = explode('/', $txtfechaInicio);
            $fechaFin = explode('/', $txtfechaFin);
            
            $em = $this->getDoctrine()->getEntityManager();
            $response = new JsonResponse();
            $row['data']= array();
            
            $sql = "SELECT cat.nombre categoria, "
                . "(case p.tven when (p.tven is null) then 0 else p.tven end) online, "
                . "(case s.tven when (s.tven is null) then 0 else s.tven end) venta, "
                . "(case r.tven when (r.tven is null) then 0 else r.tven end) total "
                . "FROM orden_creada ord "
                . "INNER JOIN producto pro ON ord.producto = pro.id "
                . "INNER JOIN categoria cat ON pro.categoria_id = cat.id "
                . "LEFT JOIN ( "
                    . "SELECT cat1.id catid, SUM(o1.cantidad  * o1.precio) + shi.valor as tven "
                    . "FROM orden_creada o1 INNER JOIN producto pro1 ON o1.producto = pro1.id "
                        . "INNER JOIN categoria cat1 ON pro1.categoria_id = cat1.id INNER JOIN shipping shi ON o1.shipping = shi.id "
                    . "WHERE o1.tipo_orden = 1 "
                    . "and o1.fecha_registro >= '" .$fechaInicio[2] . "-" . $fechaInicio[1] . "-" . $fechaInicio[0] . "' and o1.fecha_registro <= '" .$fechaFin[2] . "-" . $fechaFin[1] . "-" . $fechaFin[0] . "' "
                    . "GROUP BY cat1.id "
                . ") p ON cat.id = p.catid "
                . "LEFT JOIN  ( "
                    . "SELECT cat2.id catid, SUM(o2.cantidad  * o2.precio) tven "
                    . "FROM orden_creada o2 INNER JOIN producto pro2 ON o2.producto = pro2.id "
                        . "INNER JOIN categoria cat2 ON pro2.categoria_id = cat2.id "
                    . "WHERE o2.tipo_orden = 2 "
                    . "and o2.fecha_registro >= '" .$fechaInicio[2] . "-" . $fechaInicio[1] . "-" . $fechaInicio[0] . "' and o2.fecha_registro <= '" .$fechaFin[2] . "-" . $fechaFin[1] . "-" . $fechaFin[0] . "' "
                    . "GROUP BY cat2.id "
                . ") s ON cat.id = s.catid "
                . "LEFT JOIN  ( "
                    . "SELECT cat3.id catid, SUM(o3.cantidad  * o3.precio) tven "
                    . "FROM orden_creada o3 INNER JOIN producto pro3 ON o3.producto = pro3.id "
                        . "INNER JOIN categoria cat3 ON pro3.categoria_id = cat3.id "
                    . "WHERE o3.fecha_registro >= '" .$fechaInicio[2] . "-" . $fechaInicio[1] . "-" . $fechaInicio[0] . "' and o3.fecha_registro <= '" .$fechaFin[2] . "-" . $fechaFin[1] . "-" . $fechaFin[0] . "' "
                    . "GROUP BY cat3.id "
                . ") r ON cat.id = r.catid "
                . "group by cat.id order by cat.id";

            $stmt = $em->getConnection()->prepare($sql);
            $stmt->execute();
            $row['data'] = $stmt->fetchAll();
            
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
     * Muestra los ingresos por categoria - PDF
     *
     * @Route("/ingresos-categoria-pdf", name="admin_ingresos_venta_categoria_pdf", options={"expose"=true})
     * @Method("GET")
     */
    public function ReporteIngresosVentaCategoriaPDFAction(Request $request)
    {
        $txtfechaInicio = $request->get("fechaInicio");
        $txtfechaFin = $request->get("fechaFin");

        $fechaInicio = explode('/', $txtfechaInicio);
        $fechaFin = explode('/', $txtfechaFin);

        $em = $this->getDoctrine()->getEntityManager();

        $row['data']= array();

        $sql = "SELECT cat.nombre categoria, "
                . "(case p.tven when (p.tven is null) then 0 else p.tven end) online, "
                . "(case s.tven when (s.tven is null) then 0 else s.tven end) venta, "
                . "(case r.tven when (r.tven is null) then 0 else r.tven end) total "
                . "FROM orden_creada ord "
                . "INNER JOIN producto pro ON ord.producto = pro.id "
                . "INNER JOIN categoria cat ON pro.categoria_id = cat.id "
                . "LEFT JOIN ( "
                    . "SELECT cat1.id catid, SUM(o1.cantidad  * o1.precio) + shi.valor as tven "
                    . "FROM orden_creada o1 INNER JOIN producto pro1 ON o1.producto = pro1.id "
                        . "INNER JOIN categoria cat1 ON pro1.categoria_id = cat1.id INNER JOIN shipping shi ON o1.shipping = shi.id "
                    . "WHERE o1.tipo_orden = 1 ";
                if($txtfechaInicio != "" && $txtfechaFin != ""){
                     $sql.= "and o1.fecha_registro >= '" .$fechaInicio[2] . "-" . $fechaInicio[1] . "-" . $fechaInicio[0] . "' and o1.fecha_registro <= '" .$fechaFin[2] . "-" . $fechaFin[1] . "-" . $fechaFin[0] . "' ";
                 } else {
                     $sql.= "and o1.fecha_registro > DATE_SUB(now(), INTERVAL 6 MONTH) ";
                 }                            
                $sql.= "GROUP BY cat1.id "
                . ") p ON cat.id = p.catid "
                . "LEFT JOIN  ( "
                    . "SELECT cat2.id catid, SUM(o2.cantidad  * o2.precio) tven "
                    . "FROM orden_creada o2 INNER JOIN producto pro2 ON o2.producto = pro2.id "
                        . "INNER JOIN categoria cat2 ON pro2.categoria_id = cat2.id "
                    . "WHERE o2.tipo_orden = 2 ";
                if($txtfechaInicio != "" && $txtfechaFin != ""){
                     $sql.= "and o2.fecha_registro >= '" .$fechaInicio[2] . "-" . $fechaInicio[1] . "-" . $fechaInicio[0] . "' and o2.fecha_registro <= '" .$fechaFin[2] . "-" . $fechaFin[1] . "-" . $fechaFin[0] . "' ";
                 } else {
                     $sql.= "and o2.fecha_registro > DATE_SUB(now(), INTERVAL 6 MONTH) ";
                 }  
                $sql.= "GROUP BY cat2.id "
                . ") s ON cat.id = s.catid "
                . "LEFT JOIN  ( "
                    . "SELECT cat3.id catid, "
                        //. "(case o3.tipo_orden when 2 then SUM(o3.cantidad  * o3.precio) else (SUM(o3.cantidad  * o3.precio) + shi3.valor) end) as tven "
                        . "SUM(o3.cantidad  * o3.precio) as tven "
                    . "FROM orden_creada o3 INNER JOIN producto pro3 ON o3.producto = pro3.id "
                        . "INNER JOIN categoria cat3 ON pro3.categoria_id = cat3.id ";
                if($txtfechaInicio != "" && $txtfechaFin != ""){
                     $sql.= "and o3.fecha_registro >= '" .$fechaInicio[2] . "-" . $fechaInicio[1] . "-" . $fechaInicio[0] . "' and o3.fecha_registro <= '" .$fechaFin[2] . "-" . $fechaFin[1] . "-" . $fechaFin[0] . "' ";
                 } else {
                     $sql.= "and o3.fecha_registro > DATE_SUB(now(), INTERVAL 6 MONTH) ";
                     
                     $txtfechaInicio = date('d/m/Y', strtotime('now -6 months'));
                     $txtfechaFin = date('d/m/Y', strtotime('now'));
                 }
                    $sql.= "GROUP BY cat3.id "
                . ") r ON cat.id = r.catid ";        
        
        $sql.= "group by cat.id order by cat.id";

        $stmt = $em->getConnection()->prepare($sql);
        $stmt->execute();
        $row['data'] = $stmt->fetchAll();
        
        $pdf = new IngresosCategoria('P','mm','Letter');
        $pdf->AliasNbPages();
        $pdf->SetMargins(20, 20);
        $pdf->AddPage();
        $pdf->SetTitle('Ingresos totales por categoria');
        
        $pdf->SetFont('Times','B',13);
        $pdf->Cell(180,32, 'Del periodo de '.$txtfechaInicio.' al '.$txtfechaFin, 0, 0, 'C');
        
        $pdf->Ln(25);
        $pdf->Cell(12);
        
        $data = array();
        $online = 0;
        $venta = 0;
        $total = 0;
        foreach ($row['data'] as $key => $value) {
            $data[$key][0] = $value['categoria'];
            
            if(!is_null($value['online'])) {
                $data[$key][1] = $value['online'];
                $online+=$value['online'];
            } else {
                $data[$key][1] = '0.00';
            }
            
            if(!is_null($value['venta'])) {
                $data[$key][2] = $value['venta'];
                $venta+=$value['venta'];
            } else {
                $data[$key][2] = '0.00';
            }
            
            if(!is_null($value['total'])) {
                $data[$key][3] = $value['total'];
                $total+=$value['total'];
            } else {
                $data[$key][3] = '0.00';
            }
        }
        
        $colsWidth = array(50, 35, 38, 35);
        $header = array(utf8_decode('Categoria de productos'), 'Ventas en linea ($)', 'Ventas en tienda ($)', 'Total de ventas ($)');
        $align = array('L', 'R', 'R', 'R');
        
        $this->crearTabla($pdf, $header, $data, 4, $colsWidth, TRUE, $align, 11, 'Times', 12, 20);
        $pdf->Cell(12);
        
        $pdf->SetFont('Times', 'B', 12);
        $pdf->Cell(50, 7, 'TOTAL', 1, 0, 'L');
        $pdf->Cell(35, 7, number_format($online,2,'.',''), 1, 0, 'R');
        $pdf->Cell(38, 7, number_format($venta,2,'.',''), 1, 0, 'R');
        $pdf->Cell(35, 7, number_format($total,2,'.',''), 1, 0, 'R');
                     
        $pdf->Output();
    }
    
    /*
     * Función la cual crea una tabla dependiendo el número columnas que se indique en donde:
     *   $pdf Hace referencia al objeto FPDF
     *   $header Array que almacena el encabezado de la tabla en el caso que lleve la tabla
     *   $data Array que almacena los registros que se mostrarán en el cuerpo de la tabla
     *   $cols Entero que contiene el número de columnas que va a tener la tabla
     *   $colWidth Array que contiene el ancho de cada columna de la tabla
     *   $tableHeader Booleano: TRUE => Si la tabla lleva encabezado, FALSE => Si la tabla no va a llevar encabezado
     *   $align Array que contiene hacia donde se alineará el texto para cada columna L => Izquierda, C => Centrado, R => Derecha, J => Justificado
     *   $fontSize Entero que contiene el tamaño de letra para el cuerpo de la tabla (El tamaño de letra para el encabezado de la tabla => 12)
     *   $font String que contiene el tipo de letra para toda la tabla
     *   $sangria Entero que almacena la separación de la tabla con respecto al margen izquierdo de la página
     *   $margin Entero que contiene el margen izquierdo de la página
     * 
     */
    function crearTabla($pdf, $header, $data, $cols ,$colWidth, $tableHeader, $align, $fontSize, $font, $sangria, $margin)
    {
        // Si va a llevar encabezado la tabla
        if ($tableHeader==true) {
            $pdf->SetFont($font, 'B', 12);
            
            // Se pinta el encabezado de la tabla
            foreach ($header as $key => $value) {
                $pdf->Cell($colWidth[$key], 7, $header[$key], 1, 0, 'C');
            }            
            
            //Salto de linea
            $pdf->Ln();
        }
        // Cuerpo de la tabla
        $pdf->SetFont($font, '', $fontSize);

        // Se obtienen las coordenadas X, Y
        $x = $pdf->GetX();
        $y = $pdf->GetY();

        // Se recorre el arreglo que contiene los registros del cuerpo de la tabla
        foreach ($data as $key => $value) {
            // Se desplaza la fila hacia la derecha dependiendo el valor de sangria
            $pdf->Cell($sangria);
            
            // Se recorren cada columna de la fila
            for($i=1; $i<=$cols; $i++){
                $pdf->MultiCell($colWidth[$i - 1], 6, $value[$i - 1], 'LRB', $align[$i - 1]);
            
                // Se actualiza la coordenada X
                if($i == 1) {
                    $x += ($colWidth[$i - 1] + $sangria);
                } else {
                    $x += $colWidth[$i - 1];
                }
                
                // Si es la última columna de la fila
                if($i == $cols) {
                    $x = $margin;
                    $y+=6;
                    $pdf->Ln();
                }
                
                // Se establecen las coordenadas XY
                $pdf->SetXY($x, $y);                                
            }                        
        }                        
    }
}


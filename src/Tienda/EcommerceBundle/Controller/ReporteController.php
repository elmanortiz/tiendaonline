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
        $sql = "select month(ord.fecha_registro) as mes, year(ord.fecha_registro) as anio, sum(ord.cantidad  * ord.precio) total_ventas "
                . "from orden_creada ord "
                . "where ord.tipo_orden = 2 "
                . "group by month(ord.fecha_registro), year(ord.fecha_registro)";
        
        $sql = "select month(ord.fecha_registro) as mes, year(ord.fecha_registro) as anio, sum(ord.cantidad  * ord.precio) total_ventas "
                . "from orden_creada ord "
                . "where ord.tipo_orden = 1 "
                . "group by month(ord.fecha_registro), year(ord.fecha_registro)";
        
        return $this->render('reportes/ingresosventa.html.twig');
    }
    
    /**
     * Muestra los ingresos por venta - PDF
     *
     * @Route("/ingresos-venta-pdf", name="admin_ingresos_venta_pdf", options={"expose"=true})
     * @Method("POST")
     */
    public function ReporteIngresosVentaPDFAction(Request $request)
    {
        $pdf = new IngresosVentaPDF();
        
    }
    
    /**
     * Muestra los ingresos por categoria
     *
     * @Route("/ingresos-categoria", name="admin_ingresos_venta_categoria")
        * @Method("GET")
     */
    public function ReporteIngresosVentaCategoriaAction()
    {
        return $this->render('reportes/ingresosventacategoria.html.twig');
    }
    
    /**
     * Muestra los ingresos por categoria - PDF
     *
     * @Route("/ingresos-categoria-pdf", name="admin_ingresos_venta_categoria_pdf", options={"expose"=true})
     * @Method("POST")
     */
    public function ReporteIngresosVentaCategoriaPDFAction(Request $request)
    {
        $pdf = new IngresosCategoria();
    }
}

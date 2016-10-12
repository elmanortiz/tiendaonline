<?php

namespace Tienda\EcommerceBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Tienda\EcommerceBundle\Entity\Carrusel;
use Tienda\EcommerceBundle\Form\CategoriaType;

/**
 * Imagenes controller.
 *
 * @Route("/admin/imagenes-gestion")
 */
class ImagenesController extends Controller{
    /**
     * Lists all Categoria entities.
     *
     * @Route("/", name="admin_imagenes_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getEntityManager();
        $em->getConnection()->beginTransaction();
        $response = new JsonResponse();
        //$imgData = $_FILES;
        $imagenesObj = $em->getRepository('TiendaEcommerceBundle:Carrusel')->findBy(array('estado'=>1));
        return $this->render('imagenes/carrusel.html.twig', array(
            'imagenesObj' => $imagenesObj,
        ));
    }

    
    /**
     * Save las categorias
     *
     * @Route("/save/imagenes-gestion", name="admin_save_imagenes", options={"expose"=true}))
     * @Method("POST")
     */
    public function saveimagenesAction(Request $request)
    {
        try {
            $id=$request->get("id");
            $response = new JsonResponse();
            $tipoImagen = $_POST['tipoimagen'];
//            var_dump($_POST);
            $idImagen = $_POST['idImagen'];
            $em = $this->getDoctrine()->getManager();
            //$categoria = $em->getRepository('TiendaEcommerceBundle:Categoria')->find($id);
            
            $data['d']="";
            
//            //var_dump($tipoImagen);
//            var_dump($_FILES);
//            die();
            $ids=array();
            //Manejo de imagen
            $nombreTmp = $_FILES['file']['name'];
            $carruselAnterior= $em->getRepository('TiendaEcommerceBundle:Carrusel')->findAll();
            $path = $this->getParameter('photo.carrusel');
//            echo $path;
//            foreach ($carruselAnterior as $key => $imagen){
//                try{
//                    unlink($path.$imagen->getNombre());
//                }
//                catch(\Exception $e){
//                    
//                }
//                $em->remove($imagen);    
//                $em->flush();
//                
//            }
            foreach ($carruselAnterior as $key => $row2){
                $row2->setEstado(0);
                $em->merge($row2);
                $em->flush();
            }
                foreach ($_FILES['file']['name'] as $key => $row){
                    if ($nombreTmp!='') {
                        $imagen= $em->getRepository('TiendaEcommerceBundle:Carrusel')->find($idImagen[$key]);
                        try{
//                            var_dump($imagen);
                            if($imagen!=null){
                                //unlink($path.$imagen->getNombre());
                                $imagen->setEstado(1);
                                $em->merge($imagen);    
                                $em->flush();
                                $idImagen[$key]=$imagen->getId();
                            }
                            else{
                                $fecha = date('Y-m-d-H-i-s');
                                $extensionTmp = $_FILES['file']['type'][$key];
                                $extensionArray= explode('/', $extensionTmp);
                                $extension = $extensionArray[1];
                                $nombreArchivo =$fecha.".".$extension;
                                //var_dump($nombreArchivo);
                                if(move_uploaded_file($_FILES['file']['tmp_name'][$key], $path.$nombreArchivo)){
                                    $carrusel=new Carrusel();
                                    $carrusel->setNombre($nombreArchivo);
                                    $carrusel->setEstado(1);
                                    $carrusel->setTipoImagen($tipoImagen);
                                    $em->persist($carrusel);
                                    $em->flush();
                                    $idImagen[$key]=$carrusel->getId();
//                                    echo $carrusel->getId();
                                }
                                else{//Error al subir foto

                                }
                            }
                            
                        }
                        catch(\Exception $e){
                        }
                        
                        //var_dump($path);
                        
                    }
                
            }
           
//            die();
            //for
            $data['ids']=$idImagen;
            $data['msg']="Imágenes guardadas!";
            
                        
            $response->setData($data); 
            
        } catch (\Exception $e) {
            var_dump($e);
            if(method_exists($e,'getErrorCode')){
                switch (intval($e->getErrorCode()))
                    {
                        case 2003: 
                            $data['error'] = $this->getParameter('app.serverOffline');
                        break;
                        default :
                            $data['error'] = $e->getMessage();                     
                        break;
                    }      
                }
                $response->setData($data);
            }
        
        return $response;
        
    }
    
    
}
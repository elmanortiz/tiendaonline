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
     * @Route("/carrusel", name="admin_imagenes_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getEntityManager();
        $em->getConnection()->beginTransaction();
        //$response = new JsonResponse();
        //$imgData = $_FILES;
        $imagenesObj = $em->getRepository('TiendaEcommerceBundle:Carrusel')->findBy(array('estado'=>1,'tipoimagen'=>1));
        
        return $this->render('imagenes/carrusel.html.twig', array(
            'imagenesObj' => $imagenesObj,
        ));
    }
    
    
    /**
     * Lists all Categoria entities.
     *
     * @Route("/categorias", name="admin_imagenes_categorias_index")
     * @Method("GET")
     */
    public function categoriasimAction()
    {
        $em = $this->getDoctrine()->getEntityManager();
        $em->getConnection()->beginTransaction();
        //$response = new JsonResponse();
        //$imgData = $_FILES;
        $imagenesObj = $em->getRepository('TiendaEcommerceBundle:Carrusel')->findBy(array('estado'=>1,'tipoimagen'=>2));
        //var_dump($imagenesObj);
        return $this->render('imagenes/categoriaimagen.html.twig', array(
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
            //var_dump($_POST);
            $idImagen = $_POST['idImagen'];
            $em = $this->getDoctrine()->getManager();
            //$categoria = $em->getRepository('TiendaEcommerceBundle:Categoria')->find($id);
            
            $data['d']="";
            
//var_dump($tipoImagen);
//            var_dump($_FILES);
//            die();
            $ids=array();
            //Manejo de imagen
            $nombreTmp = $_FILES['file']['name'];
            //$carruselAnterior= $em->getRepository('TiendaEcommerceBundle:Carrusel')->findAll();
            $carruselAnterior= $em->getRepository('TiendaEcommerceBundle:Carrusel')->findBy(array('tipoimagen'=>$tipoImagen));
            
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
                $row2->setEstado(false);
                $em->merge($row2);
                $em->flush();
            }
                foreach ($_FILES['file']['name'] as $key => $row){
                    $imagen= $em->getRepository('TiendaEcommerceBundle:Carrusel')->find($idImagen[$key]);
                    
                    if ($nombreTmp!='') {
                        echo "if nombre";
                        
                        try{
//                            var_dump($imagen);
                            $fecha = date('Y-m-d-H-i-s');
                            $extensionTmp = $_FILES['file']['type'][$key];
                            $extensionArray= explode('/', $extensionTmp);
//                            var_dump($extensionTmp);
                            $extension = $extensionArray[1];
                            $nombreArchivo =$key.$fecha.".".$extension;
                            if($imagen!=null){
                                //unlink($path.$imagen->getNombre());
                                if($tipoImagen!=2){
                                    echo "imagen tipo 1";
                                    $imagen->setEstado(1);
                                    $imagen->setNombre($key.$nombreArchivo);
                                    $em->merge($imagen);    
                                    $em->flush();
                                }
//                                else{
//                                    $imagen->setEstado(1);
//                                    $imagen->setNombre($key.$nombreArchivo);
//                                    $em->persist($imagen);    
//                                    $em->flush();
//                                }
                                $idImagen[$key]=$imagen->getId();
                                if(move_uploaded_file($_FILES['file']['tmp_name'][$key], $path.$nombreArchivo)){
                                    $carrusel=new Carrusel();
                                    $carrusel->setNombre($carrusel->getId().$nombreArchivo);
                                    $carrusel->setEstado(1);
                                    $carrusel->setTipoImagen($tipoImagen);
                                    $em->persist($carrusel);
                                    $em->flush();
                                    $idImagen[$key]=$carrusel->getId();
//                                    echo $carrusel->getId();
                                }
                            }
                            else{
                                echo "imagen tipo 1";
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
                            echo $e->getMessage();
                            echo $e->getLine();
                        }
                        
                        //var_dump($path);
                        
                    }
                    else{
                        $imagen= $em->getRepository('TiendaEcommerceBundle:Carrusel')->find($idImagen[$key]);
                        $imagen->setEstado(true);
                        $em->merge($imagen);    
                        $em->flush();
                    }
                
            }
           
//            die();
            //for
            $data['ids']=$idImagen;
            $data['msg']="Imágenes guardadas!";
            
                        
            $response->setData($data); 
            
        } catch (\Exception $e) {
            echo $e->getMessage();
                            echo $e->getLine();
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
    
    
    
    
    
    /**
     * @Route("/ingresar_empresa_persona/get", name="ingresar_foto_persona", options={"expose"=true})
     * @Method("POST")
     */
    public function RegistrarFotoAction(Request $request) {
            //data es el valor de retorno de ajax donde puedo ver los valores que trae dependiendo de las instrucciones que hace dentro del controlador
           
            $nombreimagen2=" ";
            $idConsulta = $request->get('id');
            $dataForm = $request->get('frm');
            
            $tipoImagen = $_POST["tipo"];
            
            
            
            //var_dump($idConsulta);
            
//            var_dump(count($_FILES['file']['name']));
            $em = $this->getDoctrine()->getManager();
            //$consulta = $em->getRepository('TiendaEcommerceBundle:Carrusel')->find($idConsulta);
//            $totalImagen = $em->getRepository('TiendaEcommerceBundle:Carrusel')->findBy(array('consulta'=>$idConsulta));
//            var_dump($totalImagen);
            $arr = Array();
//            for($i=0;$i<count($_FILES['file']['name']);$i++){
                //$nombreimagen=$_FILES['file']['name'][$i];    
                $nombreimagen=$_FILES['file']['name'];

                


                //$tipo = $_FILES['file']['type'][$i];
                $tipo = $_FILES['file']['type'];
                $extension= explode('/',$tipo);
                $nombreimagen2.=".".$extension[1];
            
                if ($nombreimagen != null){
                    
                    $imagen = new Carrusel();
                    
                    
//                    die();
//                    $imagen->setConsulta($consulta);
                    
                    
                    //Direccion fisica del la imagen  
                    $path1 = $this->container->getParameter('photo.carrusel');

                    $path = "Photos/perfil/E";
                    $fecha = date('Y-m-d His');

                    $nombreArchivo = "-".$fecha.$nombreimagen2;

                    $nombreBASE=$path.$nombreArchivo;
                    $nombreBASE=str_replace(" ","", $nombreBASE);
                    $nombreSERVER =str_replace(" ","", $nombreArchivo);
                    $imagen->setNombre($nombreSERVER);
                    $imagen->setEstado(1);
                    $imagen->setTipoImagen($tipoImagen);
                    //$resultado = move_uploaded_file($_FILES["file"]["tmp_name"][$i], $path1.$nombreSERVER);
                    $resultado = move_uploaded_file($_FILES["file"]["tmp_name"], $path1.$nombreSERVER);
                    $em->persist($imagen);
                    $em->flush();
                    $arregloim=Array();

                    if ($resultado){
                        //array_push($arregloim, count($totalImagen));
                        array_push($arregloim, $imagen->getId());
                        array_push($arregloim, $imagen->getNombre());
                        array_push($arr, $arregloim);
                    }else{
                        array_push($arr, 0);
//                        $data['servidor'] = "No se pudo mover la imagen al servidor";
//                        $data['servidor'] = "No se pudo mover la imagen al servidor";
                    }
                }
                else{
                    //$data['imagen'] = "Imagen invalida";


                }
//            }

//            return $this->redirect($this->generateUrl('admin_imagenes_index'));
            return new Response(json_encode($arr));
            

      
            
    }
    
    
}

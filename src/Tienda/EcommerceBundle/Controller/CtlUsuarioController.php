<?php

namespace Tienda\EcommerceBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Tienda\EcommerceBundle\Entity\CtlUsuario;
use Tienda\EcommerceBundle\Form\CtlUsuarioType;

/**
 * CtlUsuario controller.
 *
 * @Route("/admin/usuario")
 */
class CtlUsuarioController extends Controller
{
    /**
     * Lists all CtlUsuario entities.
     *
     * @Route("/", name="admin_usuario_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $ctlUsuarios = $em->getRepository('TiendaEcommerceBundle:CtlUsuario')->findAll();

        return $this->render('ctlusuario/index.html.twig', array(
            'ctlUsuarios' => $ctlUsuarios,
        ));
    }

    /**
     * Creates a new CtlUsuario entity.
     *
     * @Route("/new", name="admin_usuario_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $ctlUsuario = new CtlUsuario();
        $form = $this->createForm('Tienda\EcommerceBundle\Form\CtlUsuarioType', $ctlUsuario);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            //establecemos la contraseña: --------------------------
            $this->setSecurePassword($ctlUsuario);
            
            $em = $this->getDoctrine()->getManager();
            $em->persist($ctlUsuario);
            $em->flush();

            return $this->redirectToRoute('admin_usuario_show', array('id' => $ctlUsuario->getId()));
        }

        return $this->render('ctlusuario/new.html.twig', array(
            'ctlUsuario' => $ctlUsuario,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a CtlUsuario entity.
     *
     * @Route("/{id}", name="admin_usuario_show")
     * @Method("GET")
     */
    public function showAction(CtlUsuario $ctlUsuario)
    {
        $deleteForm = $this->createDeleteForm($ctlUsuario);

        return $this->render('ctlusuario/show.html.twig', array(
            'ctlUsuario' => $ctlUsuario,
            'delete_form' => $deleteForm->createView(),
        ));
    }
    
    /**
     * Formulario para cambiar contraseña
     *
     * @Route("/{id}/cambio-password", name="admin_cambio_password")
     * @Method("GET")
     */
    public function cambioPasswordAction(CtlUsuario $ctlUsuario)
    {
    
        return $this->render('ctlusuario/cambioPassword.html.twig', array(
            'ctlUsuario' => $ctlUsuario,
        ));
    }

    /**
     * Displays a form to edit an existing CtlUsuario entity.
     *
     * @Route("/{id}/edit", name="admin_usuario_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, CtlUsuario $ctlUsuario)
    {
        $deleteForm = $this->createDeleteForm($ctlUsuario);
        $editForm = $this->createForm('Tienda\EcommerceBundle\Form\CtlUsuarioType', $ctlUsuario);
        $editForm->handleRequest($request);
        
        //obtiene la contraseña actual -----------------------
        $current_pass = $ctlUsuario->getPassword();

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            
             //evalua si la contraseña se encuentra vacia
            if($ctlUsuario->getPassword()==""){
                $ctlUsuario->setPassword($current_pass);
            }
            
            //evalua si la contraseña fue modificada: ------------------------
            if ($current_pass != $ctlUsuario->getPassword()) {
                $this->setSecurePassword($ctlUsuario);
            }
            
            $em->persist($ctlUsuario);
            $em->flush();

            return $this->redirectToRoute('admin_usuario_edit', array('id' => $ctlUsuario->getId()));
        }

        return $this->render('ctlusuario/edit.html.twig', array(
            'ctlUsuario' => $ctlUsuario,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a CtlUsuario entity.
     *
     * @Route("/{id}", name="admin_usuario_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, CtlUsuario $ctlUsuario)
    {
        $form = $this->createDeleteForm($ctlUsuario);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($ctlUsuario);
            $em->flush();
        }

        return $this->redirectToRoute('admin_usuario_index');
    }

    /**
     * Creates a form to delete a CtlUsuario entity.
     *
     * @param CtlUsuario $ctlUsuario The CtlUsuario entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(CtlUsuario $ctlUsuario)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_usuario_delete', array('id' => $ctlUsuario->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
    
    private function setSecurePassword(&$entity) {
        $entity->setSalt(md5(time()));
        $encoder = new \Symfony\Component\Security\Core\Encoder\MessageDigestPasswordEncoder('sha512', true, 10);
        $password = $encoder->encodePassword($entity->getPassword(), $entity->getSalt());
        $entity->setPassword($password);
    }
    
    private function evaluatePassword(&$entity, $contrasenia) {
        $entity->setSalt(md5(time()));
        $encoder = new \Symfony\Component\Security\Core\Encoder\MessageDigestPasswordEncoder('sha512', true, 10);
        $password = $encoder->encodePassword($contrasenia, $entity->getSalt());
        $entity->setPassword($password);
    }
    
    /**
     * Actualizacion de nueva contraseña para el usuario
     * 
     * @Route("/cambiar-passw/", name="admin_cambio_passw", options={"expose"=true})
     * @Method("POST")
     */
    public function CambiarPassw(Request $request) {

        $isAjax = $this->get('Request')->isXMLhttpRequest();

        if ($isAjax) {
            try {
                $em = $this->getDoctrine()->getManager();
                $passwActual = $request->get('passwActual');
                $passwNva = $request->get('passwNva');

                $usuario = $this->container->get('security.context')->getToken()->getUser();
                $salt = $usuario->getSalt();
    //            $current_pass = $usuario->getPassword();
                $encoder = new \Symfony\Component\Security\Core\Encoder\MessageDigestPasswordEncoder('sha512', true, 10);
                $password = $encoder->encodePassword($passwActual, $salt);

                $entity = $em->getRepository('TiendaEcommerceBundle:CtlUsuario')->findBy(array('password'=>$password));

                if(count($entity)==1){
                    $this->evaluatePassword($usuario, $passwNva);
                    $em->merge($usuario);
                    $em->flush();

                    $serverUpdate = $this->getParameter('app.serverMsgPasswUpdate');
                    $data['msg']=$serverUpdate; 
                }
                else{
                    $data['error'] = $this->getParameter('app.serverPasswIncorrecto');   
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
}

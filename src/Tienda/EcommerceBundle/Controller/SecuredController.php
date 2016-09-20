<?php

namespace Tienda\EcommerceBundle\Controller;


use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\SecurityContextInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Tienda\EcommerceBundle\Entity\CtlUsuario;
use Tienda\EcommerceBundle\Controller\UsuarioController;

/**
 * @Route("/secured")
 */
class SecuredController extends Controller
{
    /**
     * @Route("/login", name="admin_login" , options={"expose"=true})
     * @Template()
     */ 
    public function loginAction(Request $request)
    {
        if ($this->container->get('security.context')->isGranted('IS_AUTHENTICATED_FULLY')) {
            return $this->redirect($this->generateUrl('admin_dashboard'));
        } else {

            $session = $request->getSession();
            if (class_exists('\Symfony\Component\Security\Core\Security')) {
                $authErrorKey = Security::AUTHENTICATION_ERROR;
                $lastUsernameKey = Security::LAST_USERNAME;
            } else {
                // BC for SF < 2.6
                $authErrorKey = SecurityContextInterface::AUTHENTICATION_ERROR;
                $lastUsernameKey = SecurityContextInterface::LAST_USERNAME;
            }
            // get the error if any (works with forward and redirect -- see below)
            if ($request->attributes->has($authErrorKey)) {
                $error = $request->attributes->get($authErrorKey);
            } elseif (null !== $session && $session->has($authErrorKey)) {
                $error = $session->get($authErrorKey);
                $session->remove($authErrorKey);
            } else {
                $error = null;
            }
            if (!$error instanceof AuthenticationException) {
                $error = null; // The value does not come from the security component.
            }
            // last username entered by the user
            $lastUsername = (null === $session) ? '' : $session->get($lastUsernameKey);



            return $this->render('secured/login.html.twig', array(
                'last_username' => $lastUsername,
                'error' => $error,

            ));
        }   
    }
    
    /**
     * @Route("/login_check", name="admin_security_check", options={"expose"=true})
     */
    public function securityCheckAction(Request $request)
    {        
        // The security layer will intercept this request            
    }
    
    /**
     * @Route("/logout", name="admin_logout" , options={"expose"=true})
     */
    public function logoutAction()
    {
        // The security layer will intercept this request
    }
    
    function generateRandomString($length) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        
        return $randomString;
    }
    
    private function setSecurePassword(&$entity, $contrasenia) {
        $entity->setSalt(md5(time()));
        $encoder = new \Symfony\Component\Security\Core\Encoder\MessageDigestPasswordEncoder('sha512', true, 10);
        $password = $encoder->encodePassword($contrasenia, $entity->getSalt());
        $entity->setPassword($password);
    } 
    
    private function authenticateUser(\ERP\CRMBundle\Entity\CtlUsuario $user)
    {
        $providerKey = 'secured_area_'; // your firewall name
        $token = new UsernamePasswordToken($user, null, $providerKey, $user->getRoles());

        $this->container->get('security.context')->setToken($token);
    }
}

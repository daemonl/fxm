<?php

namespace Rebase\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\SecurityContext;

class SecurityController extends Controller
{
    
    public function loginAction()
    {
        $request = $this->getRequest();
        $session = $request->getSession();
               
 
        // get the login error if there is one
        if ($request->attributes->has(SecurityContext::AUTHENTICATION_ERROR)) {
            $error = $request->attributes->get(SecurityContext::AUTHENTICATION_ERROR);
        } else {
            $error = $session->get(SecurityContext::AUTHENTICATION_ERROR);
        }
        
        return $this->render('RebaseUserBundle:Secured:Login.html.twig', array(
            // last username entered by the user
            'last_username' => $session->get(SecurityContext::LAST_USERNAME),
            'error'         => $error,
        ));
    }
    public function createAction(Request $request)
    {
      $user = new \Rebase\UserBundle\Entity\User();
      
      $form = $this->createFormBuilder($user)
                   ->add('email', 'email')
                   ->add('password', 'repeated', array(
                    'type' => 'password',
                    'invalid_message' => 'The password fields must match.',
                    'options' => array('label' => 'Password'),
                    ))
                   ->getForm();
      if ($request->getMethod() == 'POST')
    {
      $form->bindRequest($request);
      if ($form->isValid()){
        $em = $this->getDoctrine()->getEntityManager();
        $em->persist($user);
        $em->flush();
        $this->get('session')->setFlash('good', "Your account been created. Please Log in.");
        return $this->redirect($this->generateUrl("login"));
      }else{
        $this->get('session')->setFlash('bad', 'Your account could not be created - please check below for errors.');
      }
    }
      return $this->render('RebaseUserBundle:Secured:Create.html.twig', array('form'=>$form->createView()));
    }
}

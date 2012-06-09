<?php

namespace Rebase\BigvBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;


class DefaultController extends _Season
{
    
    public function indexAction()
    {
      $em = $this->getDoctrine()->getEntityManager();
      
      $user = $this->get('security.context')->getToken()->getUser();
          
      $query = $em->createQueryBuilder()
              ->add('select', 'permission')
              ->add('from', 'RebaseBigvBundle:UserPermission permission')
              ->add('where', 'permission.userID = ?1')
              ->setParameter(1, $user->getId());
      
      $UserPermissions = $query->getQuery()->getResult();
      return $this->render('RebaseBigvBundle:Default:home.html.twig', array('permissions'=>$UserPermissions));
    }
}

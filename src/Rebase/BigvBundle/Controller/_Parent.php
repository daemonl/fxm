<?php


namespace Rebase\BigvBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

use Doctrine\ORM\NoResultException;
use Doctrine\ORM\Query;

use Symfony\Component\DependencyInjection\ContainerInterface;

use Rebase\BigvBundle\Statics\Context;

class _Parent extends Controller{

  public function singleResultOr404(Query $query)
  {
    try {
        return $query->getSingleResult();
      } catch (NoResultException $e) {
        throw $this->createNotFoundException();
       // echo "404 - No Results for Query";
       // die();
      }
  }
  public function singleResultOrNull(Query $query)
  {
    try {
        return $query->getSingleResult();
      } catch (NoResultException $e) {
        return null;
      }
  }
  public function flash($type, $message)
  {
    $this->get('session')->setFlash($type, $message);
  }
  
  public function rejectContext()
  {
     echo $this->redirect($this->generateUrl('_RBV_context_league'));
     die();
  }
  
  public function setLeague($newId)
  {
    $em = $this->getDoctrine()->getEntityManager();
    $session = $this->getRequest()->getSession();
     
    $qb = $em->createQueryBuilder();
    
    $qb->add('select', 'league')
            ->add('from', 'RebaseBigvBundle:League league')
            ->add('where', 'league.id=?1')
            ->setParameter(1, $newId);
    
    $league = $this->singleResultOrNull($qb->getQuery());
    if ($league != null)
    {
      $session->set('league', $league->getId());
      context::$league = $league;
    }
  }
  
  public function setSeason($newId)
  {
    $em = $this->getDoctrine()->getEntityManager();
    $session = $this->getRequest()->getSession();
     
    $qb = $em->createQueryBuilder();
    
    $qb->add('select', 'season')
            ->add('from', 'RebaseBigvBundle:Season season')
            ->add('where', 'season.id=?1')
            ->setParameter(1, $newId);
    
    $season = $this->singleResultOrNull($qb->getQuery());
    if ($season != null)
    {
      $session->set('season', $season->getId());
      $session->set('league', $season->getLeague()->getId());
      context::$season = $season;
      context::$league = $season->getLeague();
    }
  }

  
  public function doForm(Request $request, $pageTitle, $submit, \Symfony\Component\Form\Form $form)
  {
    	if ($request->getMethod() == 'POST') {
			$form->bindRequest($request);
			  if ($form->isValid()) {
				    $em = $this->getDoctrine()->getEntityManager();
            $em->persist($form->getData());
            $em->flush();
            return true;
			}
   }
    return $this->render('RebaseBigvBundle:Generic:form.html.twig', array('form'=>$form->createView(), 'pageTitle'=>$pageTitle, 'submitLabel'=>$submit));
  }
}

?>

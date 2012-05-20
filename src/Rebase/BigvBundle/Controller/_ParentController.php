<?php


namespace Rebase\BigvBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

use Doctrine\ORM\NoResultException;
use Doctrine\ORM\Query;

use Symfony\Component\DependencyInjection\ContainerInterface;

use Rebase\BigvBundle\Statics\Context;

class _ParentController extends Controller{

  //USE THIS INSTEAD OF CONSTRUCT, as 
  public function setContainer(ContainerInterface $container = null)
  {
    $this->container = $container;
    $session = $this->getRequest()->getSession();
    
    $this->setLeague($session->get('league'));
    $this->setSeason($session->get('season'));
  }
  
  public function singleResultOr404(Query $query)
  {
    try {
        return $query->getSingleResult();
      } catch (NoResultException $e) {
        echo "404 - No Results for Query";
        die();
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
      context::$season = $season;
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
  
   function getOldForPersistCollection($old)
  {
    $arr = array();
    foreach ($old as $o)
    {
      $arr[] = $o;
    }
    return $arr;
  }
  function getDeleteListForPersistCOllection($old, $new)
  {
    $em = $this->getDoctrine()->getEntityManager();
     foreach ($new as $n)
        {
          foreach ($old as $key => $o)
          {
            if ($o->getId() === $n->getId())
            {
              unset($old[$key]);
            }
          }
        }
        
        return $old;
     
  }
}

?>

<?php


namespace Rebase\BigvBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

use Doctrine\ORM\NoResultException;
use Doctrine\ORM\Query;

use Symfony\Component\DependencyInjection\ContainerInterface;

use Rebase\BigvBundle\Statics\Context;

class _Season extends _Parent {

  protected $season;  
  protected $league;
  //USE THIS INSTEAD OF CONSTRUCT
  public function setContainer(ContainerInterface $container = null)
  {
    $this->container = $container;
    $session = $this->getRequest()->getSession();
    
    $em = $this->getDoctrine()->getEntityManager();
    $session = $this->getRequest()->getSession();
     
    $qb = $em->createQueryBuilder();
    
    $qb->add('select', 'season')
       ->add('from', 'RebaseBigvBundle:Season season')
       ->add('where', 'season.league=?1 AND season.id=?2')
       ->setParameter(1, $session->get('league'))
       ->setParameter(2, $session->get('season'));
    
    $season = $this->singleResultOrNull($qb->getQuery());
   
    if ($season == null)
    {
     $this->rejectContext();
    }
    Context::$season = $season;
    Context::$league = $season->getLeague();
    $this->season = Context::$season;
    $this->league = Context::$league;
  }
}

?>

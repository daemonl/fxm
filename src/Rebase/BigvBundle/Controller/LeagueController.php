<?php

namespace Rebase\BigvBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

use Rebase\BigvBundle\Entity\League;
use Rebase\BigvBundle\Entity\Season;

use Rebase\BigvBundle\Statics\Context;

use Rebase\BigvBundle\Common\BasicFunctions;

class LeagueController extends _Parent
{
  public function chooseLeagueAction()
  {
     $em = $this->getDoctrine()->getEntityManager();
    $qb = $em->createQueryBuilder()
            ->add('select', 'up')
            ->add('from', 'RebaseBigvBundle:UserPermission up')
            ->add('where', 'up.userID = ?1')
            ->setParameter(1, $this->get('security.context')->getToken()->getUser()->getId());
    $query = $qb->getQuery();
    $permissions = $query->getResult();

    return $this->render('RebaseBigvBundle:League:chooseLeague.html.twig', array('permissions'=>$permissions));
  }
  
  private function loadLeague($leagueID)
  {
    $session = $this->getRequest()->getSession();
    $em = $this->getDoctrine()->getEntityManager();
    $qb = $em->createQueryBuilder();

    $qb->add('select', 'league')
      ->add('from', 'RebaseBigvBundle:League league')
      ->add('where', 'league.id=?1')
      ->setParameter(1, $leagueID);
    
    $league = $this->singleResultOrNull($qb->getQuery());
    if ($league == null)
    {
      $session->set('league', 0);
      $session->set('season', 0);
      return $this->rejectContext();
    }
    $session->set('league', $league->getId());
    return $league;
  }
        
  public function setLeagueAction($leagueID)
  {
    $session = $this->getRequest()->getSession();
    $session->set('league', 0);
    $session->set('season', 0);
    
    $this->loadLeague($leagueID);

    return $this->redirect($this->generateUrl('_RBV_context_season'));
  }
    public function chooseSeasonAction()
    { 
      $session = $this->getRequest()->getSession();
      $league = $this->loadLeague($session->get('league'));
      $session->set('season', 0);
      return $this->render('RebaseBigvBundle:League:chooseSeason.html.twig', array('league'=>$league));
    }
    
    public function setSeasonAction($seasonID)
    {
      $session = $this->getRequest()->getSession();
             
      $em = $this->getDoctrine()->getEntityManager();
      
      $qb = $em->createQueryBuilder();
    
      $qb->add('select', 'season')
        ->add('from', 'RebaseBigvBundle:Season season')
        ->add('where', 'season.league=?1 AND season.id=?2')
        ->setParameter(1, $session->get('league'))
        ->setParameter(2, $seasonID);

      $season = $this->singleResultOrNull($qb->getQuery());
      if ($season == null)
      {

        $this->rejectContext();
      }
      $session->set('season', $season->getId());
      return $this->redirect($this->generateUrl('_RBV_home'));
    }
    

    public function editLeagueAction(Request $request, $leagueID)
    {

      if ($leagueID == 0)
      {
        $league = new League();
      }else{
        $em = $this->getDoctrine()->getEntityManager();
        $qb = $em->createQueryBuilder()
                ->add('select', 'l')
                ->add('from', 'RebaseBigvBundle:League l')
                ->add('where', 'l.id = ?1')
                ->setParameter(1, $leagueID);
        $query = $qb->getQuery();
        $league = $query->getSingleResult();
      }
      

	    $form = $this->createFormBuilder($league)
         ->add('shortname')
         ->add('name')
         ->getForm();

		if ($request->getMethod() == 'POST') {
			$form->bindRequest($request);
			if ($form->isValid()) {
				    $em = $this->getDoctrine()->getEntityManager();
            
            $user = $this->get('security.context')->getToken()->getUser();
             
             
            $permission = new \Rebase\BigvBundle\Entity\UserPermission();
            $permission->setUserID($user->getId());
      			$permission->setLeague($league);
            $em->persist($league);
            $em->persist($permission);
      			$em->flush();
      			return $this->redirect($this->generateUrl('_RBV_league_index', array('leagueID'=>$league->getId())));
			}
		}
        
		return $this->render('RebaseBigvBundle:League:edit.html.twig', array(
          	'form' => $form->createView(),
            'league'=>$league));
    }
    
    public function editSeasonAction(Request $request, $seasonID)
    {
      
     if ($seasonID == 0)
     {
       $season = new Season();
       $season->setLeague(context::$league);
       $f_title="New Season";
       $f_action = "Create";
     }else{
       $this->setSeason($seasonID);
       $season=context::$season;
       $f_title="Edit Season {$season->getName()}";
       $f_action = "Save";
     }
	    $form = $this->createFormBuilder($season)
         ->add('shortname', 'text', array('label'=>'Short Name'))
         ->add('name', 'text', array('label'=>'Official Name'))
         ->add('start', 'datetime', array('label'=>'Season Start'))
         ->add('stop', 'datetime', array('label'=>'Season End'))
         ->getForm(); 
      
      $f = $this->doForm($request, $f_title, $f_action, $form);
      
      if ($f === true)
      {
        return $this->redirect($this->generateUrl('_RBV_season_index', array('seasonID'=>$season->getId())));
      }else{
        return $f;
      }
    }
}

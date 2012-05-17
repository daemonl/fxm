<?php

namespace Rebase\BigvBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

use Rebase\BigvBundle\Entity\League;
use Rebase\BigvBundle\Entity\Season;

use Rebase\BigvBundle\Statics\Context;

use Rebase\BigvBundle\Common\BasicFunctions;

class LeagueController extends _ParentController
{
    public function indexAction($leagueID)
    { 
      if ((context::$league == null) OR (context::$league->getId() != $leagueID))
      {
        $this->setLeague($leagueID);
      }

      $league = context::$league;
      return $this->render('RebaseBigvBundle:League:index.html.twig', array('league'=>$league));
    }

    public function createAction(Request $request)
    {

      $league = new League();
      $league->setName("New League");

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
    
    public function seasonIndexAction($seasonID)
    {
      if ((context::$season == null) OR (context::$season->getId() != $seasonID))
      {
        $this->setSeason($seasonID);
      }
      
       return $this->render('RebaseBigvBundle:League:season.html.twig');
    }
}

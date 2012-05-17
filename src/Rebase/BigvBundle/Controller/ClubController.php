<?php
namespace Rebase\BigvBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

use Rebase\BigvBundle\Entity\Club;
use Rebase\BigvBundle\Entity\Team;
use Rebase\BigvBundle\Entity\VenueTeamLink;


class ClubController extends Controller
{
  public function indexAction()
  {
    $em = $this->getDoctrine()->getEntityManager();
    $clubs = $em->getRepository('RebaseBigvBundle:Club')->findAll();
    return $this->render('RebaseBigvBundle:Club:index.html.twig', array('clubs'=> $clubs));
  }
    
	
  public function editAction(Request $request, $clubID)
  {
    $em = $this->getDoctrine()->getEntityManager();
    
    if ($clubID != 0)
    {
      $new = false;
      $club = $em->getRepository('RebaseBigvBundle:Club')->findOneById($clubID);
    }else{
      $new = true;
      $club = new Club();
      $club->setName("New Club");
    }
      $form = $this->createFormBuilder($club)
            ->add('name', 'text')
            ->add('shortname', 'text')
              ->add('venue', 'entity', array(
              'class' => 'RebaseBigvBundle:Venue'
            ))
            ->getForm();

		if ($request->getMethod() == 'POST') {
			  $form->bindRequest($request);
			  if ($form->isValid()) {
				  $em = $this->getDoctrine()->getEntityManager();
      	  $em->persist($club);
      	  $em->flush();
      	  //ViewVenue
      	  return $this->redirect($this->generateUrl('_RBV_club_home', array('clubID'=>$club->getId())));
		    }
      }
		  return $this->render('RebaseBigvBundle:Club:EditClub.html.twig', array('club' => $club , 'form' => $form->createView(), 'new'=>$new));
    }
    
    public function homeAction($clubID)
    {
      $em = $this->getDoctrine()->getEntityManager();
      $club = $em->getRepository('RebaseBigvBundle:Club')->findOneById($clubID);
      $divisions = $em->getRepository('RebaseBigvBundle:Division')->findAll();
	  $rDivs = array();
	  foreach($divisions as $d)
	  {
		  $tf = new \Rebase\BigvBundle\Common\Classes\TeamForm();
		  $tf->Division = $d;
		  $t = $em->getRepository('RebaseBigvBundle:Team')->findOneBy(array('club' => $club->getID(), 'division' => $d->getId()));
		  if ($t)
		  {
			$tf->Exists = 1;
			$tf->Team = $t;
      $links = $em->getRepository('RebaseBigvBundle:VenueTeamLink')->findBy(array('team' => $t->getId()));
      $tf->Venues = array();
      $NEWVenue = new VenueTeamLink();
      $NEWVenue->setTeam($t);
      $tf->Form = $this->createFormBuilder($NEWVenue)
            ->add('venue', 'entity', array(
              'class' => 'RebaseBigvBundle:Venue'
            ))
            ->getForm()->createView();
      foreach ($links as $l)
      {
        $tf->Venues[] = $l;
      }
		  }else{
			  $tf->Exists = 0;  
		  }
		  $rDivs[] = $tf;
	  }
      return $this->render('RebaseBigvBundle:Club:Home.html.twig', array('club' => $club, 'teams' => $rDivs));
    }
    
    public function addTeamAction(Request $request, $clubID, $divisionID)
    {
      $em = $this->getDoctrine()->getEntityManager();
      $club = $em->getRepository('RebaseBigvBundle:Club')->findOneById($clubID);
      $team = new Team();
      $division = $em->getRepository('RebaseBigvBundle:Division')->findOneById($divisionID);
      $team->setDivision($division);
      $team->setClub($club);
      $VTL = new VenueTeamLink();
      $VTL->setTeam($team);
      $VTL->setVenue($club->getVenue());
      $team->addVenueTeamLink($VTL);
      
      $club->addTeam($team);
      $em->persist($team);
      $em->persist($VTL);
      $em->flush();
      
      return $this->redirect($this->generateUrl('_RBV_club_home', array('clubID'=>$clubID)));
    }
    
    public function removeTeamAction(Request $request, $clubID, $teamID)
    {
      $em = $this->getDoctrine()->getEntityManager();
      $team = $em->getRepository('RebaseBigvBundle:Team')->findOneById($teamID);
      $links = $em->getRepository('RebaseBigvBundle:VenueTeamLink')->findByTeam($team->getId());
	  //$games = $em->getRepository('RebaseBigvBundle:Game')->findByHomeTeam($team->getId());
	  if (count($team->getHomeGames()) + count($team->getAwayGames()) > 0)
	  {
		  $this->get('session')->setFlash('error', 'This team has games. Please remove all games first.');
		  return $this->redirect($this->generateUrl('_RBV_club_home', array('clubID'=>$clubID)));
	  }
	  foreach ($links as $link)
	  {
        $em->remove($link);
	  }
      $em->remove($team);
      $em->flush();
      return $this->redirect($this->generateUrl('_RBV_club_home', array('clubID'=>$clubID)));
    }
    
    public function addVenueToTeamAction(Request $request, $clubID, $teamID)
    {
      $em = $this->getDoctrine()->getEntityManager();
      $team = $em->getRepository('RebaseBigvBundle:Team')->findOneById($teamID);
      $NEWVenue = new VenueTeamLink();
      $NEWVenue->setTeam($team);
      $form = $this->createFormBuilder($NEWVenue)
            ->add('venue', 'entity', array(
              'class' => 'RebaseBigvBundle:Venue'
            ))
            ->getForm();
      $form->bindRequest($request);
      if ($form->isValid()) {
          
      	  $em->persist($NEWVenue);
      	  $em->flush();
      }
       return $this->redirect($this->generateUrl('_RBV_club_home', array('clubID'=>$clubID)));
    }
    
    public function removeVenueFromTeamAction(Request $request, $clubID, $linkID)
    {
      $em = $this->getDoctrine()->getEntityManager();
      $link = $em->getRepository('RebaseBigvBundle:VenueTeamLink')->findOneById($linkID);
      $em->remove($link);
      $em->flush();
      return $this->redirect($this->generateUrl('_RBV_club_home', array('clubID'=>$clubID)));
    }
    

}

?>
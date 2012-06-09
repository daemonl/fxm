<?php
namespace Rebase\BigvBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

use Rebase\BigvBundle\Entity\Club;
use Rebase\BigvBundle\Entity\Team;
use Rebase\BigvBundle\Entity\VenueTeamLink;

use Rebase\BigvBundle\Form\Type\TeamType;
use Doctrine\ORM\EntityRepository;
use Rebase\BigvBundle\AltObj\CollectionPersist\Collectionator;
class ClubController extends _Season
{
  public function indexAction()
  {
    $clubs = $this->league->getClubs();
    return $this->render('RebaseBigvBundle:Club:index.html.twig', array('clubs'=> $clubs));
  } 
	
  public function editAction(Request $request, $cslID)
  {
    $em = $this->getDoctrine()->getEntityManager();
    
    if ($cslID != 0)
    {
     $csl = $this->loadCSL($cslID);
      
    }else{ 
      $csl = new \Rebase\BigvBundle\Entity\ClubSeasonLink();
      $club = new \Rebase\BigvBundle\Entity\Club();
      $club->addClubSeasonLink($csl);
      $club->setLeague($this->league);
      $csl->setClub($club);
      $csl->setSeason($this->season);
    }
      $form = $this->createFormBuilder($csl)
            ->add('club.name', 'text')
            ->add('club.shortname', 'text')
            ->add('vsl', 'entity', array('class' => 'RebaseBigvBundle:VenueSeasonLink', 'property' => 'venue.name', 'query_builder' => function(EntityRepository $er) {
        return $er->createQueryBuilder('funder')
                ->add('select', 'vsl')
                ->add('from', 'RebaseBigvBundle:VenueSeasonLink vsl')
                ->add('where', 'vsl.season = ?1')
                ->setParameter(1, \Rebase\BigvBundle\Statics\Context::$season->getId());
        }))
        ->getForm();

		if ($request->getMethod() == 'POST') {
			  $form->bindRequest($request);
			  if ($form->isValid()) {
          
          $em->persist($csl->getClub());
      	  $em->persist($csl);
      	  $em->flush();
      	  //ViewVenue
      	  return $this->redirect($this->generateUrl('_RBV_club_home', array('cslID'=>$csl->getId())));
		    }else{
          $this->flash('error', 'There was an error with your form.');
        }
      }
		  return $this->render('RebaseBigvBundle:Club:clubForm.html.twig', array('csl' => $csl , 'form' => $form->createView()));
    }
    
    public function loadCSL($cslID)
    {
       $em = $this->getDoctrine()->getEntityManager();
       $query = $em->createQuery("SELECT csl, club FROM RebaseBigvBundle:ClubSeasonLink csl JOIN csl.club club WHERE csl.id=?1 AND csl.season=?2")
              ->setParameter(1, $cslID)
              ->setParameter(2, $this->season->getId());
      $csl = $this->singleResultOr404($query);
      return $csl;
    }
    
    public function homeAction(Request $request, $cslID)
    {
      $em = $this->getDoctrine()->getEntityManager();
      
      $csl = $this->loadCSL($cslID);
      
      
      $form = $this->createFormBuilder($csl)
         ->add('teams', 'collection', array('required'=>false, 'label'=>'-', 'type'=>new TeamType(), 'allow_add'=>true, 'allow_delete'=>true, 'by_reference' =>true))
           
              ->getForm();
        
          if ($request->getMethod() == 'POST')
    {
     $em = $this->getDoctrine()->getEntityManager();

     $C = new Collectionator($em,$csl->getTeams(), array(), array('csl'=>$csl));
   
      $form->bindRequest($request);
      
      if ($form->isValid()){
       
        $C->persist();
        $em->flush();
        $this->flash('good', "Your changes to this club's teams were successfully saved.");
        return $this->redirect($this->generateUrl("_RBV_club_index"));
      }else{
        $this->flash('error', 'There was an error updating the teams. Please check below.');
      }
    }

      return $this->render('RebaseBigvBundle:Club:clubTeams.html.twig', array('csl' => $csl, 'form'=>$form->createView()));
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
<?php

namespace Rebase\BigvBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

use Rebase\BigvBundle\Entity\Setting;
use Rebase\BigvBundle\Entity\Round;
use Rebase\BigvBundle\Entity\Calendar;

use Rebase\BigvBundle\Common\BasicFunctions;
use Rebase\BigvBundle\Common;

class GameController extends Controller
{
  public function setSlotAction(Request $request)
  {
    $em = $this->getDoctrine()->getEntityManager();
    $gameID = intval($request->request->get('GameID'));
    $game = $em->getRepository('RebaseBigvBundle:Game')->find($gameID);
    if ($request->request->get('SlotID') == "NONE")
    {
      $date = new \DateTime($request->request->get('Date'));
      $venueID = intval($request->request->get('Venue'));
      $venue = $em->getRepository('RebaseBigvBundle:Venue')->find($venueID);
      $game->removeSlot();
      $game->setDate($date);
      $game->setVenue($venue);
    }else{
      $slotID = intval($request->request->get('SlotID'));
      $slot = $em->getRepository('RebaseBigvBundle:Slot')->find($slotID);
      $game->setSlot($slot);
    }
    $em->flush();
    
    $response = new Response(json_encode(Array("OK", $gameID)));
    $response->headers->set('Content-Type', 'application/json');
    return $response;
  }
  
  public function doAction(Request $request)
  {
	  $em = $this->getDoctrine()->getEntityManager();
    $HomeTeam = $em->getRepository('RebaseBigvBundle:Team')->find(intval($request->request->get('HomeTeam')));
    $AwayTeam = $em->getRepository('RebaseBigvBundle:Team')->find(intval($request->request->get('AwayTeam')));
	  $date = new \DateTime($request->request->get('Date'));
    $ID = BasicFunctions::GetSafeString($request->request->get('ID'));
    $slotID = BasicFunctions::GetSafeString($request->request->get('Slot'));
	  $slot = NULL;
	  if ($ID == "NEW")
	  {
		  $game = new \Rebase\BigvBundle\Entity\Game(); 
		  $game->setHomeTeam($HomeTeam);
      $game->setAwayTeam($AwayTeam);
		}else{
		  $game = $em->getRepository('RebaseBigvBundle:Game')->find(intval($request->request->get('ID')));
	  }
    
	  $game->setDate($date);
    
    //Is slot set in the query?
    if (is_numeric($slotID))
    {
      $slot = $em->getRepository('RebaseBigvBundle:Slot')->findOneById($slotID);
    }
    //No, so does the game already have one?
    if (!$slot)
    {
      $slot = $game->getSlot();
    }
    if ($slot)
    {
      //Check slot date against game date
      if ($slot->getStart()->format("y-m-d") != $game->getDate()->format("y-m-d"))
      {
        $game->removeSlot();
        $slot = NULL;
      }
    }
    //If there is no slot after this, try to find one.
    if (!$slot)    
    {
		  $query = $em->createQuery('
SELECT s FROM RebaseBigvBundle:Slot s JOIN s.court v JOIN v.VenueTeamLink l WHERE s.start = :date AND l.team = :hometeam AND s.game IS NULL ORDER BY s.priority DESC'
      )->setParameter('date', $game->getDate()->format("Y-m-d H:i:s"))
       ->setParameter('hometeam', $game->getHomeTeam()->getId());
        $r = $query->getResult();
        
	      if (count($r) > 0)
	      {
		      $slot = $r[0];
	      }
	  }
	  if ($slot)
    {
	    $game->setSlot($slot);
      $game->setVenue($slot->getVenue());
      $slot->setGame($game);
      $em->persist($slot);
	  }else{
      $game->setVenue($em->getRepository('RebaseBigvBundle:VenueTeamLink')->findOneBy(array('team' => $HomeTeam->getID()))->getVenue());
      //Clean up, just in case there is a slot still. (Safe to use Method even if there is no slot)
		  $game->removeSlot();
	  }
    
  $em->persist($game);
  $em->flush();
  
  
	$retGame = new Common\JSON\Game();
  $retGame->GameID = $game->getId();
  $retGame->date = $game->getShortDate();
  $retGame->HomeTeam = $game->getHomeTeam()->getId();
  $retGame->AwayTeam = $game->getAwayTeam()->getId();
  if ($slot){ $retGame->Slot = $slot;}
  $retGame->Venue = $game->getVenue()->getId();
  $response = new Response(json_encode($retGame));
	$response->headers->set('Content-Type', 'application/json');
 	return $response;
}
  
  public function deleteAction(Request $request)
  {
	  $em = $this->getDoctrine()->getEntityManager();
	  $game = $em->getRepository('RebaseBigvBundle:Game')->find(intval($request->request->get('ID')));
    $game->removeSlot();

	  $em->remove($game);
	  $em->flush();
	  $response = new Response(json_encode("OK"));
	  $response->headers->set('Content-Type', 'application/json');
    return $response;
  } 
  
  public function frameAction(Request $request, $gameID)
  {
	  $em = $this->getDoctrine()->getEntityManager();
	  $game = $em->getRepository('RebaseBigvBundle:Game')->findOneById($gameID);
	  $query = $em->createQuery('SELECT g FROM RebaseBigvBundle:Game g WHERE g.venue = :venue AND g.date = :date'
      )->setParameter('date', $game->getDate()->format("Y-m-d H:i:s"))
       ->setParameter('venue', $game->getVenue()->getId());
		
	  $otherGames =  $query->getResult();
	  
	  $form = $this->createFormBuilder($game)
           ->add('venue', 'entity', array(
              'class' => 'RebaseBigvBundle:Venue'
            ))
            ->getForm();

		if ($request->getMethod() == 'POST') {
			$form->bindRequest($request);
			if ($form->isValid()) {
      			$em->persist($game);
      			$em->flush();
			}
		}
	  
	  return $this->render('RebaseBigvBundle:Game:frame.html.twig', array(
         'form' => $form->createView(),
		 'game' => $game,
		 'othergames' => $otherGames));
  }
  
}
?>

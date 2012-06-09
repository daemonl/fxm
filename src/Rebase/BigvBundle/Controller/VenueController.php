<?php

namespace Rebase\BigvBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

use Rebase\BigvBundle\Entity\Venue;
use Rebase\BigvBundle\Entity\Slot;
use Rebase\BigvBundle\Entity\Calendar;

use Rebase\BigvBundle\Common\BasicFunctions;

use Rebase\BigvBundle\Form\Type\SlotType;
use Rebase\BigvBundle\AltObj\CollectionPersist\Collectionator;

class VenueController extends _Season
{
    public function indexAction()
    {
      
      
		  $venues = $this->league->getVenues();
      
      return $this->render('RebaseBigvBundle:Venue:venueIndex.html.twig', array('venues'=>$venues));
    }

  public function editAction(request $request, $venueID)
  {
    $em = $this->getDoctrine()->getEntityManager();
    
    if ($venueID == 0)
    {
      $venue = new Venue();
      $venue->setLeague($this->league);
      $vsl = new \Rebase\BigvBundle\Entity\VenueSeasonLink();
      $vsl->setVenue($venue);
      $vsl->setSeason($this->season);
      $venue->addVenueSeasonLink($vsl);
    }else{
      $query = $em->createQuery("SELECT v, vsl FROM RebaseBigvBundle:Venue v LEFT JOIN v.VenueSeasonLinks vsl WHERE v.id=?1 AND v.league=?2")
                ->setParameter(1, $venueID)
                ->setParameter(2, $this->league->getId());
      $venue = $this->singleResultOr404($query);
    }


	  	$form = $this->createFormBuilder($venue)
        ->add('name')
			  ->add('FullName')
			  ->add('Address')
        ->getForm();

		if ($request->getMethod() == 'POST') {
      
			$form->bindRequest($request);
      
			if ($form->isValid()) {
      			
            if ($venueID == 0)
            {
               $em->persist($vsl);
            }
            $em->persist($venue);
      			$em->flush();
      			//ViewVenue
      			return $this->redirect($this->generateUrl('_RBV_venue_index', array('venue'=>$venue->getId())));
			}
		}
        
		return $this->render('RebaseBigvBundle:Venue:venueForm.html.twig', array(
        'form' => $form->createView(),
		  	'venue' => $venue));
	}
  

    public function loadVSL($vslID)
    {
      $em = $this->getDoctrine()->getEntityManager();
      $query = $em->createQuery("SELECT vsl, v FROM RebaseBigvBundle:VenueSeasonLink vsl JOIN vsl.venue v WHERE vsl.id=?1 AND vsl.season=?2")
            ->setParameter(1, $vslID)
            ->setParameter(2, $this->season->getId());
      return $this->singleResultOr404($query);
    }
    public function useAction($venueID)
    {
      $em = $this->getDoctrine()->getEntityManager();
      
      $query = $em->createQuery("SELECT v, vsl FROM RebaseBigvBundle:Venue v LEFT JOIN v.VenueSeasonLinks vsl WHERE v.id=?1 AND v.league=?2")
                ->setParameter(1, $venueID)
                ->setParameter(2, $this->league->getId());
      $venue = $this->singleResultOr404($query);
      
      if ($venue->ThisSeason() != null)
      {
        $this->flash('error', "The venue '{$venue->getName()}' has already been included in the current season.");
        return $this->redirect($this->generateUrl('_RBV_venue_index'));
      }
      
      $vsl = new \Rebase\BigvBundle\Entity\VenueSeasonLink();
      $vsl->setSeason($this->season);
      $vsl->setVenue($venue);
      $em->persist($vsl);
      $em->flush();
      $this->flash('good', "The venue '{$venue->getName()}' is now set to be used for this season.");
      return $this->redirect($this->generateUrl('_RBV_venue_index'));
    }
    
    public function homeAction($venue)
    {
		$repository = $this->getDoctrine()
			->getRepository('RebaseBigvBundle:Venue');
		$venue = $repository->findOneById($venue);
		return $this->render('RebaseBigvBundle:Venue:venueHome.html.twig', array('venue'=>$venue));
    }

   
	public function deleteAction($venue)
	{
		$em = $this->getDoctrine()->getEntityManager();
		$venue = $em->getRepository('RebaseBigvBundle:Venue')->findOneById($venue);
		$em->remove($venue);
		$em->flush();
	}

  
  
  public function slotsAction($vslID)
  {
    $vsl = $this->loadVSL($vslID);
    
    $em = $this->getDoctrine();

    
	  $rounds = $this->season->getRounds();
	  $slots = $vsl->getSlots();
    
	  $cal = new Calendar();
    return $this->render('RebaseBigvBundle:Venue:venueSlots.html.twig', array('calendar'=>$cal, 'venue'=>$venue, 'slots'=>$slots, 'rounds'=>$rounds));
  }
  
  public function courtDeleteAction(Request $request, $vslID, $courtID)
  {
   $vsl = $this->loadVSL($vslID);
    $em = $this->getDoctrine()->getEntityManager();

    $query = $em->createQuery("SELECT c FROM RebaseBigvBundle:Court c WHERE c.id=?1 AND c.vsl=?2")
            ->setParameter(1, $courtID)
            ->setParameter(2, $vsl->getId());
    $court = $this->singleResultOr404($query);
    
    $em->remove($court);
    $em->flush();
    return $this->redirect($this->generateUrl("_RBV_venue_index"));
  }
  public function courtAction(Request $request, $vslID, $courtID)
  {
    $vsl = $this->loadVSL($vslID);
    $em = $this->getDoctrine()->getEntityManager();
    if ($courtID == 0)
    {
      $court = new \Rebase\BigvBundle\Entity\Court();
      $court->setVsl($vsl);
    }else{
      $query = $em->createQuery("SELECT c FROM RebaseBigvBundle:Court c WHERE c.id=?1 AND c.vsl=?2")
              ->setParameter(1, $courtID)
              ->setParameter(2, $vsl->getId());
      $court = $this->singleResultOr404($query);
    }
    
    $form = $this->createFormBuilder($court)
            ->add('name')
            ->add('slots', 'collection', array('required'=>false, 'label'=>'Timeslots', 'type'=>new SlotType(), 'allow_add'=>true, 'allow_delete'=>true, 'by_reference' =>true))
            ->getForm();
    
     if ($request->getMethod() == 'POST')
    {
      $em = $this->getDoctrine()->getEntityManager();

      $S = new Collectionator($em,$court->getSlots(), array(), array('Court'=>$court));
   
      $form->bindRequest($request);
      
      if ($form->isValid()){
       
        $S->persist();
        $em->persist($court);
        $em->flush();
        $this->flash('good', "Court Updated.");
        return $this->redirect($this->generateUrl("_RBV_venue_index"));
      }else{
        $this->flash('error', 'There was an error updating the court. Please check below.');
      }
    }
       
    
    return $this->render('RebaseBigvBundle:Venue:courtForm.html.twig', array('court'=>$court, 'form'=>$form->createView()));
  }

  public function slotsAjaxDeleteAction(Request $request, $venue)
  {
	  $em = $this->getDoctrine()->getEntityManager();
	  $sID = BasicFunctions::GetSafeString($request->request->get('sID'));
	  $slot = $em->getRepository('RebaseBigvBundle:Slot')->findOneById($sID);
	  
	  if ($slot->getGame() != NULL)
	  {
		$returnSlot = array("ERROR", "This slot has a game assigned and cannot be deleted.", $sID, $slot->getID() ,$slot->getShortDate(), $slot->getPriority(), $slot->getRound()->getNumber());
		$response = new Response(json_encode($returnSlot));
		$response->headers->set('Content-Type', 'application/json');
    	return $response;  
	  }
	  
	  $em->remove($slot);
	  $em->flush();
	  $response = new Response(json_encode(array("OK")));
	  $response->headers->set('Content-Type', 'application/json');
      return $response;
  }
  public function slotsAjaxAction(Request $request, $venue)
  {	
	$sID = BasicFunctions::GetSafeString($request->request->get('sID'));
	$sDay = new \DateTime($request->request->get('sDay')); 
	$sLev = intval($request->request->get('sLev'));
	$sRound = intval($request->request->get('sRound'));
	$em = $this->getDoctrine()->getEntityManager();
	$round = $em->getRepository('RebaseBigvBundle:Round')->findOneById($sRound);
	
	if (substr($sID, 0, 3) == "NEW")
	{
		$slot = new Slot();
		$vr = $em->getRepository('RebaseBigvBundle:Venue');
		$venue = $vr->findOneById($venue);
		$slot->setVenue($venue);
	}else{
		$slot = $em->getRepository('RebaseBigvBundle:Slot')->findOneById($sID);
	}
	
	$slot->setStart($sDay);
	$slot->setEnd($sDay);
	$slot->setRound($round);
	$slot->setPriority($sLev);
	$em->persist($slot);
	$em->flush();
	
	$returnSlot = array($sID, $slot->getID() ,$sDay, $sLev, $round->getNumber());
	$response = new Response(json_encode($returnSlot));
	$response->headers->set('Content-Type', 'application/json');
    return $response;
  }

}

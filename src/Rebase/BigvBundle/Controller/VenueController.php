<?php

namespace Rebase\BigvBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

use Rebase\BigvBundle\Entity\Venue;
use Rebase\BigvBundle\Entity\Slot;
use Rebase\BigvBundle\Entity\Calendar;

use Rebase\BigvBundle\Common\BasicFunctions;

class VenueController extends Controller
{
    
    public function indexAction()
    {
		$repository = $this->getDoctrine()
			->getRepository('RebaseBigvBundle:Venue');
		$venues = $repository->findAll();
        return $this->render('RebaseBigvBundle:Venue:venueIndex.html.twig', array('venues'=>$venues));
    }

    public function createAction(Request $request)
    {
      //CreateVenue
      $venue = new Venue();
      $venue->setName("New Venue");

	  $form = $this->createFormBuilder($venue)
            ->add('name')
			->add('FullName', 'text' , array('required' => false))
			->add('Address', 'textarea', array('required' => false))
            ->getForm();

		if ($request->getMethod() == 'POST') {
			$form->bindRequest($request);
			if ($form->isValid()) {
				$em = $this->getDoctrine()->getEntityManager();
      			$em->persist($venue);
      			$em->flush();
      			//ViewVenue
      			return $this->redirect($this->generateUrl('_RBV_venue_index', array('venue'=>$venue->getId())));
			}
		}
        
		return $this->render('RebaseBigvBundle:Venue:venueCreate.html.twig', array(
          	'form' => $form->createView(),
		  	'venue' => $venue));
    }
    
    public function homeAction($venue)
    {
		$repository = $this->getDoctrine()
			->getRepository('RebaseBigvBundle:Venue');
		$venue = $repository->findOneById($venue);
		return $this->render('RebaseBigvBundle:Venue:venueHome.html.twig', array('venue'=>$venue));
    }

    public function infoAction(request $request, $venue)
    {
		$repository = $this->getDoctrine()
			->getRepository('RebaseBigvBundle:Venue');
		$venue = $repository->findOneById($venue);

	  	$form = $this->createFormBuilder($venue)
            ->add('name')
			->add('FullName')
			->add('Address')
            ->getForm();

		if ($request->getMethod() == 'POST') {
			$form->bindRequest($request);
			if ($form->isValid()) {
				$em = $this->getDoctrine()->getEntityManager();
      			$em->persist($venue);
      			$em->flush();
      			//ViewVenue
      			return $this->redirect($this->generateUrl('_RBV_venue_index', array('venue'=>$venue->getId())));
			}
		}
        
		return $this->render('RebaseBigvBundle:Venue:venueInfo.html.twig', array(
          	'form' => $form->createView(),
		  	'venue' => $venue));
	}
	public function deleteAction($venue)
	{
		$em = $this->getDoctrine()->getEntityManager();
		$venue = $em->getRepository('RebaseBigvBundle:Venue')->findOneById($venue);
		$em->remove($venue);
		$em->flush();
	}

  public function slotsAction($venue)
  {
    $em = $this->getDoctrine();
	$venue = $em->getRepository('RebaseBigvBundle:Venue')->findOneById($venue);
	$rounds = $em->getRepository('RebaseBigvBundle:Round')->findAll();
	$slots = $venue->getSlots();
	$cal = new Calendar();
    return $this->render('RebaseBigvBundle:Venue:venueSlots.html.twig', array('calendar'=>$cal, 'venue'=>$venue, 'slots'=>$slots, 'rounds'=>$rounds));
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

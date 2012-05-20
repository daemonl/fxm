<?php

namespace Rebase\BigvBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

use Rebase\BigvBundle\Form\Type\CourtType;

use Rebase\BigvBundle\Entity\Venue;
use Rebase\BigvBundle\Entity\Slot;
use Rebase\BigvBundle\Entity\Calendar;
use Rebase\BigvBundle\Statics\Context;
use Rebase\BigvBundle\Common\BasicFunctions;

class VenueController extends _ParentController {

  public function indexAction() {
    
     $em = $this->getDoctrine()->getEntityManager();
       $qb = $em->createQueryBuilder()
          ->add('select', 'v')
          ->add('from', 'RebaseBigvBundle:Venue v')
          ->add('where', 'v.league = ?2')
          ->setParameter(2, Context::$league->getId());
    $venues = $qb->getQuery()->getResult();
    return $this->render('RebaseBigvBundle:Venue:venueIndex.html.twig', array('venues' => $venues));
  }
  
  public function getVenue($venueID)
  {
     $em = $this->getDoctrine()->getEntityManager();
           $qb = $em->createQueryBuilder()
              ->add('select', 'v')
              ->add('from', 'RebaseBigvBundle:Venue v')
              ->add('where', 'v.id = ?1 AND v.league = ?2')
              ->setParameter(1, $venueID)
              ->setParameter(2, Context::$league->getId());
      return $this->singleResultOr404($qb->getQuery());
  }
  
  public function homeAction($venueID) {
    $venue = $this->getVenue($venueID);
    return $this->render('RebaseBigvBundle:Venue:venueHome.html.twig', array('venue' => $venue));
  }

  public function editAction(request $request, $venueID) {
   
    if ($venueID == 0) {
      $venue = new Venue();
      $venue->setLeague(Context::$league);
    } else {
      $venue = $this->getVenue($venueID);
    }

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
        return $this->redirect($this->generateUrl('_RBV_venue_home', array('venueID' => $venue->getId())));
      }
    }

    return $this->render('RebaseBigvBundle:Venue:venueInfo.html.twig', array(
                'form' => $form->createView(),
                'venue' => $venue));
  }

  public function deleteAction($venueID) {
    $em = $this->getDoctrine()->getEntityManager();
    $venue = $em->getRepository('RebaseBigvBundle:Venue')->findOneById($venueID);
    $em->remove($venueID);
    $em->flush();
  }

  public function courtsAction(Request $request, $venueID)
  {
    $em = $this->getDoctrine()->getEntityManager();
    $venue = $this->getVenue($venueID);
    
    $form = $this->createFormBuilder($venue)
            ->add('courts', 'collection',  array('required'=>false, 'label'=>'Courts','type'=>new CourtType(), 'allow_add'=>true, 'allow_delete'=>true, 'by_reference' =>true))
            ->getForm();
    
     
   if ($request->getMethod() == 'POST') {
       $oldCourts = $this->getOldForPersistCollection($venue->getCourts());
      $form->bindRequest($request);
      if ($form->isValid()) {
        $em = $this->getDoctrine()->getEntityManager();
        
      
         $CourtsToDelete = $this->getDeleteListForPersistCOllection($oldCourts, $venue->getCourts());
         
        foreach ($CourtsToDelete as $delme)
        {
         // $em->remove($delme->getAddress());
          $em->remove($delme);
        }
        foreach ($venue->getCourts() as $newCourt)
        {
          $em->persist($newCourt);
        }
        
        $em->persist($venue);
        $em->flush();
        //ViewVenue
        return $this->redirect($this->generateUrl('_RBV_venue_home', array('venueID' => $venue->getId())));
      }
    }
     return $this->render('RebaseBigvBundle:Venue:venueCourts.html.twig', array(
              'form' => $form->createView(),
              'venue' => $venue)); 
  }
  
  
  public function slotsAction($venue) {
    $em = $this->getDoctrine();
    $venue = $em->getRepository('RebaseBigvBundle:Venue')->findOneById($venue);
    $rounds = $em->getRepository('RebaseBigvBundle:Round')->findAll();
    $slots = $venue->getSlots();
    $cal = new Calendar();
    return $this->render('RebaseBigvBundle:Venue:venueSlots.html.twig', array('calendar' => $cal, 'venue' => $venue, 'slots' => $slots, 'rounds' => $rounds));
  }

  public function slotsAjaxDeleteAction(Request $request, $venue) {
    $em = $this->getDoctrine()->getEntityManager();
    $sID = BasicFunctions::GetSafeString($request->request->get('sID'));
    $slot = $em->getRepository('RebaseBigvBundle:Slot')->findOneById($sID);

    if ($slot->getGame() != NULL) {
      $returnSlot = array("ERROR", "This slot has a game assigned and cannot be deleted.", $sID, $slot->getID(), $slot->getShortDate(), $slot->getPriority(), $slot->getRound()->getNumber());
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

  public function slotsAjaxAction(Request $request, $venue) {
    $sID = BasicFunctions::GetSafeString($request->request->get('sID'));
    $sDay = new \DateTime($request->request->get('sDay'));
    $sLev = intval($request->request->get('sLev'));
    $sRound = intval($request->request->get('sRound'));
    $em = $this->getDoctrine()->getEntityManager();
    $round = $em->getRepository('RebaseBigvBundle:Round')->findOneById($sRound);

    if (substr($sID, 0, 3) == "NEW") {
      $slot = new Slot();
      $vr = $em->getRepository('RebaseBigvBundle:Venue');
      $venue = $vr->findOneById($venue);
      $slot->setVenue($venue);
    } else {
      $slot = $em->getRepository('RebaseBigvBundle:Slot')->findOneById($sID);
    }

    $slot->setStart($sDay);
    $slot->setEnd($sDay);
    $slot->setRound($round);
    $slot->setPriority($sLev);
    $em->persist($slot);
    $em->flush();

    $returnSlot = array($sID, $slot->getID(), $sDay, $sLev, $round->getNumber());
    $response = new Response(json_encode($returnSlot));
    $response->headers->set('Content-Type', 'application/json');
    return $response;
  }

}

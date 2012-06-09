<?php

namespace Rebase\BigvBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

use Rebase\BigvBundle\Entity\Setting;
use Rebase\BigvBundle\Entity\Round;
use Rebase\BigvBundle\AltObj\Calendar\Calendar;
use Rebase\BigvBundle\Common\BasicFunctions;

use Rebase\BigvBundle\Form\Type\RoundType;
use Rebase\BigvBundle\AltObj\CollectionPersist\Collectionator;



use Rebase\BigvBundle\Statics\Context;

class RoundsController extends _Season
{
  public function roundsAction(Request $request)
  {
    $season = Context::$season;
    $form = $this->createFormBuilder($season)
            ->add('rounds', 'collection', array('required'=>false, 'label'=>'-', 'type'=>new RoundType(), 'allow_add'=>true, 'allow_delete'=>true, 'by_reference' =>true))
            ->getForm();

    
    if ($request->getMethod() == 'POST')
    {
     $em = $this->getDoctrine()->getEntityManager();

     $C = new Collectionator($em,$season->getRounds(), array(), array('season'=>$season));
   
      $form->bindRequest($request);
      
      if ($form->isValid()){
       
        $C->persist();
        $em->flush();
        $this->flash('good', "Your changes to this season's rounds were successfully saved.");
        return $this->redirect($this->generateUrl("_RBV_home"));
      }else{
        $this->flash('error', 'There was an error updating the rounds. Please check below.');
      }
    }
       
    return $this->render('RebaseBigvBundle:Rounds:roundsForm.html.twig', array('form'=>$form->createView(), 'rounds'=>$season->getRounds()));
  }
  
  
	public function COMPLEXroundsAction()
  {
    
    
    $em = $this->getDoctrine()->getEntityManager();
    
        
    $qb = $em->createQueryBuilder();
    $qb->add('select', 'round')
            ->add('from', 'RebaseBigvBundle:Round round')
            ->add('where', 'round.season=?1')
            ->setParameter(1, context::$season->getId());
    
    $rounds = $qb->getQuery()->getResult();
		
		//$cal = new Calendar();
 	
    return $this->render('RebaseBigvBundle:Rounds:settingsRounds.html-simple.twig', array('calendar'=>$cal, 'rounds'=>$rounds));
  }

	public function roundsAjaxAction(Request $request)
  {
	$B = new BasicFunctions();
	$roundID = intval($request->request->get('roundID'));
	$roundName = $B->GetSafeString($request->request->get('roundName'));
	$roundStart = new \DateTime($request->request->get('roundStart'));
	$roundEnd = new \DateTime($request->request->get('roundEnd'));
	
	$em = $this->getDoctrine()->getEntityManager();
	if ($request->request->get('roundID') == "NEW")
	{
		$round = new Round();
	}else{
		$round = $em->getRepository('RebaseBigvBundle:Round')->find($roundID);	
	}
	$round->setNumber($roundName);
	$round->setStart($roundStart);
	$round->setEnd($roundEnd);
	$em->flush();
		
		
	$returnRound = array($roundID, $roundName, $roundStart->format("y-m-d"), $roundEnd->format("y-m-d"));
		
	$response = new Response(json_encode($returnRound));
	$response->headers->set('Content-Type', 'application/json');
 	
        return $response;
    }

}

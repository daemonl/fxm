<?php

namespace Rebase\BigvBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

use Rebase\BigvBundle\Entity\Setting;
use Rebase\BigvBundle\Entity\Round;
use Rebase\BigvBundle\AltObj\Calendar\Calendar;
use Rebase\BigvBundle\Common\BasicFunctions;

use Rebase\BigvBundle\Statics\Context;

class SettingsController extends _ParentController
{
    public function indexAction()
    {
		$repository = $this->getDoctrine()
			->getRepository('RebaseBigvBundle:Setting');
		$settings = $repository->findAll();
        return $this->render('RebaseBigvBundle:Settings:settingsIndex.html.twig', array('settings'=>$settings));
    }
	
	public function roundsAction()
    {
    $em = $this->getDoctrine()->getEntityManager();
    
    $qb = $em->createQueryBuilder();
    $qb->add('select', 'round')
            ->add('from', 'RebaseBigvBundle:Round round')
            ->add('where', 'round.season=?1')
            ->setParameter(1, context::$season->getId());
    
    $rounds = $qb->getQuery()->getResult();
		
		$cal = new Calendar();
 	
    return $this->render('RebaseBigvBundle:Settings:settingsRounds.html.twig', array('calendar'=>$cal, 'rounds'=>$rounds));
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

<?php

namespace Rebase\BigvBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

use Rebase\BigvBundle\Entity\Venue;
use Rebase\BigvBundle\Entity\Slot;
use Rebase\BigvBundle\Entity\Game;

use Rebase\BigvBundle\Common\BasicFunctions;
use Rebase\BigvBundle\Common\JSON\Warning;

class WarningController extends Controller
{
    
    public function allAction()
    {
        $em = $this->getDoctrine()->getEntityManager();
        $Return = array();
	$query = $em->createQuery('
		SELECT g, ht, hc, at, ac FROM RebaseBigvBundle:Game g
		JOIN g.homeTeam ht
		JOIN ht.club hc
		JOIN g.awayTeam at
		JOIN at.club ac 
		WHERE g.slot is NULL'
        );
        $slotless = $query->getResult();
        foreach($slotless as $game)
        {
             $w = new Warning();
            $w->Desc = "Game not in slot: ". $game->getHomeTeam()->getClub()->getShortname();
            $Return[] = $w;
        }
    $response = new Response(json_encode($Return));
    $response->headers->set('Content-Type', 'application/json');
    return $response;   
        
        
    }
}


?>
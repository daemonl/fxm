<?php
namespace Rebase\BigvBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

use Rebase\BigvBundle\AltObj\DayView\DayViewByRound;
use Rebase\BigvBundle\AltObj\DayView\DayViewByVenue;
use Rebase\BigvBundle\AltObj\DayView\DayView;
use Rebase\BigvBundle\Entity\Club;
use Rebase\BigvBundle\Entity\Team;

class DayViewController extends _Season
{
    
    public function indexAction()
    {
       $vsls = $this->season->getVenueSeasonLinks();
	     $rounds = $this->season->getRounds();
       return $this->render('RebaseBigvBundle:DayView:dayviewindex.html.twig', array('vsls' => $vsls, 'rounds' => $rounds));
    }
    
    public function byroundAction($roundID)
    {
      $em = $this->getDoctrine()->getEntityManager();
      $round = $em->getRepository('RebaseBigvBundle:Round')->findOneById(intval($roundID));
      $dayviewname = "Round ".$round->getNumber();
      $DV = new DayViewByRound($em, $round);
      return $this->render('RebaseBigvBundle:DayView:dayview.html.twig', array('dayviewname' => $dayviewname, 'DV' => $DV));
    }
    public function byvenueAction($vslID)
    {
      $em = $this->getDoctrine()->getEntityManager();
      
      $query = $em->createQuery("SELECT vsl FROM RebaseBigvBundle:VenueSeasonLink vsl WHERE vsl.id=?1 AND vsl.season=?2")
              ->setParameter(1, $vslID)
              ->setParameter(2, $this->season->getId());
      
      $vsl = $this->singleResultOr404($query);
            
      $dayviewname = $vsl->getVenue()->getName();
      $DV = new DayViewByVenue($em, $vsl);
      return $this->render('RebaseBigvBundle:DayView:dayview.html.twig', array('dayviewname' => $dayviewname, 'DV' => $DV));
    }
}


?>

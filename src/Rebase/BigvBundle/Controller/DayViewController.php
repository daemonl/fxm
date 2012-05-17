<?php
namespace Rebase\BigvBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

use Rebase\BigvBundle\Entity\DayView\DayViewByRound;
use Rebase\BigvBundle\Entity\DayView\DayViewByVenue;
use Rebase\BigvBundle\Entity\DayView\DayView;
use Rebase\BigvBundle\Entity\Club;
use Rebase\BigvBundle\Entity\Team;

class DayViewController extends Controller
{
    
    public function indexAction()
    {
       $em = $this->getDoctrine()->getEntityManager();
       $venues = $em->getRepository('RebaseBigvBundle:Venue')->findAll();
	   $rounds = $em->getRepository('RebaseBigvBundle:Round')->findAll();
       return $this->render('RebaseBigvBundle:DayView:dayviewindex.html.twig', array('venues' => $venues, 'rounds' => $rounds));
    }
    
    public function byroundAction($roundID)
    {
      $em = $this->getDoctrine()->getEntityManager();
      $round = $em->getRepository('RebaseBigvBundle:Round')->findOneById(intval($roundID));
      $dayviewname = "Round ".$round->getNumber();
      $DV = new DayViewByRound($em, $round);
      return $this->render('RebaseBigvBundle:DayView:dayview.html.twig', array('dayviewname' => $dayviewname, 'DV' => $DV));
    }
    public function byvenueAction($venueID)
    {
      $em = $this->getDoctrine()->getEntityManager();
      $venue = $em->getRepository('RebaseBigvBundle:Venue')->findOneById($venueID);
      $dayviewname = $venue->getName();
      $DV = new DayViewByVenue($em, $venue);
      return $this->render('RebaseBigvBundle:DayView:dayview.html.twig', array('dayviewname' => $dayviewname, 'DV' => $DV));
    }
}


?>

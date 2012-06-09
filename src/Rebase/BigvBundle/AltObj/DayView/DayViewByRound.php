<?php
namespace Rebase\BigvBundle\AltObj\DayView;

use Rebase\BigvBundle\Entity\Round;

class DayViewByRound extends DayView
{ 
  public function __construct($em, Round $round)
  {
    
    $venues = $em->getRepository('RebaseBigvBundle:Venue')->findAll();
    $this->Teams = $em->getRepository('RebaseBigvBundle:Team')->findAll();
	
	$query = $em->createQuery('SELECT g FROM RebaseBigvBundle:Game g WHERE g.date >= :DateStart  AND g.date <= :DateEnd ')
	->setParameter('DateStart', $round->getStart()->format("Y-m-d H:i:s"))
    ->setParameter('DateEnd', $round->getEnd()->format("Y-m-d H:i:s"));
	$this->Games = $query->getResult();
    
	$days = $round->getDays();
	
	foreach ($venues as $V)
    {
      $Subs = Array();
      foreach ($days as $D)
      { 
        $S = new DayViewSub();
        $S->Title = $D;
        $S->Date = $D;
        $S->Venue = $V->getID();
        foreach ($V->getSlots() as $Slot)
        {
          
          if ($Slot->getShortDate() == $D)
          {
            $S->Slots[] = $Slot;
          }

        }
        //if (count($S->Slots) > 0)
        //{
          $Subs[] = $S;
        //}
      }
      
      //if (count($Subs) > 0)
      //{
        $Sup = new DayViewSuper($Subs);
        $Sup->Title = $V->getName();
        $this->Supers[] = $Sup;
      //}
     
    }
    
   
  }
}

?>

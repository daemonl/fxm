<?php
namespace Rebase\BigvBundle\Entity\DayView;

use Rebase\BigvBundle\Entity\Venue;
use Rebase\BigvBundle\Entity\Round;

class DayViewByVenue extends DayView
{ 
  public function __construct($em, Venue $venue)
  {
    
    $rounds = $em->getRepository('RebaseBigvBundle:Round')->findAll();
    $this->Teams = $em->getRepository('RebaseBigvBundle:Team')->findAll();
    $this->Games = $venue->getGames();  
    foreach ($rounds as $R)
    {
    
      $Subs = Array();
      foreach ($R->getDays() as $D)
      { 
        $S = new DayViewSub();
        $S->Title = $D;
        $S->Date = $D;
        $S->Venue = $venue->getID();
        foreach ($venue->getSlots() as $Slot)
        {
          
          if ($Slot->getShortDate() == $D)
          {
            $S->Slots[] = $Slot;
          }

        }
        
        if (count($S->Slots) > 0)
        {
          $Subs[] = $S;
        }
          
      }
      
      if (count($Subs) > 0)
      {
        $Sup = new DayViewSuper($Subs);
        $Sup->Title = "Round ". $R->getNumber();
        $this->Supers[] = $Sup;
      }
     
    }
    
   
  }
}

?>

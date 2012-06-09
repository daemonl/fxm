<?php
namespace Rebase\BigvBundle\AltObj\DayView;

use Rebase\BigvBundle\Entity\Venue;
use Rebase\BigvBundle\Entity\VenueSeasonLink;
use Rebase\BigvBundle\Entity\Round;
use \Rebase\BigvBundle\Statics\Context;
class DayViewByVenue extends DayView
{ 
  public $Teams;
  public $Games;
  public $Rounds;
  
  public function __construct(\Doctrine\ORM\EntityManager $em, VenueSeasonLink $vsl)
  {
    $query = $em->createQuery("SELECT r FROM RebaseBigvBundle:Round r WHERE r.season=?1")
            ->setParameter(1, Context::$season->getId());
    $this->Rounds = $query->getResult();
    
    $query = $em->createQuery("SELECT t FROM RebaseBigvBundle:Team t 
                                LEFT JOIN t.csl csl
                                WHERE csl.season=?1")
            ->setParameter(1, Context::$season->getId());
    $this->Teams = $query->getResult();
    
    
  
    $this->Games = array();
    
    foreach ($vsl->getCourts() as $court)
    {
      foreach ($court->getSlots() as $slot)
      {
        if ($slot->getGame())
        {
          $this->Games[] = $slot->getGame();
        }
      }
    }
    
    
    foreach ($this->Rounds as $R)
    {
    
      $Subs = Array();
      foreach ($R->getDays() as $D)
      { 
        $S = new DayViewSub();
        $S->Title = $D;
        $S->Date = $D;
        $S->Venue = $vsl->getID();
        foreach ($vsl->getCourts() as $court)
        {
        foreach ($court->getSlots() as $Slot)
        {
          
          if ($Slot->getShortDate() == $D)
          {
            $S->Slots[] = $Slot;
          }

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

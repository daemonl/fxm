<?php
namespace Rebase\BigvBundle\AltObj\DayView;

use Rebase\BigvBundle\Entity\Setting;
use Rebase\BigvBundle\Entity\Round;
use Rebase\BigvBundle\Entity\Venue;
use Rebase\BigvBundle\Entity\Calendar;
use Rebase\BigvBundle\Common\BasicFunctions;

class DayViewSuper
{
  public $Subs = Array();
  public $Title; 
  
  public function __construct(Array $Subs)
  {
    $this->Subs = $Subs;
  }
}
class DayViewSub
{
  public $Title; 
  public $Slots = Array();
  public $Date;
  public $Venue;
}

class DayView 
{
  public $Supers = Array();
  public $Games = Array();
  public $Teams = Array();
}



?>

<?php

namespace Rebase\BigvBundle\AltObj\Calendar;

use Rebase\BigvBundle\Statics\Context;

class Calendar
{
	public $Weeks;
	
	public function getWeeks()
	{
	  return $this->Weeks;	
	}
	
	public function getDate($date)
	{
		foreach($this->Weeks as $w)
		{
			foreach($w->Days as $d)
			{
				if ($d->getRaw() == $date)
				{return $d;}
			}
		}
		return nothing;
	}
	
	public function __construct()
	{
		$this->Weeks = array();
    
		$StartDay = context::$season->getStart();
    
 		$EndDay = context::$season->getStop();
    
    $StartDay->setTime(0,0,0);
    $EndDay->setTime(0,0,0);
    
    $D = $StartDay->format('w');
    $StartDay->sub(new \DateInterval("P{$D}D"));
    $D = 7-($EndDay->format('w'));
    $EndDay->sub(new \DateInterval("P{$D}D"));
     
 		$NumWeeks = ($EndDay->format("U") - $StartDay->format("U"))/60/60/24/7;

 		for($w = 0; $w <= $NumWeeks; $w ++)
 		{
		   $this->Weeks[] = new calendar_week($w, $StartDay);
		}
	}
}

class calendar_week
{
	public $Days;
	public $Number;
  public function __construct($WeekNumber, $StartDay)
	{	
	  $this->Number = $WeekNumber;
	  $this->Days = array(); 
    $StartDayTS = intval($StartDay->format("U"));
	  for($d = 0; $d < 7; $d ++)
   	{
      $RTS = $StartDayTS + (60*60*24*$d +($WeekNumber*7*24*60*60));
      $weekStart = new \DateTime("@$RTS");
      
	    $this->Days[] = new calendar_day($weekStart, $this);
		}
	}
	
	public function getNumber()
	{
		return $this->Number;
	}
	public function getDays()
	{
		return $this->Days;
	}
}

class calendar_day
{
	public $Date;
	public $Slots;
	public $Week;
	
	public function __construct($date, $Week)
	{
		$this->Week = $Week;
		$this->Date = $date;
		$this->Slots = array();
	}
	public function RateAvail($venueID)
	{
		$Av = 0;
		foreach ($this->Slots as $s)
		{
			if($s->getVenue()->getId() == $venueID)
			{
				if ( $s->getPriority() > $Av)
				{
					$Av = $s->getPriority();
				}
			}
		}
	}
	
	public function getRaw()
	{
		return $this->Date->format("y-m-d");
	}
	public function getShort()
	{
		return $this->Date->format("d-M D");
	}
}
	 
?>
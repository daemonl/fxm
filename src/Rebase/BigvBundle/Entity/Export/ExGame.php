<?php
namespace Rebase\BigvBundle\Entity\Export;

use Rebase\BigvBundle\Entity\Round;
use Rebase\BigvBundle\Entity\Venue;
use Rebase\BigvBundle\Entity\Game;
use Rebase\BigvBundle\Common\BasicFunctions; 
 
class ExGame
{
  public $GameID;
  public $HomeTeamShort;
  public $HomeTeamLong;
  public $AwayTeamShort;
  public $AwayTeamLong;
  public $DivisionShort;
  public $DivisionLong;
  public $VenueShort;
  public $VenueLong;
  public $DateDay;
  public $DateShort;
  public $DateExcel;
  public $DateLong;
  public $TimeShort;
  public $TimeLong;
  public $Round;
  
  public function __construct($game)
  {
	  $this->GameID = $game->getId();
	  $this->HomeTeamShort = $game->getHomeTeam()->getClub()->getShortname();
	  $this->HomeTeamLong = $game->getHomeTeam()->getClub()->getName();
	  $this->AwayTeamShort = $game->getAwayTeam()->getClub()->getShortname();
	  $this->AwayTeamLong = $game->getAwayTeam()->getClub()->getName();
	  $this->DivisionShort = $game->getHomeTeam()->getDivision()->getName();
	  $this->DivisionLong = $game->getHomeTeam()->getDivision()->getFullName();
	  if ($game->getSlot())
	  {
		  $Date = $game->getSlot()->getStart();
		  $Venue = $game->getSlot()->getVenue();
		  $this->Round = $game->getSlot()->getRound()->getNumber();
	  }else{
		  $Date = $game->getDate();
		  $Venue = $game->getVenue();		  
	  }
		$this->VenueShort = $Venue->getName();
		$this->VenueLong = $Venue->getFullName();
		$this->DateDay = $Date->format("D");
		$this->DateShort = $Date->format("d m y");
		$this->DateExcel = $Date->format("d-m-Y");
		$this->DateLong = $Date->format("D d M Y");
		$this->TimeShort = $Date->format("H:i");
		$this->TimeLong = $Date->format("h:i A");



	  
  }
}

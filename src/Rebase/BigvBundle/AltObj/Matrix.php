<?php
namespace Rebase\BigvBundle\Entity;

use Rebase\BigvBundle\Entity\Setting;
use Rebase\BigvBundle\Entity\Round;
use Rebase\BigvBundle\Entity\Calendar;
use Rebase\BigvBundle\Common\BasicFunctions;

class MatrixRound
{
	public $Days = array();
	public $RoundNumber;
	public function __construct($RepoRound, $MatrixTeams, $ThisDivision)
	{
		$this->RoundNumber = $RepoRound->getNumber();
		//Go from start to end and add days to MatrixDay
		foreach($RepoRound->getDays() as $d)
		{
			$MD = new MatrixDay($this, $d, $MatrixTeams, $RepoRound->getSlots(), $ThisDivision);
			if (count($MD->Slots) > 0){
			  $this->Days[] = $MD;
			}
		}
		
	}
}

class MatrixSlot
{
	public $VenueID;
	public $Priority;
	public $hasGame;
	public $SlotID;
	public function __construct($RepoSlot, $ThisDivision)
	{
		if ($RepoSlot->getGame() == null)
		{
		  $this->hasGame = 0;
		}elseif($RepoSlot->getGame()->getHomeTeam()->getDivision() == $ThisDivision){
      $this->hasGame = 0;
    }else{
		  $this->hasGame = 1;
		}
		$this->SlotID = $RepoSlot->getId();
		$this->VenueID = $RepoSlot->getVenue()->getID();
		$this->Priority = $RepoSlot->getPriority();
	}
}

class MatrixDay
{
	public $Teams = array();
	public $Slots = array();
    public $DateObject;
	public $RawDay;
	public $ShortDay;
    public $WeekDay;

	public function __construct($MatrixRound, $RawDay, $MatrixTeams, $slots, $ThisDivision)
	{

	$this->RawDay = $RawDay;
    $this->DateObject = \date_create($RawDay);
    $this->ShortDay = $this->DateObject->format("j M");
    $this->WeekDay = $this->DateObject->format("D");
	
		foreach ($slots as $s)
		{
		  if($s->getShortDate() == $RawDay)
		  {
		    $MS = new MatrixSlot($s, $ThisDivision);
		    $this->Slots[] = $MS;	
		  }
		}
		if (count($this->Slots) > 0){
		  foreach ($MatrixTeams as $MT)
		  {
			$MTD = new MatrixTeamDay($this, $MT);
			$this->Teams[] = $MTD;
		  }
		}
	}
}

class MatrixTeamDay
{
	public $Team;
	public $Slots = array();
	public $Priority;
	public $NextSlot;
	public function __construct($MatrixDay, $MatrixTeam)
	{
		$this->Team = $MatrixTeam;
		$this->Priority = 0;
		foreach ($MatrixDay->Slots as $s)
		{
			foreach ($MatrixTeam->VenueLinks as $l)
			{
		      if (($s->VenueID == $l->getVenue()->getID())&&($s->hasGame == 0))
		      {
		  	    $this->Slots[] = $s;
			    if ($this->Priority < $s->Priority)
			    { 
				    $this->Priority = $s->Priority;
				    $this->NextSlot = $s->SlotID;
			    }
		      }
		   }
		}
	}
}

class MatrixTeam
{
	public $ShortName;
	public $TeamID;
	public $VenueLinks;
	public $RequiredGames = array();
	
	public function __construct($em, $RepoTeam)
	{
		$this->ShortName = $RepoTeam->getClub()->getShortname();
		$this->TeamID = $RepoTeam->getId();
		$this->VenueLinks = $RepoTeam->getVenueTeamLink();
	}
}

class MatrixGame
{
  public $HomeTeam;
  public $AwayTeam;
  public $day;
  public $gameID;
}

class MatrixRequiredSet
{
  public $HomeGames = array();
  public $AwayHames = array();
  public $SetNum;
  public $RequiredString;
  public $RequiredNum;
}

class Matrix
{
  public $Rounds = array();
  public $Teams = array();
  public $RequiredGames = array();
  public $ExistingGames = array();
	
  public function GetColCount()
  {
    return(count($this->Teams) + 2);
  }
	
  public function GetTeamWithID($id)
  {
    foreach ($this->Teams as $T)
    {
      if ($T->TeamID == $id) {return $T;}	
    }
    return NULL;
  }
	
  public function __construct($em, $division)
  {
    $divisionID = $division->getID();
		
    $query = $em->createQuery("
      SELECT t, l, v, c FROM RebaseBigvBundle:Team t
      JOIN t.VenueTeamLink l
      JOIN t.club c
      JOIN l.venue v
      WHERE t.division = $divisionID
      ");
    $teams = $query->getResult();
				
    $query = $em->createQuery("
      SELECT r, s, v FROM RebaseBigvBundle:Round r
      JOIN r.slots s
      JOIN s.venue v
      ORDER BY r.number
      ");//JOIN s.game g  JOIN g.homeTeam ht JOIN ht.division d
    $rounds = $query->getResult();
				
    $query = $em->createQuery('
      SELECT g, ht, hc, at, ac, s, v FROM RebaseBigvBundle:Game g
      JOIN g.homeTeam ht
      JOIN ht.club hc
      JOIN g.awayTeam at
      JOIN at.club ac 
      JOIN g.slot s
      JOIN s.venue v'
      );
    $games = $query->getResult();
		
    foreach($teams as $t)
    {
      $MV = new MatrixTeam($em, $t);
      $this->Teams[] = $MV;
    }
    
    foreach($games as $g)
    {
      if ($g->getHomeTeam()->getDivision()->getId() == $division->getId())
      {
        $RG = new MatrixGame();
        $RG->gameID = $g->getId();
        $RG->HomeTeam = $this->GetTeamWithID($g->getHomeTeam()->getId());
        $RG->AwayTeam = $this->GetTeamWithID($g->getAwayTeam()->getId());
        $RG->day = $g->getShortDate();
        $this->ExistingGames[] = $RG;	
      }
    }
    
    foreach($rounds as $r)
    {
      $MR = new MatrixRound($r, $this->Teams, $division);
      $this->Rounds[] = $MR;
    }
		
    //Required Games
    $TimesMore = floor($division->getGamesPerTeam()/(count($this->Teams)-1));
    $RequiredString = "";
    $RequiredNum = 0;
    $Set = 0;
    while ($TimesMore > -1)
    {
      if ($TimesMore > 1)
      {
        $RequiredNum = 2;
        $RequiredString = "Both";
      }elseif ($TimesMore == 1){
        $RequiredString = "Either";
        $RequiredNum = 1;
      }else{
        $RequiredString = "Optional";
        $RequiredNum = 0;               
      }
      
      $Set += 1;
    
    foreach($this->Teams as $t1)
    {
      $RGSET = new MatrixRequiredSet();
      $RGSET->RequiredString = $RequiredString;
      $RGSET->SetNum = $Set;
      $RGSET->RequiredNum = $RequiredNum;
      foreach($this->Teams as $t2)
      {		
        if ($t1 != $t2)
        {
          $RG = new MatrixGame();
          $RG->HomeTeam = $t1;
          $RG->AwayTeam = $t2;
          $RGSET->HomeGames[] = $RG;
          $RG = new MatrixGame();
          $RG->HomeTeam = $t2;
          $RG->AwayTeam = $t1;
          $RGSET->AwayGames[] = $RG;					
        }
      }
      $t1->RequiredGames[] = $RGSET;
    }
    $TimesMore += -2;
  }
				
}
}

?>
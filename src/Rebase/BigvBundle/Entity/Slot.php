<?php

namespace Rebase\BigvBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Rebase\BigvBundle\Entity\Game;
/**
 * Rebase\BigvBundle\Entity\Slot
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Slot
{
    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="Venue", inversedBy="slots")
     * @ORM\JoinColumn(name="venue_id", referencedColumnName="id")
     */
    protected $venue;
	
	  /**
     * @ORM\OneToOne(targetEntity="Game", inversedBy="slot")
	   * @ORM\JoinColumn(name="game_id", referencedColumnName="id")
    */
    protected $game;
	
	/**
     * @ORM\ManyToOne(targetEntity="Round", inversedBy="slot")
     * @ORM\JoinColumn(name="round_id", referencedColumnName="id")
     */
	 private $round;
	
	/**
     * @ORM\Column(type="integer")
     */
	private $priority;

	/**
     * @ORM\Column(type="datetime")
     */	
	private $start;

	/**
     * @ORM\Column(type="datetime")
     */	
	private $end;
  
    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }


	public function getShortDate()
	{
		return $this->start->format("y-m-d");
	}

  public function getDate()
  {
    return new \DateTime($this->getStart()->format("y-m-d"));
  }
  
   
    /**
     * Set venue
     *
     * @param Rebase\BigvBundle\Entity\Venue $venue
     */
    public function setVenue(\Rebase\BigvBundle\Entity\Venue $venue)
    {
        $this->venue = $venue;
    }

    /**
     * Get venue
     *
     * @return Rebase\BigvBundle\Entity\Venue 
     */
    public function getVenue()
    {
        return $this->venue;
    }

    /**
     * Set priority
     *
     * @param integer $priority
     */
    public function setPriority($priority)
    {
        $this->priority = $priority;
    }

    /**
     * Get priority
     *
     * @return integer 
     */
    public function getPriority()
    {
        return $this->priority;
    }
	public function getPriorityName()
    {
        switch($this->priority)
		{
			case 1: return "OK"; break;
			case 2: return "Good"; break;
			case 3: return "Great"; break;
			
		}
		return "??";
    }
    /**
     * Set start
     *
     * @param datetime $start
     */
    public function setStart($start)
    {
        $this->start = $start;
    }

    /**
     * Get start
     *
     * @return datetime 
     */
    public function getStart()
    {
        return $this->start;
    }

    /**
     * Set end
     *
     * @param datetime $end
     */
    public function setEnd($end)
    {
        $this->end = $end;
    }

    /**
     * Get end
     *
     * @return datetime 
     */
    public function getEnd()
    {
        return $this->end;
    }

    /**
     * Set round
     *
     * @param Rebase\BigvBundle\Entity\Round $round
     */
    public function setRound(\Rebase\BigvBundle\Entity\Round $round)
    {
        $this->round = $round;
    }

    /**
     * Get round
     *
     * @return Rebase\BigvBundle\Entity\Round 
     */
    public function getRound()
    {
        return $this->round;
    }

    /**
     * Set game
     *
     * @param Rebase\BigvBundle\Entity\Game $game
     */
    public function setGame(\Rebase\BigvBundle\Entity\Game $game)
    {
        $this->game = $game;
    }
	public function removeGame()
	{
		if ($this->game)
		{
      $v = $this->game;
      $this->game = NULL;
		  $v->removeSlot();
		}
	}

    /**
     * Get game
     *
     * @return Rebase\BigvBundle\Entity\game 
     */
    public function getGame()
    {
        return $this->game;
    }
}
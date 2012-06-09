<?php

namespace Rebase\BigvBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Rebase\BigvBundle\Entity\Slot;
 
/**
 * @ORM\Entity
 */
class Game
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")    
     */
    private $id;
	
	/**
     * @ORM\Column(type="datetime")
     */	
	private $date;
	
	/**
     * @ORM\ManyToOne(targetEntity="Venue", inversedBy="games")
     * @ORM\JoinColumn(name="venue_id", referencedColumnName="id")
     */
	private $venue;
	
	/**
     * @ORM\OneToOne(targetEntity="Slot", inversedBy="game")
     */
    protected $slot;
	
	/** 
     * @ORM\ManyToOne(targetEntity="Team", inversedBy="HomeGames")
     * @ORM\JoinColumn(name="homeTeam_id", referencedColumnName="id")
     */
	private $homeTeam;
	
	/**
     * @ORM\ManyToOne(targetEntity="Team", inversedBy="AwayGames")
     * @ORM\JoinColumn(name="awayTeam_id", referencedColumnName="id")
     */
	private $awayTeam;

   
   	public function getFriendlyDate()
	{
		return $this->date->format("D j M");
	}
	
	public function getShortDate()
	{
		return $this->date->format("y-m-d");
	}
	
    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set slot
     *
     * @param Rebase\BigvBundle\Entity\Slot $slot
     */
    public function setSlot(\Rebase\BigvBundle\Entity\Slot $slot)
    {
        $this->removeSlot();
        $this->slot = $slot;
        $this->date = $slot->getDate();
        $this->venue = $slot->getVenue();
    }

	public function removeSlot()
	{
		if ($this->slot)
		{
      $v = $this->slot;
      $this->slot = NULL;
		  $v->removeGame();
		}
	}

    /**
     * Get slot
     *
     * @return Rebase\BigvBundle\Entity\Slot 
     */
    public function getSlot()
    {
        return $this->slot;
    }

    /**
     * Set homeTeam
     *
     * @param Rebase\BigvBundle\Entity\Team $homeTeam
     */
    public function setHomeTeam(\Rebase\BigvBundle\Entity\Team $homeTeam)
    {
        $this->homeTeam = $homeTeam;
    }

    /**
     * Get homeTeam
     *
     * @return Rebase\BigvBundle\Entity\Team 
     */
    public function getHomeTeam()
    {
        return $this->homeTeam;
    }

    /**
     * Set awayTeam
     *
     * @param Rebase\BigvBundle\Entity\Team $awayTeam
     */
    public function setAwayTeam(\Rebase\BigvBundle\Entity\Team $awayTeam)
    {
        $this->awayTeam = $awayTeam;
    }

    /**
     * Get awayTeam
     *
     * @return Rebase\BigvBundle\Entity\Team 
     */
    public function getAwayTeam()
    {
        return $this->awayTeam;
    }

    /**
     * Set date
     *
     * @param datetime $date
     */
    public function setDate($date)
    {
        $this->date = $date;
    }

    /**
     * Get date
     *
     * @return datetime 
     */
    public function getDate()
    {
        return $this->date;
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
}
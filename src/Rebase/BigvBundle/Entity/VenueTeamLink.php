<?php

namespace Rebase\BigvBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
 
/**
 * @ORM\Entity
 */
class VenueTeamLink
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")    
     */
    private $id;
	
	/**
     * @ORM\ManyToOne(targetEntity="Team", inversedBy="VenueTeamLink")
     * @ORM\JoinColumn(name="team_id", referencedColumnName="id")
     */
	private $team;

	/**
     * @ORM\ManyToOne(targetEntity="Venue", inversedBy="VenueTeamLink")
     * @ORM\JoinColumn(name="venue_id", referencedColumnName="id")
     */
	private $venue;
	

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
     * Set team
     *
     * @param Rebase\BigvBundle\Entity\Team $team
     */
    public function setTeam(\Rebase\BigvBundle\Entity\Team $team)
    {
        $this->team = $team;
    }

    /**
     * Get team
     *
     * @return Rebase\BigvBundle\Entity\Team 
     */
    public function getTeam()
    {
        return $this->team;
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
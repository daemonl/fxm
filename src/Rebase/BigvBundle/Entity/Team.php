<?php

namespace Rebase\BigvBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
 
/**
 * @ORM\Entity
 */
class Team
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")    
     */
    private $id;
	
	/**
     * @ORM\ManyToOne(targetEntity="Club", inversedBy="teams")
     * @ORM\JoinColumn(name="club_id", referencedColumnName="id")
     */
	private $club;
	
	  /**
     * @ORM\ManyToOne(targetEntity="Division", inversedBy="teams")
     * @ORM\JoinColumn(name="division_id", referencedColumnName="id")
     */
	private $division;

	 /**
     * @ORM\OneToMany(targetEntity="VenueTeamLink", mappedBy="team")
     */
    protected $VenueTeamLink;
	
    /**
     * @ORM\OneToMany(targetEntity="Game", mappedBy="homeTeam")
     */
    protected $HomeGames;

    /**
     * @ORM\OneToMany(targetEntity="Game", mappedBy="awayTeam")
     */
    protected $AwayGames;  
    public function __construct()
    {
        $this->HomeGames = new \Doctrine\Common\Collections\ArrayCollection();
    $this->AwayGames = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set club
     *
     * @param Rebase\BigvBundle\Entity\Club $club
     */
    public function setClub(\Rebase\BigvBundle\Entity\Club $club)
    {
        $this->club = $club;
    }

    /**
     * Get club
     *
     * @return Rebase\BigvBundle\Entity\Club 
     */
    public function getClub()
    {
        return $this->club;
    }

    /**
     * Set division
     *
     * @param Rebase\BigvBundle\Entity\Division $division
     */
    public function setDivision(\Rebase\BigvBundle\Entity\Division $division)
    {
        $this->division = $division;
    }

    /**
     * Get division
     *
     * @return Rebase\BigvBundle\Entity\Division 
     */
    public function getDivision()
    {
        return $this->division;
    }

    /**
     * Add HomeGames
     *
     * @param Rebase\BigvBundle\Entity\Game $homeGames
     */
    public function addGame(\Rebase\BigvBundle\Entity\Game $homeGames)
    {
        $this->HomeGames[] = $homeGames;
    }

    /**
     * Get HomeGames
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getHomeGames()
    {
        return $this->HomeGames;
    }

    /**
     * Get AwayGames
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getAwayGames()
    {
        return $this->AwayGames;
    }

    /**
     * Set HomeVenue
     *
     * @param Rebase\BigvBundle\Entity\Venue $homeVenue
     */
    public function setHomeVenue(\Rebase\BigvBundle\Entity\Venue $homeVenue)
    {
        $this->HomeVenue = $homeVenue;
    }


    /**
     * Add VenueTeamLink
     *
     * @param Rebase\BigvBundle\Entity\VenueTeamLink $venueTeamLink
     */
    public function addVenueTeamLink(\Rebase\BigvBundle\Entity\VenueTeamLink $venueTeamLink)
    {
        $this->VenueTeamLink[] = $venueTeamLink;
    }

    /**
     * Get VenueTeamLink
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getVenueTeamLink()
    {
        return $this->VenueTeamLink;
    }
}
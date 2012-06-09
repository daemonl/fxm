<?php

namespace Rebase\BigvBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
 
/**
 * @ORM\Entity
 */
class ClubSeasonLink
{
  /**
    * @ORM\Id
    * @ORM\Column(type="integer")
    * @ORM\GeneratedValue(strategy="AUTO")    
    */
  private $id;
	
	/**
    * @ORM\ManyToOne(targetEntity="Season", inversedBy="ClubSeasonLinks")
    * @ORM\JoinColumn(name="season_id", referencedColumnName="id")
    */
	private $season;

  	/**
    * @ORM\ManyToOne(targetEntity="Club", inversedBy="csls")
    * @ORM\JoinColumn(name="club_id", referencedColumnName="id")
    */
	private $club;
  
  
    /**
   * @ORM\OneToMany(targetEntity="VenueSeasonLink", mappedBy="season")
   */
    protected $VenueSeasonLinks;
    
    
      
	/** 
   * @ORM\OneToMany(targetEntity="Team", mappedBy="csl")
   */
  protected $teams;
  
    /**
    * @ORM\ManyToOne(targetEntity="VenueSeasonLink", inversedBy="club")
    * @ORM\JoinColumn(name="vsl_id", referencedColumnName="id")
    */
  private $vsl;
    

   
    public function __construct()
    {
        $this->teams = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set season
     *
     * @param Rebase\BigvBundle\Entity\Season $season
     */
    public function setSeason(\Rebase\BigvBundle\Entity\Season $season)
    {
        $this->season = $season;
    }

    /**
     * Get season
     *
     * @return Rebase\BigvBundle\Entity\Season 
     */
    public function getSeason()
    {
        return $this->season;
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
     * Add teams
     *
     * @param Rebase\BigvBundle\Entity\Team $teams
     */
    public function addTeam(\Rebase\BigvBundle\Entity\Team $teams)
    {
        $this->teams[] = $teams;
    }

    /**
     * Get teams
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getTeams()
    {
        return $this->teams;
    }

    /**
     * Set vsl
     *
     * @param Rebase\BigvBundle\Entity\VenueSeasonLink $vsl
     */
    public function setVsl(\Rebase\BigvBundle\Entity\VenueSeasonLink $vsl)
    {
        $this->vsl = $vsl;
    }

    /**
     * Get vsl
     *
     * @return Rebase\BigvBundle\Entity\VenueSeasonLink 
     */
    public function getVsl()
    {
        return $this->vsl;
    }

    /**
     * Add VenueSeasonLinks
     *
     * @param Rebase\BigvBundle\Entity\VenueSeasonLink $venueSeasonLinks
     */
    public function addVenueSeasonLink(\Rebase\BigvBundle\Entity\VenueSeasonLink $venueSeasonLinks)
    {
        $this->VenueSeasonLinks[] = $venueSeasonLinks;
    }

    /**
     * Get VenueSeasonLinks
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getVenueSeasonLinks()
    {
        return $this->VenueSeasonLinks;
    }
}
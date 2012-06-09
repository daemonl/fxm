<?php

namespace Rebase\BigvBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
 
/**
 * @ORM\Entity
 */
class VenueSeasonLink
{
  /**
    * @ORM\Id
    * @ORM\Column(type="integer")
    * @ORM\GeneratedValue(strategy="AUTO")    
    */
  private $id;
	
	/**
    * @ORM\ManyToOne(targetEntity="Season", inversedBy="VenueSeasonLinks")
    * @ORM\JoinColumn(name="season_id", referencedColumnName="id")
    */
	private $season;

	/**
     * @ORM\ManyToOne(targetEntity="Venue", inversedBy="VenueSeasonLinks")
     * @ORM\JoinColumn(name="venue_id", referencedColumnName="id")
     */
	private $venue;
  
	/**
   * @ORM\OneToMany(targetEntity="Court", mappedBy="vsl")
   */
  protected $courts;

  	/**
   * @ORM\OneToMany(targetEntity="ClubSeasonLink", mappedBy="vsl")
   */
  protected $ClubSeasonLinks;
  
   
    public function __construct()
    {
        $this->courts = new \Doctrine\Common\Collections\ArrayCollection();
    $this->ClubSeasonLinks = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Add courts
     *
     * @param Rebase\BigvBundle\Entity\Court $courts
     */
    public function addCourt(\Rebase\BigvBundle\Entity\Court $courts)
    {
        $this->courts[] = $courts;
    }

    /**
     * Get courts
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getCourts()
    {
        return $this->courts;
    }

    /**
     * Add ClubSeasonLinks
     *
     * @param Rebase\BigvBundle\Entity\ClubSeasonLink $clubSeasonLinks
     */
    public function addClubSeasonLink(\Rebase\BigvBundle\Entity\ClubSeasonLink $clubSeasonLinks)
    {
        $this->ClubSeasonLinks[] = $clubSeasonLinks;
    }

    /**
     * Get ClubSeasonLinks
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getClubSeasonLinks()
    {
        return $this->ClubSeasonLinks;
    }
}
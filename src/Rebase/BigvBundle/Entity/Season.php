<?php

namespace Rebase\BigvBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
 
/**
 * @ORM\Entity
 */
class Season
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")    
     */
    private $id;

    /**
    * @ORM\Column(type="string", length=100)
    */
    private $name;
	
	 /**
    * @ORM\Column(type="string", length=4)
    */
    private $shortname;
    
    /**
    * @ORM\Column(type="datetime")
    */
    private $start;
    
    /**
    * @ORM\Column(type="datetime")
    */
    private $stop;
    
	
    /**
     * @ORM\ManyToOne(targetEntity="League", inversedBy="seasons")
     */
    protected $league;
    
    /**
     * @ORM\OneToMany(targetEntity="Club", mappedBy="season")
     */
    protected $clubs;
    
    /**
     * @ORM\OneToMany(targetEntity="Division", mappedBy="season")
     */
    protected $divisions;
    
    /**
     * @ORM\OneToMany(targetEntity="Venue", mappedBy="season")
     */
    protected $venues;
    
	  /**
     * @ORM\OneToMany(targetEntity="Round", mappedBy="season")
     */
    protected $rounds; 
    
    /**
   * @ORM\OneToMany(targetEntity="VenueSeasonLink", mappedBy="season")
   */
    protected $VenueSeasonLinks;

        /**
   * @ORM\OneToMany(targetEntity="ClubSeasonLink", mappedBy="season")
   */
    protected $ClubSeasonLinks;
    
    public function __construct()
    {
        $this->clubs = new \Doctrine\Common\Collections\ArrayCollection();
    $this->divisions = new \Doctrine\Common\Collections\ArrayCollection();
    $this->venues = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set name
     *
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set shortname
     *
     * @param string $shortname
     */
    public function setShortname($shortname)
    {
        $this->shortname = $shortname;
    }

    /**
     * Get shortname
     *
     * @return string 
     */
    public function getShortname()
    {
        return $this->shortname;
    }

    /**
     * Set league
     *
     * @param Rebase\BigvBundle\Entity\League $league
     */
    public function setLeague(\Rebase\BigvBundle\Entity\League $league)
    {
        $this->league = $league;
    }

    /**
     * Get league
     *
     * @return Rebase\BigvBundle\Entity\League 
     */
    public function getLeague()
    {
        return $this->league;
    }

    /**
     * Add clubs
     *
     * @param Rebase\BigvBundle\Entity\Club $clubs
     */
    public function addClub(\Rebase\BigvBundle\Entity\Club $clubs)
    {
        $this->clubs[] = $clubs;
    }

    /**
     * Get clubs
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getClubs()
    {
        return $this->clubs;
    }

    /**
     * Add divisions
     *
     * @param Rebase\BigvBundle\Entity\Division $divisions
     */
    public function addDivision(\Rebase\BigvBundle\Entity\Division $divisions)
    {
        $this->divisions[] = $divisions;
    }

    /**
     * Get divisions
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getDivisions()
    {
        return $this->divisions;
    }

    /**
     * Add venues
     *
     * @param Rebase\BigvBundle\Entity\Venue $venues
     */
    public function addVenue(\Rebase\BigvBundle\Entity\Venue $venues)
    {
        $this->venues[] = $venues;
    }

    /**
     * Get venues
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getVenues()
    {
        return $this->venues;
    }

    /**
     * Set start
     *
     * @param dateTime $start
     */
    public function setStart(\dateTime $start)
    {
        $this->start = $start;
    }

    /**
     * Get start
     *
     * @return dateTime 
     */
    public function getStart()
    {
        return $this->start;
    }

    /**
     * Set stop
     *
     * @param dateTime $stop
     */
    public function setStop(\dateTime $stop)
    {
        $this->stop = $stop;
    }

    /**
     * Get stop
     *
     * @return dateTime 
     */
    public function getStop()
    {
        return $this->stop;
    }

    /**
     * Add rounds
     *
     * @param Rebase\BigvBundle\Entity\Round $rounds
     */
    public function addRound(\Rebase\BigvBundle\Entity\Round $rounds)
    {
        $this->rounds[] = $rounds;
    }

    /**
     * Get rounds
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getRounds()
    {
        return $this->rounds;
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
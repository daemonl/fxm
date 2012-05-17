<?php

namespace Rebase\BigvBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
 
/**
 * @ORM\Entity
 */
class Venue
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")    
     */
    private $id;

   /**
    * @ORM\ManyToOne(targetEntity="Season", inversedBy="venues")
    * @ORM\JoinColumn(name="league_id", referencedColumnName="id")
    */
	 private $league;
    
    /**
    * @ORM\Column(type="string", length=100)
    */
    private $name;
	
	/**
    * @ORM\Column(type="string", length=500, nullable=true)
    */
    private $FullName;
	
	/**
    * @ORM\Column(type="string", length=1000, nullable=true)
    */
    private $Address;	
	
    /**
     * @ORM\OneToMany(targetEntity="Slot", mappedBy="venue")
     */
    protected $slots;
    
     /**
     * @ORM\OneToMany(targetEntity="Game", mappedBy="venue")
     */
    protected $games;
	
	 /**
     * @ORM\OneToMany(targetEntity="VenueTeamLink", mappedBy="venue")
     */
    protected $VenueTeamLink;
	 
    public function __construct()
    {
        $this->slots = new ArrayCollection(); 
    } 
	 
	 public function __toString()
	 {
		 return $this->getName();
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
     * Get slots
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getSlots()
    {
        return $this->slots;
    }

    /**
     * Add slots
     *
     * @param Rebase\BigvBundle\Entity\Slot $slots
     */
    public function addSlot(\Rebase\BigvBundle\Entity\Slot $slots)
    {
        $this->slots[] = $slots;
    }

    /**
     * Set FullName
     *
     * @param string $fullName
     */
    public function setFullName($fullName)
    {
        $this->FullName = $fullName;
    }

    /**
     * Get FullName
     *
     * @return string 
     */
    public function getFullName()
    {
        return $this->FullName;
    }

    /**
     * Set Address
     *
     * @param string $address
     */
    public function setAddress($address)
    {
        $this->Address = $address;
    }

    /**
     * Get Address
     *
     * @return string 
     */
    public function getAddress()
    {
        return $this->Address;
    }

    /**
     * Add games
     *
     * @param Rebase\BigvBundle\Entity\Game $games
     */
    public function addGame(\Rebase\BigvBundle\Entity\Game $games)
    {
        $this->games[] = $games;
    }

    /**
     * Get games
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getGames()
    {
        return $this->games;
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

    /**
     * Set league
     *
     * @param Rebase\BigvBundle\Entity\Season $league
     */
    public function setSeason(\Rebase\BigvBundle\Entity\Season $league)
    {
        $this->league = $league;
    }

    /**
     * Get league
     *
     * @return Rebase\BigvBundle\Entity\Season 
     */
    public function getSeason()
    {
        return $this->league;
    }

    /**
     * Set league
     *
     * @param Rebase\BigvBundle\Entity\Season $league
     */
    public function setLeague(\Rebase\BigvBundle\Entity\Season $league)
    {
        $this->league = $league;
    }

    /**
     * Get league
     *
     * @return Rebase\BigvBundle\Entity\Season 
     */
    public function getLeague()
    {
        return $this->league;
    }
}
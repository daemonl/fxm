<?php

namespace Rebase\BigvBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class Venue {

  /**
   * @ORM\Id
   * @ORM\Column(type="integer")
   * @ORM\GeneratedValue(strategy="AUTO")    
   */
  protected $id;

  /**
   * @ORM\ManyToOne(targetEntity="Season", inversedBy="venues")
   * @ORM\JoinColumn(name="league_id", referencedColumnName="id")
   */
  protected $league;

  /**
   * @ORM\Column(type="string", length=100)
   */
  protected $name;

  /**
   * @ORM\Column(type="string", length=500, nullable=true)
   */
  protected $FullName;

  /**
   * @ORM\Column(type="string", length=1000, nullable=true)
   */
  protected $Address;

  /**
   * @ORM\OneToMany(targetEntity="Court", mappedBy="venue")
   */
  protected $courts;

  /**
   * @ORM\OneToMany(targetEntity="VenueTeamLink", mappedBy="venue")
   */
  protected $VenueTeamLinks;
  /**
   * @ORM\OneToMany(targetEntity="VenueSeasonLink", mappedBy="venue")
   */
  protected $VenueSeasonLinks;

  
  
  
  public function ThisSeason()
  {
    foreach ($this->VenueSeasonLinks as $vsl)
    {
      if ($vsl->getSeason()->getId() == \Rebase\BigvBundle\Statics\Context::$season->getId())
      {
        return $vsl;
      }
    }
    return null;
  }
  
  

    public function __construct()
    {
        $this->courts = new \Doctrine\Common\Collections\ArrayCollection();
    $this->VenueTeamLinks = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set league
     *
     * @param \Rebase\BigvBundle\Entity\League $league
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
     * Add VenueTeamLinks
     *
     * @param Rebase\BigvBundle\Entity\VenueTeamLink $venueTeamLinks
     */
    public function addVenueTeamLink(\Rebase\BigvBundle\Entity\VenueTeamLink $venueTeamLinks)
    {
        $this->VenueTeamLinks[] = $venueTeamLinks;
    }

    /**
     * Get VenueTeamLinks
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getVenueTeamLinks()
    {
        return $this->VenueTeamLinks;
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
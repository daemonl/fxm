<?php

namespace Rebase\BigvBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table()
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
  protected $VenueTeamLink;

    public function __construct()
    {
        $this->courts = new \Doctrine\Common\Collections\ArrayCollection();
    $this->VenueTeamLink = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @param Rebase\BigvBundle\Entity\Season $league
     */
    public function setLeague(\Rebase\BigvBundle\Entity\League $league)
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
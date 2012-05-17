<?php

namespace Rebase\BigvBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
 
/**
 * @ORM\Entity
 */
class Club
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")    
     */
    private $id;

  /**
   * @ORM\ManyToOne(targetEntity="Season", inversedBy="clubs")
   * @ORM\JoinColumn(name="season_id", referencedColumnName="id")
   */
	 private $season;
     
    /**
    * @ORM\Column(type="string", length=100)
    */
    private $name;
	
	/**
    * @ORM\Column(type="string", length=4)
    */
    private $shortname;
	
	  /**
     * @ORM\ManyToOne(targetEntity="Venue", inversedBy="club")
     * @ORM\JoinColumn(name="venue_id", referencedColumnName="id")
     */
	  private $venue;
	
    /**
     * @ORM\OneToMany(targetEntity="Team", mappedBy="club")
     */
    protected $teams;
	 

  
    
    public function __construct()
    {
        $this->teams = new ArrayCollection(); 
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
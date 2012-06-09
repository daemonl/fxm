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
   * @ORM\JoinColumn(name="league_id", referencedColumnName="id")
   */
	 private $league;
     
   
   	/**
    * @ORM\OneToMany(targetEntity="ClubSeasonLink", mappedBy="club")
    */
    protected $csls;
  
    /**
    * @ORM\Column(type="string", length=100)
    */
    private $name;
	
	/**
    * @ORM\Column(type="string", length=4)
    */
    private $shortname;
	

  public function ThisSeason()
  {
    foreach ($this->csls as $csl)
    {
      if ($csl->getSeason()->getId() == \Rebase\BigvBundle\Statics\Context::$season->getId())
      {
        return $csl;
      }
    }
    return null;
  }
	 

  
    public function __construct()
    {
        $this->csls = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Add csls
     *
     * @param Rebase\BigvBundle\Entity\ClubSeasonLink $csls
     */
    public function addClubSeasonLink(\Rebase\BigvBundle\Entity\ClubSeasonLink $csls)
    {
        $this->csls[] = $csls;
    }

    /**
     * Get csls
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getCsls()
    {
        return $this->csls;
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
}
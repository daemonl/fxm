<?php

namespace Rebase\BigvBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
 
/**
 * @ORM\Entity
 */
class Division
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")    
     */
    private $id;

      /**
   * @ORM\ManyToOne(targetEntity="Season", inversedBy="divisions")
   * @ORM\JoinColumn(name="season_id", referencedColumnName="id")
   */
	private $season;
  
    /**
    * @ORM\Column(type="string", length=100)
    */
    private $name;
	
	/**
    * @ORM\Column(type="integer", length=3)
    */
    private $GamesPerTeam;
	
	/**
    * @ORM\Column(type="string", length=300)
    */
    private $FullName;
	
    /**
     * @ORM\OneToMany(targetEntity="Team", mappedBy="division")
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
     * Set GamesPerTeam
     *
     * @param integer $gamesPerTeam
     */
    public function setGamesPerTeam($gamesPerTeam)
    {
        $this->GamesPerTeam = $gamesPerTeam;
    }

    /**
     * Get GamesPerTeam
     *
     * @return integer 
     */
    public function getGamesPerTeam()
    {
        return $this->GamesPerTeam;
    }

    /**
     * Set league
     *
     * @param Rebase\BigvBundle\Entity\Season $season
     */
    public function setSeason(\Rebase\BigvBundle\Entity\Season $season)
    {
        $this->season = $season;
    }

    /**
     * Get league
     *
     * @return Rebase\BigvBundle\Entity\Season 
     */
    public function getSeason()
    {
        return $this->season;
    }

}
<?php

namespace Rebase\BigvBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
 
/**
 * @ORM\Entity
 */
class League
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
     * @ORM\OneToMany(targetEntity="Season", mappedBy="league")
     */
    protected $seasons;
    
    /**
     * @ORM\OneToMany(targetEntity="UserPermission", mappedBy="league")
     */
    protected $users;
    
    public function __construct()
    {
        
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
     * Add seasons
     *
     * @param Rebase\BigvBundle\Entity\Season $seasons
     */
    public function addSeason(\Rebase\BigvBundle\Entity\Season $seasons)
    {
        $this->seasons[] = $seasons;
    }

    /**
     * Get seasons
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getSeasons()
    {
        return $this->seasons;
    }

    /**
     * Add users
     *
     * @param Rebase\BigvBundle\Entity\UserPermission $users
     */
    public function addUserPermission(\Rebase\BigvBundle\Entity\UserPermission $users)
    {
        $this->users[] = $users;
    }

    /**
     * Get users
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getUsers()
    {
        return $this->users;
    }
}
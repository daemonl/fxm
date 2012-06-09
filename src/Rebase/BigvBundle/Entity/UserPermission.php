<?php

namespace Rebase\BigvBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
 
/**
 * @ORM\Entity
 */
class UserPermission
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")    
     */
    private $id;

    
    /**
     * @ORM\Column(type="integer", name="user_id")
     */
    private $userID;
    
  
  	/** 
     * @ORM\ManyToOne(targetEntity="League", inversedBy="users")
     * @ORM\JoinColumn(name="league_id", referencedColumnName="id")
     */
	 private $league;

    
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
     * Set userID
     *
     * @param integer $userID
     */
    public function setUserID($userID)
    {
        $this->userID = $userID;
    }

    /**
     * Get userID
     *
     * @return integer 
     */
    public function getUserID()
    {
        return $this->userID;
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
}
<?php

namespace Rebase\BigvBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
 
/**
 * @ORM\Entity
 */
class Court
{
  /**
   * @ORM\Id
   * @ORM\Column(type="integer")
   * @ORM\GeneratedValue(strategy="AUTO")    
   */
  private $id;
    
  /**
   * @ORM\ManyToOne(targetEntity="Venue", inversedBy="games")
   * @ORM\JoinColumn(name="venue_id", referencedColumnName="id")
   */
	private $venue;
  
  /**
   * @ORM\Column(type="string", length=100)
   */
  private $name;
  
  /**
   * @ORM\OneToMany(targetEntity="Slot", mappedBy="court")
   */
  private $slots;
    public function __construct()
    {
        $this->slots = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Add slots
     *
     * @param Rebase\BigvBundle\Entity\Slot $slots
     */
    public function addSlot(\Rebase\BigvBundle\Entity\Slot $slots)
    {
        $this->slots[] = $slots;
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
}
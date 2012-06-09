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
   * @ORM\ManyToOne(targetEntity="VenueSeasonLink", inversedBy="courts")
   * @ORM\JoinColumn(name="vsl_id", referencedColumnName="id")
   */
	private $vsl;
  
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
     * Set vsl
     *
     * @param Rebase\BigvBundle\Entity\VenueSeasonLink $vsl
     */
    public function setVsl(\Rebase\BigvBundle\Entity\VenueSeasonLink $vsl)
    {
        $this->vsl = $vsl;
    }

    /**
     * Get vsl
     *
     * @return Rebase\BigvBundle\Entity\VenueSeasonLink 
     */
    public function getVsl()
    {
        return $this->vsl;
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
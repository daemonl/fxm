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
}
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

}
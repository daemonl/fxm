<?php

namespace Rebase\BigvBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Rebase\BigvBundle\Entity\Round
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Round
{
    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

  /**
   * @ORM\ManyToOne(targetEntity="Season", inversedBy="rounds")
   * @ORM\JoinColumn(name="season_id", referencedColumnName="id")
   */
	 private $season;
     
    
   	/**
     * @ORM\Column(type="integer")
     */
	private $number;

 	/**
     * @ORM\OneToMany(targetEntity="Slot", mappedBy="round")
     */
    protected $slots;
	
	/**
     * @ORM\Column(type="date")
     */	
	private $start;

	/**
     * @ORM\Column(type="date")
     */	
	private $end;
  
  
    public function __construct()
    {
        $this->slots = new \Doctrine\Common\Collections\ArrayCollection();
    }
	
   public function getDays()
   {
		//Check that the start is before the end.
		if ($this->start < $this->end)
		{
			$ar = array();
			$cd = $this->start;
			while($cd <= $this->end)
			{
			  $ar[] = $cd->format("y-m-d");
			  $cd->add(new \DateInterval("P1D"));
			}
			return $ar;
		}else{
			return array();
		}
		//Return an array of simple date strings for each day.   
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
     * Set start
     *
     * @param date $start
     */
    public function setStart($start)
    {
        $this->start = $start;
    }

    /**
     * Get start
     *
     * @return date 
     */
    public function getStart()
    {
        return $this->start;
    }

   public function getRawStart()
	{
		return $this->start->format("y-m-d");
	}
    /**
     * Set end
     *
     * @param date $end
     */
    public function setEnd($end)
    {
        $this->end = $end;
    }

    /**
     * Get end
     *
     * @return date 
     */
    public function getEnd()
    {
        return $this->end;
    }
	
	public function getRawEnd()
	{
		return $this->end->format("y-m-d");
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

    /**
     * Set number
     *
     * @param integer $number
     */
    public function setNumber($number)
    {
        $this->number = $number;
    }

    /**
     * Get number
     *
     * @return integer 
     */
    public function getNumber()
    {
        return $this->number;
    }

    /**
     * Set season
     *
     * @param Rebase\BigvBundle\Entity\Season $season
     */
    public function setSeason(\Rebase\BigvBundle\Entity\Season $season)
    {
        $this->season = $season;
    }

    /**
     * Get season
     *
     * @return Rebase\BigvBundle\Entity\Season 
     */
    public function getSeason()
    {
        return $this->season;
    }
}
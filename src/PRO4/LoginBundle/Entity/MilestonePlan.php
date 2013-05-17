<?php

namespace PRO4\LoginBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * MilestonePlan
 */
class MilestonePlan
{
    /**
     * @var integer
     */
    private $milestonePlanId;

    /**
     * @var \DateTime
     */
    private $startDate;

    /**
     * @var \DateTime
     */
    private $endDate;

    /**
     * @var \PRO4\LoginBundle\Entity\Project
     */
    private $project;


    /**
     * Get milestonePlanId
     *
     * @return integer 
     */
    public function getMilestonePlanId()
    {
        return $this->milestonePlanId;
    }

    /**
     * Set startDate
     *
     * @param \DateTime $startDate
     * @return MilestonePlan
     */
    public function setStartDate($startDate)
    {
        $this->startDate = $startDate;
    
        return $this;
    }

    /**
     * Get startDate
     *
     * @return \DateTime 
     */
    public function getStartDate()
    {
        return $this->startDate;
    }

    /**
     * Set endDate
     *
     * @param \DateTime $endDate
     * @return MilestonePlan
     */
    public function setEndDate($endDate)
    {
        $this->endDate = $endDate;
    
        return $this;
    }

    /**
     * Get endDate
     *
     * @return \DateTime 
     */
    public function getEndDate()
    {
        return $this->endDate;
    }

    /**
     * Set project
     *
     * @param \PRO4\LoginBundle\Entity\Project $project
     * @return MilestonePlan
     */
    public function setProject(\PRO4\LoginBundle\Entity\Project $project)
    {
        $this->project = $project;
    
        return $this;
    }

    /**
     * Get project
     *
     * @return \PRO4\LoginBundle\Entity\Project 
     */
    public function getProject()
    {
        return $this->project;
    }
}

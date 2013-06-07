<?php

namespace PRO4\ToDoListBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ToDoList
 *
 * @ORM\Table(name="to_do_list")
 * @ORM\Entity
 */
class ToDoList
{
    /**
     * @var integer
     *
     * @ORM\Column(name="to_do_list_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $toDoListId;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=40, nullable=false)
     */
    private $name;

    /**
     * @var boolean
     *
     * @ORM\Column(name="completed", type="boolean", nullable=false)
     */
    private $completed;

    /**
     * @var \Department
     *
     * @ORM\ManyToOne(targetEntity="PRO4\ProjectBundle\Entity\Department")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="department_id", referencedColumnName="department_id")
     * })
     */
    private $department;

    /**
     * @var \Project
     *
     * @ORM\ManyToOne(targetEntity="PRO4\ProjectBundle\Entity\Project")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="project_id", referencedColumnName="project_id", nullable=false)
     * })
     */
    private $project;



    /**
     * Get toDoListId
     *
     * @return integer 
     */
    public function getToDoListId()
    {
        return $this->toDoListId;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return ToDoList
     */
    public function setName($name)
    {
        $this->name = $name;
    
        return $this;
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
     * Set completed
     *
     * @param boolean $completed
     * @return ToDoList
     */
    public function setCompleted($completed)
    {
        $this->completed = $completed;
    
        return $this;
    }

    /**
     * Get completed
     *
     * @return boolean 
     */
    public function getCompleted()
    {
        return $this->completed;
    }

    /**
     * Set department
     *
     * @param \PRO4\ProjectBundle\Entity\Department $department
     * @return ToDoList
     */
    public function setDepartment(\PRO4\ProjectBundle\Entity\Department $department = null)
    {
        $this->department = $department;
    
        return $this;
    }

    /**
     * Get department
     *
     * @return \PRO4\ProjectBundle\Entity\Department 
     */
    public function getDepartment()
    {
        return $this->department;
    }

    /**
     * Set project
     *
     * @param \PRO4\ProjectBundle\Entity\Project $project
     * @return ToDoList
     */
    public function setProject(\PRO4\ProjectBundle\Entity\Project $project = null)
    {
        $this->project = $project;
    
        return $this;
    }

    /**
     * Get project
     *
     * @return \PRO4\ProjectBundle\Entity\Project 
     */
    public function getProject()
    {
        return $this->project;
    }
}
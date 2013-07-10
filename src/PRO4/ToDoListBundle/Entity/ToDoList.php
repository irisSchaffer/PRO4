<?php

namespace PRO4\ToDoListBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * ToDoList
 *
 * @ORM\Table(name="to_do_list")
 * @ORM\Entity(repositoryClass="PRO4\ToDoListBundle\Entity\ToDoListRepository")
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
     * 
     * @ORM\OneToMany(targetEntity="PRO4\ToDoListBundle\Entity\ListItem", mappedBy="toDoList", cascade={"persist"})
     * @ORM\OrderBy({"completed" = "ASC", "name" = "ASC"})
     **/
     private $listItems;
     
     private $departmentId;
    

	public function __construct() {
		$this->listItems = new ArrayCollection();
		$this->completed = false;
	}


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
    	if($completed == true) {
    		foreach($this->listItems as $item) {
        	$item->complete();
        }
    	}
        $this->completed = $completed;
        
        return $this;
    }

    /**
     * Get completed
     *
     * @return boolean 
     */
    public function isCompleted()
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

    /**
     * Add listItem
     *
     * @param \PRO4\ToDoListBundle\Entity\ListItem $listItem
     * @return ToDoList
     */
    public function addListItem(\PRO4\ToDoListBundle\Entity\ListItem $listItem)
    {
        $this->listItems[] = $listItem;
        $listItem->setToDoList($this);
    
        return $this;
    }

    /**
     * Remove listItems
     *
     * @param \PRO4\ToDoListBundle\Entity\ListItem $listItems
     */
    public function removeListItem(\PRO4\ToDoListBundle\Entity\ListItem $listItems)
    {
        $this->listItems->removeElement($listItems);
        $listItem->setToDoList(null);
    }

    /**
     * Get listItems
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getListItems()
    {
        return $this->listItems;
    }
    
    public function setDepartmentId($departmentId) {
    	$this->departmentId = $departmentId;
    	
    	return $this;
    }
    
    public function getDepartmentId() {
    	return $this->departmentId;
    }
}
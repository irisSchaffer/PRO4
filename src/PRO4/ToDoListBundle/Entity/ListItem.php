<?php

namespace PRO4\ToDoListBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ListItem
 *
 * @ORM\Table(name="list_item")
 * @ORM\Entity(repositoryClass="PRO4\ToDoListBundle\Entity\ListItemRepository")
 */
class ListItem
{
    /**
     * @var integer
     *
     * @ORM\Column(name="list_item_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $listItemId;

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
     * @var boolean
     *
     * @ORM\Column(name="deleted", type="boolean", nullable=false)
     */
    private $deleted;

    /**
     * @var \ToDoList
     *
     * @ORM\ManyToOne(targetEntity="ToDoList")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="to_do_list_id", referencedColumnName="to_do_list_id", nullable=false)
     * })
     */
    private $toDoList;
	
	
	public function __construct() {
		$this->completed = false;
		$this->deleted = false;
	}


    /**
     * Get listItemId
     *
     * @return integer 
     */
    public function getListItemId()
    {
        return $this->listItemId;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return ListItem
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
     * Set completed true
     *
     * @return ListItem
     */
    public function complete()
    {
        $this->completed = true;
    
        return $this;
    }
    
    public function undo()
    {
        $this->completed = false;
    
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
     * Set toDoList
     *
     * @param \PRO4\ToDoListBundle\Entity\ToDoList $toDoList
     * @return ListItem
     */
    public function setToDoList(\PRO4\ToDoListBundle\Entity\ToDoList $toDoList = null)
    {
        $this->toDoList = $toDoList;
    
        return $this;
    }

    /**
     * Get toDoList
     *
     * @return \PRO4\ToDoListBundle\Entity\ToDoList 
     */
    public function getToDoList()
    {
        return $this->toDoList;
    }

    /**
     * Set deleted true
     *
     * @param boolean $deleted
     * @return ListItem
     */
    public function delete()
    {
        $this->deleted = true;
    
        return $this;
    }

    /**
     * Get deleted
     *
     * @return boolean 
     */
    public function isDeleted()
    {
        return $this->deleted;
    }
}
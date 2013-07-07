<?php

namespace PRO4\UserBundle\Entity;

use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * User
 *
 * @ORM\Table(name="user")
 * @ORM\Entity(repositoryClass="PRO4\UserBundle\Entity\UserRepository")
 * @UniqueEntity("eMail")
 */
class User implements UserInterface
{
    /**
     * @var integer
     *
     * @ORM\Column(name="user_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $userId;

    /**
     * @var string $eMail
     *
     * @ORM\Column(name="eMail", type="string", length=100, unique=true, nullable=false)
     * 
     * @Assert\Email()
     */
    private $eMail;
	
	/**
     * @ORM\Column(name="salt", type="string", length=32, nullable=false)
     */
    private $salt;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=40, nullable=false)
     */
    private $password;

    /**
     * @var boolean
     *
     * @ORM\Column(name="activated", type="boolean")
     */
    private $activated;
    
    /**
     * @ORM\ManyToMany(targetEntity="\PRO4\ProjectBundle\Entity\Project", mappedBy="users")
     * @ORM\JoinTable(name="user_in_project",
     *      joinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="user_id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="project_id", referencedColumnName="project_id")}
     *      )
     */
    private $projects;
    
    /**
     * @ORM\ManyToMany(targetEntity="\PRO4\ProjectBundle\Entity\Department", mappedBy="users")
     * @ORM\JoinTable(name="user_in_department",
     *      joinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="user_id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="department_id", referencedColumnName="department_id")}
     *      )
     */
    private $departments;
    
    private $isOwner;
    private $isOperator;
    private $isAdmin;
    private $isViewer;


	public function __construct()
    {
        $this->activated = true;
        $this->salt = md5(uniqid(null, true));
        $this->projects = new ArrayCollection();
        $this->departments = new ArrayCollection();
        
        $this->isOwner = false;
        $this->isOperator = false;
        $this->isAdmin = false;
        $this->isViewer = false;
    }

    /**
     * Get userId
     *
     * @return integer 
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * Set eMail
     *
     * @param string $eMail
     * @return User
     */
    public function setEMail($eMail)
    {
        $this->eMail = $eMail;
    
        return $this;
    }

    /**
     * Get eMail
     *
     * @return string 
     */
    public function getEMail()
    {
        return $this->eMail;
    }

    /**
     * Set password
     *
     * @param string $password
     * @return User
     */
    public function setPassword($password)
    {
        $this->password = $password;
    
        return $this;
    }

    /**
     * activate user
     *
     * @return User
     */
    public function activate()
    {
        $this->activated = true;
    
        return $this;
    }

	/**
     * deactivate user
     *
     * @return User
     */
    public function deactivate()
    {
        $this->activated = false;
    
        return $this;
    }

    /**
     * Get activation-status
     *
     * @return boolean 
     */
    public function isActivated()
    {
        return $this->activated;
    }
	
	/**
     * @inheritDoc
     */
    public function getUsername()
    {
        return $this->eMail;
    }

    /**
     * @inheritDoc
     */
    public function getSalt()
    {
        return $this->salt;
    }

    /**
     * @inheritDoc
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @inheritDoc
     */
    public function getRoles()
    {
        return array('ROLE_USER');
    }

    /**
     * @inheritDoc
     */
    public function eraseCredentials()
    {
    }

    /**
     * @see \Serializable::serialize()
     */
    public function serialize()
    {
        return serialize(array(
            $this->id,
        ));
    }

    /**
     * @see \Serializable::unserialize()
     */
    public function unserialize($serialized)
    {
        list (
            $this->id,
        ) = unserialize($serialized);
    }

    /**
     * Set salt
     *
     * @param string $salt
     * @return User
     */
    public function setSalt($salt)
    {
        $this->salt = $salt;
    
        return $this;
    }

    /**
     * Set activated
     *
     * @param boolean $activated
     * @return User
     */
    public function setActivated($activated)
    {
        $this->activated = $activated;
    
        return $this;
    }

    /**
     * Get activated
     *
     * @return boolean 
     */
    public function getActivated()
    {
        return $this->activated;
    }

    /**
     * Add projects
     *
     * @param \PRO4\ProjectBundle\Entity\Project $projects
     * @return User
     */
    public function addProject(\PRO4\ProjectBundle\Entity\Project $project)
    {
        $this->projects[] = $project;
    
        return $this;
    }

    /**
     * Remove projects
     *
     * @param \PRO4\ProjectBundle\Entity\Project $projects
     */
    public function removeProject(\PRO4\ProjectBundle\Entity\Project $project)
    {
        $this->projects->removeElement($project);
    }

    /**
     * Get projects
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getProjects()
    {
        return $this->projects;
    }

    /**
     * Add department
     *
     * @param \PRO4\ProjectBundle\Entity\Department $department
     * @return User
     */
    public function addDepartment(\PRO4\ProjectBundle\Entity\Department $department)
    {
        $this->departments[] = $department;
    
        return $this;
    }

    /**
     * Remove department
     *
     * @param \PRO4\ProjectBundle\Entity\Department $department
     */
    public function removeDepartment(\PRO4\ProjectBundle\Entity\Department $department)
    {
        $this->departments->removeElement($department);
    }

    /**
     * Get departments
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getDepartments()
    {
        return $this->departments;
    }
    
    public function setIsOwner($isOwner) {
    	$this->isOwner = $isOwner;
    	$this->isOperator = $isOwner;
    	$this->isAdmin = $isOwner;
        $this->isViewer = $isOwner;
    	
    	return $this;
    }
    
    public function isOwner() {
    	return $this->isOwner;
    }
    
    public function setIsOperator($isOperator) {
    	$this->isOwner = false;
    	    	
    	$this->isOperator = $isOperator;
    	$this->isAdmin = $isOperator;
        $this->isViewer = $isOperator;
    	
    	return $this;
    }
    
    public function isOperator() {
    	return $this->isOperator;
    }
    
    public function setIsAdmin($isAdmin) {
    	$this->isOwner = false;
    	$this->isOperator = false;
    	
    	$this->isAdmin = $isAdmin;
        $this->isViewer = $isAdmin;
    	
    	return $this;
    }
    
    public function isAdmin() {
    	return $this->isAdmin;
    }
    
    public function setIsViewer($isViewer) {
    	$this->isOwner = false;
    	$this->isOperator = false;
    	$this->isAdmin = false;
    	
    	$this->isViewer = $isViewer;
    	
    	return $this;
    }
    
    public function isViewer() {
    	return $this->isViewer;
    }
    
    public function __sleep(){
	   return array('userId', 'eMail');
	}
}
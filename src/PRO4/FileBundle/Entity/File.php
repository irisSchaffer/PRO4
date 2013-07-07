<?php
namespace PRO4 \ FileBundle \ Entity;

use Doctrine \ ORM \ Mapping as ORM;
use Symfony \ Component \ Validator \ Constraints as Assert;
use Symfony \ Component \ HttpFoundation \ File \ UploadedFile;

/**
 * File
 *
 * @ORM\Table(name="file")
 * @ORM\Entity(repositoryClass="FileRepository")
 * @ORM\HasLifecycleCallbacks
 */
class File {
	const UPLOAD_DIR = "uploads";
	
	
	/**
	 * @var integer
	 *
	 * @ORM\Column(name="file_id", type="integer", nullable=false)
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	private $fileId;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="name", type="string", length=100, nullable=false)
	 * @Assert\NotBlank()
	 * @Assert\Length(min = "3", max = "100")
	 * 
	 */
	private $name;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="path", type="string", length=100, nullable=false)
	 * @Assert\NotBlank()
	 * @Assert\Length(min = "3", max = "100")
	 */
	private $path;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="description", type="text", nullable=true)
	 */
	private $description;

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
	 * @var \Department
	 *
	 * @ORM\ManyToOne(targetEntity="PRO4\ProjectBundle\Entity\Department")
	 * @ORM\JoinColumns({
	 *   @ORM\JoinColumn(name="department_id", referencedColumnName="department_id")
	 * })
	 */
	private $department;

	/**
	 * @Assert\File(maxSize="6000000")
	 */
	private $file;

	private $temp;

    /**
     * Sets file.
     *
     * @param UploadedFile $file
     */
    public function setFile(UploadedFile $file = null)
    {
        $this->file = $file;
        // check if we have an old image path
        if (isset($this->path)) {
            // store the old name to delete after the update
            $this->temp = $this->path;
            $this->path = null;
        } else {
            $this->path = 'initial';
        }
    }

    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function preUpload()
    {
        if (null !== $this->getFile()) {
            // do whatever you want to generate a unique name
            $filename = sha1(uniqid(mt_rand(), true));
            $this->path = $filename.'.'.$this->getFile()->guessExtension();
        }
    }

    /**
     * @ORM\PostPersist()
     * @ORM\PostUpdate()
     */
    public function upload()
    {
        if (null === $this->getFile()) {
            return;
        }

        // if there is an error when moving the file, an exception will
        // be automatically thrown by move(). This will properly prevent
        // the entity from being persisted to the database on error
        $this->getFile()->move($this->getUploadRootDir(), $this->path);

        // check if we have an old image
        if (isset($this->temp)) {
            // delete the old image
            unlink($this->getUploadRootDir().'/'.$this->temp);
            // clear the temp image path
            $this->temp = null;
        }
        $this->file = null;
    }

    /**
     * @ORM\PostRemove()
     */
    public function removeUpload()
    {
        if ($file = $this->getAbsolutePath()) {
            unlink($file);
        }
    }

	public function getAbsolutePath() {
		return null === $this->path ? null : $this->getUploadRootDir() . '/' . sha1($this->fileId) . '.' . $this->path;
	}

	public function getWebPath() {
		return null === $this->path ? null : File::UPLOAD_DIR . '/' . $this->path;
	}

	protected function getUploadRootDir() {
		// the absolute directory path where uploaded
		// documents should be saved
		return __DIR__ . '/../../../../web/' . File::UPLOAD_DIR;
	}

	/**
	 * Get file.
	 *
	 * @return UploadedFile
	 */
	public function getFile() {
		return $this->file;
	}

	/**
	 * Get fileId
	 *
	 * @return integer 
	 */
	public function getFileId() {
		return $this->fileId;
	}

	/**
	 * Set name
	 *
	 * @param string $name
	 * @return File
	 */
	public function setName($name) {
		$this->name = $name;

		return $this;
	}

	/**
	 * Get name
	 *
	 * @return string 
	 */
	public function getName() {
		return $this->name;
	}

	/**
	 * Set path
	 *
	 * @param string $path
	 * @return File
	 */
	public function setPath($path) {
		$this->path = $path;

		return $this;
	}

	/**
	 * Get path
	 *
	 * @return string 
	 */
	public function getPath() {
		return $this->path;
	}

	/**
	 * Set description
	 *
	 * @param string $description
	 * @return File
	 */
	public function setDescription($description) {
		$this->description = $description;

		return $this;
	}

	/**
	 * Get description
	 *
	 * @return string 
	 */
	public function getDescription() {
		return $this->description;
	}

	/**
	 * Set project
	 *
	 * @param \PRO4\PRO4\ProjectBundle\Entity\Project $project
	 * @return File
	 */
	public function setProject(\ PRO4 \ ProjectBundle \ Entity \ Project $project = null) {
		$this->project = $project;

		return $this;
	}

	/**
	 * Get project
	 *
	 * @return \PRO4\ProjectBundle\Entity\Project 
	 */
	public function getProject() {
		return $this->project;
	}

	/**
	 * Set department
	 *
	 * @param \PRO4\ProjectBundle\Entity\Department $department
	 * @return File
	 */
	public function setDepartment(\ PRO4 \ ProjectBundle \ Entity \ Department $department = null) {
		$this->department = $department;

		return $this;
	}

	/**
	 * Get department
	 *
	 * @return \PRO4\ProjectBundle\Entity\Department 
	 */
	public function getDepartment() {
		return $this->department;
	}
	
	/**
	 * @return name of image that should be used in frontend
	 */
	public function getImageName() {
		$ending = strtolower(substr($this->path, strrpos($this->path, '.') + 1));
		
		switch($ending) {
			case "jpeg":
			case "jpg":
			case "gif":
			case "png":
			case "tiff":
				$name = "image";
				break;
				
			case "psd":
				$name = "psd";
				break;
				
			case "ai":
				$name = "illustrator";
				break;
				
			case "zip":
			case "tar":
			case "rar":
			case "7z":
				$name = "compressed";
				break;
				
			case "pdf":
				$name = "pdf";
				break;
				
			case "ppt":
			case "pptx":
			case "pptm":
				$name = "powerpoint";
				break;
				
			case "doc":
			case "docx":
				$name = "word";
				break;
				
			case "xls":
			case "xlsx":
				$name = "excel";
				break;
				
			case "txt":
			case "text":
			case "readme":
			case "tex":
			case "utf8":
				$name = "text";
				break;
				
			case "php":
			case "sql":
			case "html":
			case "htm":
			case "xml":
			case "js":
			case "py":
				$name = "code";
				break;
			
			case "java":
			case "class";
			case "cpp":
			case "h":
			case "c":
				$name = "developer";
				break;
			
			case "css":
				$name = "css";
				break;
			
			default:
				$name = "blank";
				break;
		}
		
		return "img/extensions/" . $name . ".png";
		
	}
}
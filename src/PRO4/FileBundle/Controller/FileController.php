<?php

namespace PRO4\FileBundle\Controller;

use PRO4\MainBundle\Controller\MyController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;

use PRO4\FileBundle\Entity\File;
use PRO4\FileBundle\Form\Type\MyFileType;

class FileController extends MyController
{
    public function indexAction(Request $request, $projectId) {
    	$project = $this->find("\PRO4\ProjectBundle\Entity\Project", $projectId);
    	
		$file = new File();
    	$file->setProject($project);
    	$file->setDepartment(null);
    	
    	$em = $this->getDoctrine()->getManager();
    	$files = $em->getRepository("PRO4FileBundle:File")->findByProjectWithoutDepartment($project)->getQuery()->getResult();
    	
    	return $this->showFiles($file, $files, $projectId);
    }
    
    public function filesInDepartmentAction(Request $request, $projectId, $departmentId) {
    	$project = $this->find("\PRO4\ProjectBundle\Entity\Project", $projectId);
    	$department = $this->find("\PRO4\ProjectBundle\Entity\Department", $departmentId);
    	
    	$this->checkPermission("VIEW", $department);
    	
		$file = new File();
    	$file->setProject($project);
    	$file->setDepartment($department);
    	
    	$em = $this->getDoctrine()->getManager();
    	$files = $em->getRepository("PRO4FileBundle:File")->findByDepartment($department);
    	
    	$parameters = array("department" => $department);
    	
    	return $this->showFiles($file, $files, $projectId, $parameters);
    }
    
    public function deleteFileAction($projectId, $fileId, $departmentId = null) {
    	$project = $this->find("\PRO4\ProjectBundle\Entity\Project", $projectId);
    	$file = $this->find("\PRO4\FileBundle\Entity\File", $fileId);
    	$department = ($departmentId === null ? null : $this->find("\PRO4\ProjectBundle\Entity\Department", $departmentId));
    	
    	if($file->getProject() !== $project || $file->getDepartment() !== $department) {
    		throw new InvalidArgumentException();
    	}    	
    	
    	if($department !== null) {
    		$this->checkPermission("EDIT", $department);
    	} else {
    		$this->checkPermission("EDIT", $project);
    	}
    	
    	$em = $this->getDoctrine()->getManager();
    	$em->remove($file);
    	$em->flush();
    	
    	if($department !== null) {
    		$redirect = $this->generateUrl("files_in_department", array("projectId" => $projectId, "departmentId" => $departmentId));
    	} else {
    		$redirect = $this->generateUrl("files", array("projectId" => $projectId));
    	}
    	
    	return $this->redirect($redirect);
		
    }
    
    private function showFiles($file, $files, $projectId, array $parameters = array()) {
    	$project = $this->find("\PRO4\ProjectBundle\Entity\Project", $projectId);
    	$departments = $this->getUser()->getDepartments();
    	
    	$this->checkPermission("VIEW", $project);
    	
    	$form = $this->createForm(new MyFileType(), $file);	
    	 
    	$request = $this->getRequest();
    	if($request->isMethod("POST")) {
    		$form->bind($request);
    		if ($form->isValid()) {
			    $em = $this->getDoctrine()->getManager();
			    $em->persist($file);
			    $em->flush();
			}
			
			$this->get('session')->getFlashBag()->add(
				    "success",
				    "You successfully added the file \"" . $file->getName() . "\"!"
				);
			
			header("location: ".$request->getUri());
  			exit();
    	}
    	
    	$parameters["form"] = $form->createView();
    	$parameters["files"] = $files;
    	$parameters["departments"] = $departments;
    	$parameters["project"] = $project;
    	
    	return $this->render('PRO4FileBundle:File:fileForm.html.twig', $parameters);
    }
    
}

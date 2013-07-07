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
    	
		$file = new File();
    	$file->setProject($project);
    	$file->setDepartment($department);
    	
    	$em = $this->getDoctrine()->getManager();
    	$files = $em->getRepository("PRO4FileBundle:File")->findByDepartment($department);
    	
    	$parameters = array("department" => $department);
    	
    	return $this->showFiles($file, $files, $projectId, $parameters);
    }
    
    private function showFiles($file, $files, $projectId, array $parameters = array()) {
    	$project = $this->find("\PRO4\ProjectBundle\Entity\Project", $projectId);
    	$departments = $this->getUser()->getDepartments();
    	
    	$form = $this->createForm(new MyFileType(), $file, array("attr" => array("required" => false)));	
    	 
    	$request = $this->getRequest();
    	if($request->isMethod("POST")) {
    		$form->bind($request);
    		if ($form->isValid()) {
			    $em = $this->getDoctrine()->getManager();
			    $em->persist($file);
			    $em->flush();
			}
    	}
    	
    	$parameters["form"] = $form->createView();
    	$parameters["files"] = $files;
    	$parameters["departments"] = $departments;
    	$parameters["project"] = $project;
    	
    	return $this->render('PRO4FileBundle:File:fileForm.html.twig', $parameters);
    }
    
}

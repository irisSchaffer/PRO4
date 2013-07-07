<?php

namespace PRO4\FileBundle\Controller;

use PRO4\MainBundle\Controller\MyController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;

use PRO4\FileBundle\Entity\File;
use PRO4\FileBundle\Form\Type\FileType;

class FileController extends MyController
{
    public function indexAction($projectId)
    {
    	$project = $this->find("\PRO4\ProjectBundle\Entity\Project", $projectId);
    	$departments = $project->getDepartments();
    	$em = $this->getDoctrine()->getManager();
    	$files = $em->getRepository("PRO4FileBundle:File")->findFilesByProject($project)->getQuery()->getResult();
    	
    	$file = new File();
    	$file->setProject($project);
    	$file->setDepartment(null);
    	
    	$form = $this->createForm(new FileType(), $file, array("attr" => array("required" => false)));
    	
    	return $this->render(
			'PRO4FileBundle:File:index.html.twig',
			array(
				"files" => $files,
				"project" => $project
			)
		);
    }
    
}

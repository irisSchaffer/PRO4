<?php

namespace PRO4\FileBundle\Entity;

use Doctrine\ORM\EntityRepository;

class FileRepository extends EntityRepository {
	
    public function findFilesForProject(\PRO4\ProjectBundle\Entity\Project $project, array $departments) {
        $qb = $this->createQueryBuilder('f')
				->where('f.project = :project')
				->andwhere('f.department is null')
    			->setParameters(
    				array(
						'project' => $project
					)
				);

    	return $qb;
    }
    
    public function findFilesForDepartment(\PRO4\ProjectBundle\Entity\Department $department) {
        $qb = $this->createQueryBuilder('f')
				->where('f.department = :department')
				->orderBy('f.name', 'ASC')
    			->setParameters(
    				array(
						'department' => $department
					)
				);

    	return $qb;
    }
}
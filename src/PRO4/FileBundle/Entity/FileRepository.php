<?php

namespace PRO4\FileBundle\Entity;

use Doctrine\ORM\EntityRepository;

class FileRepository extends EntityRepository {
	
    public function findByProjectWithoutDepartment(\PRO4\ProjectBundle\Entity\Project $project) {
        $qb = $this->createQueryBuilder('f')
				->where('f.project = :project')
				->andwhere('f.department is null')
    			->setParameter('project', $project);

    	return $qb;
    }
}
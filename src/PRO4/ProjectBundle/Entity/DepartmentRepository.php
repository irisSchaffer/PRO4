<?php

namespace PRO4\ProjectBundle\Entity;

use Doctrine\ORM\EntityRepository;

class DepartmentRepository extends EntityRepository {
    public function findByProject(\PRO4\ProjectBundle\Entity\Project $project) {
        return $this->createQueryBuilder('d')
				->where('d.project = :project')
				->orderBy('d.name', 'ASC')
    			->setParameter('project', $project);
    }
    
    public function findByProjectAndUser(\PRO4\ProjectBundle\Entity\Project $project, \PRO4\UserBundle\Entity\User $user) {
    	return $this->createQueryBuilder('d')
				->where('d.project = :project')
				->innerJoin('d.users', 'u', 'WITH', 'u.userId = :userId')
				->orderBy('d.name', 'ASC')
    			->setParameter('project', $project)
    			->setParameter('userId', $user->getUserId());
    }
}
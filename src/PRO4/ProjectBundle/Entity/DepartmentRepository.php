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
}
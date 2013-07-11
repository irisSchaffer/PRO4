<?php

namespace PRO4\ToDoListBundle\Entity;

use Doctrine\ORM\EntityRepository;

class ToDoListRepository extends EntityRepository {
	
    public function findToDoListsForProject(\PRO4\ProjectBundle\Entity\Project $project, array $departments) {
        $qb = $this->createQueryBuilder('t')
				->where('t.project = :project')
				->andwhere('t.completed = :completed')
				->orderBy('t.name', 'ASC')
    			->setParameters(
    				array(
						'project' => $project,
						'completed' => false,
					)
				);
				
				
		if(count($departments) > 0) {
	 		$qb = $qb
	 			->andwhere('t.department is null OR t.department IN (:departments)')
	 			->setParameter('departments', $departments);
	 	} else {
	 		$qb->andwhere('t.department is null');
	 	}

    	return $qb;
    }
}
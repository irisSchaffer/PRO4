<?php

namespace PRO4\CalendarBundle\Entity;

use Doctrine\ORM\EntityRepository;

class EventRepository extends EntityRepository {
	 public function findEventsForProject($day, \PRO4\ProjectBundle\Entity\Project $project, array $departments) {
        $qb = $this->createQueryBuilder('e')
				->where('e.project = :project')
				->andwhere('e.date = :date')
    			->setParameters(
    				array(
						'project' => $project,
						'date' => $day->format("Y-m-d"),
					)
				);

		if(count($departments) > 0) {
	 		$qb = $qb
	 			->andwhere('e.department is null OR e.department IN (:departments)')
	 			->setParameter('departments', $departments);
	 	} else {
	 		$qb->andwhere('e.department is null');
	 	}
	 	
	 	$qb->orderBy('e.time', 'ASC');

    	return $qb;
    }
}
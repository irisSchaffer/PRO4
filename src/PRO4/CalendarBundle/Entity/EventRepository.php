<?php

namespace PRO4\CalendarBundle\Entity;

use Doctrine\ORM\EntityRepository;

class EventRepository extends EntityRepository {
	 public function findEventsForProject($day, \PRO4\ProjectBundle\Entity\Project $project, array $departments) {
        $qb = $this->createQueryBuilder('e')
				->where('e.project = :project')
				->andwhere('e.department is null')
				->orderBy('e.time', 'ASC')
				->andwhere('e.date = :date')
    			->setParameters(
    				array(
						'project' => $project,
						'date' => $day->format("Y-m-d"),
					)
				);

		if(count($departments) > 0) {
	 		$qb = $qb
	 			->orwhere('e.department IN (:departments)')
	 			->setParameter('departments', $departments);
	 	}

    	return $qb;
    }
}
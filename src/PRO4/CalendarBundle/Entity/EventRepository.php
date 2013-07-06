<?php

namespace PRO4\CalendarBundle\Entity;

use Doctrine\ORM\EntityRepository;

class EventRepository extends EntityRepository {
	 public function findEventsForProject($day, \PRO4\ProjectBundle\Entity\Project $project, array $departments) {
        $qb = $this->createQueryBuilder('e')
				->where('e.project = :project')
				->andwhere('e.department is null')
				->orwhere('e.department IN (:departments)')
				->orderBy('e.time', 'ASC')
				->andwhere('e.date = :date')
    			->setParameters(
    				array(
						'project' => $project,
						'departments' => $departments,
						'date' => $day->format("Y-m-d"),
					)
				);

    	return $qb;
    }
}
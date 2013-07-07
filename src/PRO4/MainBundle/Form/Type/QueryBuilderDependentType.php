<?php

namespace PRO4\MainBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\QueryBuilder;

class QueryBuilderDependentType extends AbstractType
{
	
	private $queryBuilder;
	
	public function __construct(QueryBuilder $queryBuilder) {
    	$this->queryBuilder = $queryBuilder;
    }

    public function getName()
    {
        return 'queryBuilderDependent';
    }
    
    public function getQueryBuilder() {
    	return $this->queryBuilder;
    }
    
    public function setQueryBuilder(QueryBuilder $queryBuilder) {
    	$this->queryBuilder = $queryBuilder;
    	
    	return $this;
    }
}
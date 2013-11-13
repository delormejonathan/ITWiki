<?php

namespace Wiki\IndexBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * ArticleRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ArticleRepository extends EntityRepository
{
	
	public function articlesByTag(Tag $tag = null)
	{
		$query = $this->createQueryBuilder('a')
								->leftJoin('a.creator', 'c')
								->addSelect('c')
								->orderBy('a.create' , 'DESC');
		if (null !== $tag)
		{
			$query->where(':tag MEMBER OF a.tags')
				  ->setParameter('tag', $tag);
		}

		return $query->getQuery()->getResult();
	}
}

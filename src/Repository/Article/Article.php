<?php

namespace App\Repository\Article;

use App\Model\Article\Status;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Symfony\Component\HttpFoundation\Request;

class Article extends EntityRepository
{
    private const MAX_RESULTS = 15;

    public function fromRequest(Request $request): array
    {
        $qb = $this->createQueryBuilder('a');
        $qb->from(\App\Model\Article\Article::class, 'article');
        $rq = $request->query;
        if ($rq->has('publishingDate')) {
            $qb->andWhere($qb->expr()->between('article.publishingDate', ':dateStart', ':dateEnd'));
            $dt = strtotime($rq->get('publishingDate'));
            $qb->setParameter('dateStart', date('Y-m-d 00:00:00', $dt));
            $qb->setParameter('dateEnd', date('Y-m-d 23:59:59', $dt));
        }
        if ($rq->has('title')) {
            $qb->andWhere($qb->expr()->like('article.title', ':title'));
            $qb->setParameter('title', '%' . $rq->get('title') . '%');
        }
        if ($rq->has('status')) {
            $qb->where($qb->expr()->eq('article.status', ':status'));
            $qb->setParameter('status', $rq->get('status'));
        }

        $this->ordering($qb, $rq->all());
        $qb->setMaxResults($this->maxResults((int) $rq->getDigits('limit')));

        return $qb->getQuery()->getResult();
    }

    private function ordering(QueryBuilder $q, array $params): void
    {
        if (isset($params['order'], $params['direction'])) {
            $q->orderBy('article.' . $params['order'], $params['direction']);
        }
    }

    private function maxResults(int $limit): int
    {
        if ($limit > 0) {
            return min($limit, self::MAX_RESULTS);
        }

        return self::MAX_RESULTS;
    }
}

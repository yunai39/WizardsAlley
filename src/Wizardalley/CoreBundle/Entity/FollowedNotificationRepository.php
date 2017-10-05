<?php

namespace Wizardalley\CoreBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Wizardalley\DefaultBundle\Controller\BaseController;

/**
 * FollowedNotificationRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class FollowedNotificationRepository extends EntityRepository
{

    /**
     * @param int $id_user
     * @param int $page
     * @param int $limit
     *
     * @return array
     */
    public function findNotification(
        $id_user,
        $page = 1,
        $limit = BaseController::BASE_LIMIT
    ) {
        $firstResult = ($page - 1) * $limit;

        $qb = $this->_em->createQueryBuilder()->select('p')->from($this->_entityName, 'p');
        $query = $qb
            ->join('p.user', 'u')
            ->where('u.id = :id')
            ->orderBy('p.dataNotification', 'DESC')
            ->setFirstResult($firstResult)
            ->setMaxResults($limit)
            ->setParameter(':id', $id_user)
            ->getQuery();
        $result = $query->getResult();

        return $result;
    }


    /**
     * @param int $id_user
     * @param int $page
     * @param int $limit
     * @param int $status
     *
     * @return array
     */
    public function findStatusNotification(
        $id_user,
        $page = 1,
        $status,
        $limit = BaseController::BASE_LIMIT
    ) {
        $firstResult = ($page - 1) * $limit;

        $qb = $this->_em->createQueryBuilder()->select('p')->from($this->_entityName, 'p');
        $query = $qb
            ->join('p.user', 'u')
            ->where('u.id = :id')
            ->andWhere('p.checked = :checked')
            ->orderBy('p.dataNotification', 'DESC')
            ->setFirstResult($firstResult)
            ->setMaxResults($limit)
            ->setParameter(':id', $id_user)
            ->setParameter(':checked', $status)
            ->getQuery();
        $result = $query->getResult();

        return $result;
    }
}

<?php

namespace Wizardalley\CoreBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Wizardalley\DefaultBundle\Controller\BaseController;

/**
 * PageRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class PageRepository extends EntityRepository
{
    /**
     * @param     $id_page
     * @param int $limit
     *
     * @return array
     */
    public function findLatestFollower($id_page, $limit = BaseController::BASE_LIMIT)
    {
        $qb     = $this->_em->createQueryBuilder()
                            ->select('u')
                            ->from('WizardalleyCoreBundle:WizardUser', 'u');
        $query  = $qb
            ->join('u.pagesFollowed', 'p')
            ->where('p.page = :id')
            ->orderBy('p.dateInscription', 'desc')
            ->setMaxResults($limit)
            ->setParameter(':id', $id_page)
            ->getQuery();
        $result = $query->getArrayResult();

        return $result;
    }

    /**
     * @param $id_page
     *
     * @return array
     */
    public function findPage($id_page)
    {
        $qb     = $this->_em->createQueryBuilder()
                            ->select('p')
                            ->from('WizardalleyCoreBundle:Page', 'p');
        $query  = $qb
            ->where('p.id = :id')
            ->setParameter(':id', $id_page)
            ->getQuery();
        $result = $query->getArrayResult();

        return $result;
    }

    /**
     * @param     $id_user
     * @param int $page
     * @param int $limit
     *
     * @return array
     */
    public function findPageFollowedUser($id_user, $page = 1, $limit = BaseController::BASE_LIMIT)
    {
        $offset = $limit * ($page - 1);
        $query  = $this->_em->createQuery("SELECT p FROM Wizardalley\CoreBundle\Entity\Page p join p.followers puf join puf.user u WHERE u.id = ?1 ORDER BY puf.dateInscription ");
        $query->setMaxResults($limit)
              ->setFirstResult($offset)
              ->setParameter(1, $id_user);

        return $query->getScalarResult();
    }

    /**
     * @param     $id_user
     * @param int $page
     * @param int $limit
     *
     * @return array
     */
    public function findPageCreatedUser($id_user, $page = 1, $limit = BaseController::BASE_LIMIT)
    {
        $offset = $limit * ($page - 1);
        $query  = $this->_em->createQuery("SELECT p FROM Wizardalley\CoreBundle\Entity\Page p join p.creator u  WHERE u.id = ?1 ORDER BY p.id ");
        $query->setMaxResults($limit)
              ->setFirstResult($offset)
              ->setParameter(1, $id_user);

        return $query->getScalarResult();
    }

    /**
     * @param     $id_user
     * @param int $page
     * @param int $limit
     *
     * @return array
     */
    public function findPageEditorUser($id_user, $page = 1, $limit = BaseController::BASE_LIMIT)
    {
        $offset = $limit * ($page - 1);
        $query  = $this->_em->createQuery("SELECT p FROM Wizardalley\CoreBundle\Entity\Page p join p.editors u  WHERE u.id = ?1 ORDER BY p.id ");
        $query->setMaxResults($limit)
              ->setFirstResult($offset)
              ->setParameter(1, $id_user);

        return $query->getScalarResult();
    }

    /**
     * @param     $like
     * @param int $page
     * @param int $limit
     *
     * @return array
     */
    public function findPagesLike($like, $page = 1, $limit = BaseController::BASE_LIMIT)
    {
        $firstResult = ($page - 1) * $limit;

        $qb    = $this->_em->createQueryBuilder()
                           ->select('p')
                           ->from($this->_entityName, 'p');
        $query = $qb
            ->where('p.name LIKE :like')
            ->orderBy('p.name', 'DESC')
            ->setFirstResult($firstResult)
            ->setMaxResults($limit)
            ->setParameter(':like', '%' . $like . '%')
            ->getQuery();

        $result = $query->getResult();

        return $result;
    }


    /**
     * @return array
     */
    public function findPageThisMonth()
    {
        $qb     = $this->_em->createQueryBuilder()->select('p')->from($this->_entityName, 'p');
        $query  = $qb->where('p.createdAt > :date')
                     ->setParameter(':date', (new \DateTime())->format('Y-m'))
                     ->getQuery()
        ;
        $result = $query->getArrayResult();

        return $result;
    }
}

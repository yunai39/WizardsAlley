<?php

namespace Wizardalley\CoreBundle\Entity;

use Wizardalley\CoreBundle\Entity\WizardUser;
use Doctrine\ORM\EntityRepository;

/**
 * UserConnectedRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class WizardUserRepository extends EntityRepository
{
    /**
     *
     * @param WizardUser $user
     * @param int        $page
     * @param int        $limit
     *
     * @return array
     */
    public function findFriends(WizardUser $user, $page, $limit)
    {
        $offset = $limit * ($page - 1);
        $sql    = "
        select distinct w.username, w.id,w.path_profile
            from friends f1
                left join friends f2 on f2.user_id = f1.friend_user_id 
                left join wizard_user w on w.id = f2.user_id
            where
                f2.friend_user_id = ? and
                f1.user_id = ?
            limit {$offset}, {$limit}
                ";
        $conn   = $this->getEntityManager()->getConnection();
        $stmt   = $conn->prepare($sql);
        $stmt->execute(array($user->getId(), $user->getId()));

        return $stmt->fetchAll();
    }

    /**
     *
     * @param WizardUser $user
     * @param int        $publication_id
     * @param int        $limit
     *
     * @return array
     */
    public function findPublicationUser(WizardUser $user, $publication_id, $limit)
    {
        $sql    = "
            (
            select 
                pu.id as 'publication_id', 
                ap.datePublication, 
                pu.title, 
                pu.small_content as 'content', 
                pa.id as 'writer_id', 
                pa.name, pa.path_profile, 
                'page_publication' as type
            from 
                abstract_publication ap
                    left join publication pu 
                    on ap.id = pu.id
                left join page pa on pu.page_id = pa.id
                left join page_user_follow puf on puf.page_id = pa.id
                left join comment_publication cp on cp.publication_id = pu.id
            where puf.wizard_user_id = :user_id_1
                and ( pu.id < :publication_id or :publication_id = -1)
            )
            UNION
            (
            select 
                pu.id as 'publication_id', 
                ap.datePublication, 
                '' as title, 
                ap.content, 
                w.id as 'writer_id', 
                w.username as 'name', 
                w.path_profile, 
                'user_publication' as type
            from 
                abstract_publication ap
                    left join small_publication pu
                    on ap.id = pu.id
                left join wizard_user w on w.id = ap.user_id
                left join friends f1 on f1.user_id = ap.user_id
                left join friends f2 on f1.friend_user_id = f2.user_id
                left join comment_publication csp on csp.publication_id = pu.id
            where f1.friend_user_id = :user_id_2
                and ( pu.id < :publication_id or :publication_id = -1)
            )
            ORDER BY publication_id DESC
            LIMIT " . $limit . "
            ";
        $conn   = $this->getEntityManager()->getConnection();
        $stmt   = $conn->prepare($sql);
        $stmt->execute(array(
            'user_id_1'      => $user->getId(),
            'user_id_2'      => $user->getId(),
            'publication_id' => $publication_id
        ));

        return $stmt->fetchAll();
    }

    /**
     *
     * @param WizardUser $user
     * @param int        $offset
     * @param int        $limit
     *
     * @return array
     */
    public function findPublication(WizardUser $user, $offset, $limit)
    {
        $sql    = "
            (
            select 
                pu.id as 'publication_id', 
                pa.datePublication, 
                pu.title, pu.small_content as 'content', 
                pa.id as 'writer_id', 
                p.name, 
                p.path_profile, 
                'page_publication' as type
            from publication pu 
                left join abstract_publication pa
                on pa.id = pu.id
              left join page p on pu.page_id = p.id 
            where pa.user_id = :user_id_1)
            UNION
            (
            select 
                pa.id as 'publication_id', 
                pa.datePublication, 
                '' as title, 
                pa.content, 
                w.id as 'writer_id', 
                w.username as 'name', 
                w.path_profile, 
                'user_publication' as type
            from small_publication pu
                left join abstract_publication pa
                on pa.id = pu.id
            left join wizard_user w on w.id = pa.user_id
             where pa.user_id = :user_id_2
            )
            ORDER BY datePublication DESC
            LIMIT " . $offset . ", " . $limit . "
            ";
        $conn   = $this->getEntityManager()->getConnection();
        $stmt   = $conn->prepare($sql);
        $stmt->execute(array(
            'user_id_1' => $user->getId(),
            'user_id_2' => $user->getId(),
        ));

        return $stmt->fetchAll();
    }

    /**
     * @param WizardUser $user
     * @param int        $id_last
     * @param int        $limit
     *
     * @return array
     * @throws \Doctrine\DBAL\DBALException
     */
    public function findPublicationWall(WizardUser $user, $id_last, $limit)
    {
        if ($id_last) {
            $sqlLimit = "LIMIT 1," . $limit;
            $sqlWhere = "AND pa.id < " . $id_last;
        } else {
            $sqlLimit = "LIMIT " . $limit;
            $sqlWhere = "";
        }
        $sql    = "
            (
            select 
                pu.id as 'publication_id', 
                pa.datePublication, 
                pu.title, pu.small_content as 'content', 
                pa.id as 'writer_id', 
                p.name, 
                p.path_profile, 
                'page_publication' as type
            from publication pu 
                left join abstract_publication pa
                on pa.id = pu.id
              left join page p on pu.page_id = p.id 
            where pa.user_id = :user_id_1 {$sqlWhere})
            UNION
            (
            select 
                pa.id as 'publication_id', 
                pa.datePublication, 
                '' as title, 
                pa.content, 
                w.id as 'writer_id', 
                w.username as 'name', 
                w.path_profile, 
                'user_publication' as type
            from small_publication pu
                left join abstract_publication pa
                on pa.id = pu.id
            left join wizard_user w on w.id = pa.user_id
             where pa.user_id = :user_id_2 {$sqlWhere}
            )
            ORDER BY publication_id DESC
            {$sqlLimit}
            ";
        $conn   = $this->getEntityManager()->getConnection();
        $stmt   = $conn->prepare($sql);
        $stmt->execute(array(
            'user_id_1' => $user->getId(),
            'user_id_2' => $user->getId(),
        ));

        return $stmt->fetchAll();
    }


    /**
     *
     * @param string $search
     *
     * @return array
     */
    public function searchUser($search)
    {

        $sql    = "
        select w.id , w.username as label, w.username as value,w.path_profile
            from wizard_user w
            where
                w.username like ?
                ";
        $conn   = $this->getEntityManager()->getConnection();
        $stmt   = $conn->prepare($sql);
        $stmt->execute(array('%' . $search . '%'));

        return $stmt->fetchAll();
    }

    /**
     *
     * @param string $like
     * @param int    $page
     * @param int    $limit
     *
     * @return array
     */
    public function findUsersLike($like, $page = 1, $limit = 4)
    {
        $firstResult = ($page - 1) * $limit;

        $qb    = $this->_em->createQueryBuilder()->select('u')->from($this->_entityName, 'u');
        $query = $qb
            ->where('u.username LIKE :like')
            ->orderBy('u.username', 'DESC')
            ->setFirstResult($firstResult)
            ->setMaxResults($limit)
            ->setParameter(':like', '%' . $like . '%')
            ->getQuery();

        return $query->getResult();
    }
}

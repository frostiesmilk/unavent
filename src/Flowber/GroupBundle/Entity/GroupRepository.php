<?php

namespace Flowber\GroupBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\ResultSetMapping;

/**
 * GroupRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class GroupRepository extends EntityRepository
{
    public function getInfosGroup($id){  
        $query = $this->_em->createQuery(''
                . 'SELECT groups.id, groups.title, groups.subtitle, groups.description, '
                . 'concat(concat(user.firstname, :espace), user.surname) as createdBy '
                . 'FROM FlowberGroupBundle:Groups groups '
                . 'LEFT JOIN FlowberUserBundle:User user WITH groups.createdBy = user '
                . 'WHERE groups.id = :id');
        $query->setParameter('id', $id);
        $query->setParameter('espace', ' ');
       
        return $query->getSingleResult();
    }

    /*
     * Récupère les id des groupes auquel participe l'user circle
     */
    public function GetGroupsId($circle){
        $sql = "SELECT sub.circle_id "
                . "FROM  subscribers sub "
                . "WHERE sub.subscriber_id=".$circle." "
                . "AND sub.statut='groups' "
                . "ORDER BY sub.creationDate desc";

        
        $rsm = new ResultSetMapping;
        $rsm->addScalarResult('circle_id', 'id');
        
        return $this->getEntityManager()->createNativeQuery($sql, $rsm)->getResult();
    }
    
    /*
     * Récupère tous les id des groupes auquel participe l'user circle
     */
    public function GetAllGroupsId(){
        $sql = "SELECT id "
                . "FROM groups "
                . "LIMIT 12";
        
        $rsm = new ResultSetMapping;
        $rsm->addScalarResult('id', 'id');
        
        return $this->getEntityManager()->createNativeQuery($sql, $rsm)->getResult();
    }    
    /*
     * Récupère les id des groupes auquel participe l'user circle
     */
    public function GetFourGroupsId($circle){
        $sql = "SELECT sub.circle_id "
                . "FROM  subscribers sub "
                . "WHERE sub.subscriber_id=".$circle." "
                . "AND sub.statut='groups' "
                . "ORDER BY sub.creationDate desc "
                . "LIMIT 4";

        
        $rsm = new ResultSetMapping;
        $rsm->addScalarResult('circle_id', 'id');
        
        return $this->getEntityManager()->createNativeQuery($sql, $rsm)->getResult();
    }
    /*
     * Récupère le nombre de membre du groupe circle
     */
    public function GetCountMembers($circle){
        $sql = "SELECT sub.circle_id "
                . "FROM  subscribers sub "
                . "WHERE sub.circle_id=".$circle;
        
        $rsm = new ResultSetMapping;
        $rsm->addScalarResult('circle_id', 'id');
        
        return count($this->getEntityManager()->createNativeQuery($sql, $rsm)->getResult());
    }  
    
    /*
     * Récupère les ids des membres du groupe
     */
    public function GetMembers($circle){
        $sql = "SELECT sub.subscriber_id "
                . "FROM  subscribers sub "
                . "WHERE sub.circle_id=".$circle;        
        $rsm = new ResultSetMapping;
        $rsm->addScalarResult('subscriber_id', 'id');
        
        return $this->getEntityManager()->createNativeQuery($sql, $rsm)->getResult();
    }   

    /*
     * Récupère les id des amis du cercle $circle
     */
    public function GetFriendsId($circle){
        $sql = "SELECT sub.circle_id "
                . "FROM  subscribers sub "
                . "WHERE sub.subscriber_id=".$circle." "
                . "AND sub.statut='profile' "
                . "ORDER BY sub.creationDate desc";

        
        $rsm = new ResultSetMapping;
        $rsm->addScalarResult('circle_id', 'id');
        
        return $this->getEntityManager()->createNativeQuery($sql, $rsm)->getResult();
    }
}

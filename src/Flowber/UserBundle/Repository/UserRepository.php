<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Flowber\UserBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Flowber\UserBundle\Entity\User;
use Doctrine\ORM\Query\ResultSetMapping;

/**
 * Description of UserRepository
 *
 * @author Marie
 */
class UserRepository extends EntityRepository{
    
    public function getReceivedMessages(User $user){
        $sql = "SELECT message.subject, message.message, user.firstname, user.surname, user.email, message.creationDate "
            . "FROM (SELECT * FROM messages_to_user a, private_message b "
                    . "WHERE a.user_id = ".$user->getId()." "
                    . "AND a.private_message_id=b.id) "
                . "message, user "
            . "WHERE message.user_from_id = user.id ";
        
        $rsm = new ResultSetMapping;
        $rsm->addScalarResult('subject', 'subject');
        $rsm->addScalarResult('message', 'message');
        $rsm->addScalarResult('firstname', 'firstname');
        $rsm->addScalarResult('surname', 'surname');
        $rsm->addScalarResult('email', 'email');
        $rsm->addScalarResult('creationDate', 'creationDate');
        
        return $this->getEntityManager()->createNativeQuery($sql, $rsm)->getResult();
    }
}

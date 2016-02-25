<?php

namespace Flowber\EventBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Flowber\EventBundle\Entity\Event;

/**
 * EventRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class EventRepository extends EntityRepository
{
    public function getInfosEvent($id){       
        $query = $this->_em->createQuery(''
                . 'SELECT event.startDate, '
                . 'event.startTime, event.endDate, event.endTime, '
                . 'address.name, address.address, address.city, address.zipcode, address.coordinates '
                . 'FROM FlowberEventBundle:Event event '
                . 'LEFT JOIN FlowberUserBundle:PostalAddress address  WITH event.postalAddress = address '
                . 'WHERE event.id = :id');
        $query->setParameter('id', $id);
        
        return $query->getSingleResult();
    }
 
    
    /*
     * Est ce que la personne participe à l'event
     * return participant
     */
    public function isParticipant($user, $event){
        $qb = $this->_em->createQueryBuilder();
        
        $qb->select('participants')
            ->from('FlowberEventBundle:Participants', 'participants')
            ->where('participants.user = :user')
            ->setParameter('user', $user)
            ->andWhere('participants.event = :event')
            ->setParameter('event', $event)
            ->andWhere('participants.statut = :statut')
            ->setParameter('statut', 'ok');
        
        return count($qb->getQuery()->getResult());
    }
    
    /*
     * Est ce que la personne est un administrateur de l'event
     * return participant
     */
    public function isAdmin($user, $event){
        $qb = $this->_em->createQueryBuilder();
        
        $qb->select('participants')
            ->from('FlowberEventBundle:Participants', 'participants')
            ->where('participants.user = :user')
            ->setParameter('user', $user)
            ->andWhere('participants.event = :event')
            ->setParameter('event', $event)
            ->andWhere('participants.statut = :statut')
            ->setParameter('statut', 'ok')
            ->andWhere('participants.role = :role')
            ->setParameter('role', 'admin');
        
        return count($qb->getQuery()->getResult());
    }
    
    /*
     * Récupère de nombre de participants
     * return count
     */
    public function getParticipantsNumber($event){
        $qb = $this->_em->createQueryBuilder();
        
        $qb->select('participants')
            ->from('FlowberEventBundle:Participants', 'participants')
            ->andWhere('participants.event = :event')
            ->setParameter('event', $event)
            ->andWhere('participants.statut = :statut')
            ->setParameter('statut', 'ok');
        
        return count($qb->getQuery()->getResult());
    }
    
    /*
     * Récupère de nombre de participants
     * return count
     */
    public function getParticipantsNames($event){
        $query = $this->_em->createQuery(''
                . 'SELECT user.id, '
                . 'concat(concat(user.firstname, :espace), user.surname) as createdBy '
                . 'FROM FlowberEventBundle:Participants participants '
                . 'LEFT JOIN FlowberUserBundle:User user WITH participants.user = user '
                . 'WHERE participants.event = :event');
        $query->setParameter('event', $event);
        $query->setParameter('espace', ' ');
        
        return $query->getResult();
    }
}

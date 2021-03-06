<?php

namespace Flowber\LikeBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\ResultSetMapping;

/**
 * UserRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class LikesRepository extends EntityRepository
{
    /*
     * Récupère le nombre de like d'un post
     * Return nombre de like
     */    
    public function getLikePostNumber($postId){
        $qb = $this->_em->createQueryBuilder();
        
        return $qb->getQuery()
                  ->getResult();
    }  
}
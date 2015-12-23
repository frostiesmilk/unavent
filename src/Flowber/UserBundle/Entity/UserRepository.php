<?php

namespace Flowber\UserBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\ResultSetMapping;

/**
 * UserRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class UserRepository extends EntityRepository
{
    /*
     * Récupère l'adresse d'un utilisateur
     * return city and zipcode
     */
    public function getUserCityZipcode (User $user){
        
    }
    
    /*
     * Récupère les informations de base d'un utilisateur
     * return prénom, nom, date de naissance, adresse mail
     */
    public function getUserInfoForProfile (User $user){
        
    }
    
    /*
     * Récupère le nom et le prénom de l'utilisateur
     * return prénom, nom
     */
    public function getName (User $user){
        
    }
}

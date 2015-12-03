<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

// src/Flowber/EventBundle/Validator/Constraints/DateRangeValidator.php
namespace Flowber\EventBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

/**
 * Description of DateRangeValidator
 *
 * @author Equina
 */
class DateRangeValidator extends ConstraintValidator{
    public function validate($entity, Constraint $constraint) {
//        $hasEndDate = true;
//        if ($constraint->hasEndDate !== null) {
//            $hasEndDate = $constraint->hasEndDate;
//        }
        $error = false;
        $message = $constraint->message;
        if ($entity->getStartDate() !== null) { // we have a start date
//            if ($hasEndDate) {
                if ($entity->getEndDate() !== null) { // we have an end date
                    if ($entity->getStartDate() > $entity->getEndDate()) { // incorrect start date
                        $error = true;
                    }
                    if($entity->getStartDate() == $entity->getEndDate()){ // event on same date
                        if($entity->getEndTime()!= null && $entity->getEndTime() < $entity->getStartTime()){ // incorrect time
                            $error = true;
                        }
                    }                    
                } 
//            } else {
//                if ($entity->getEndDate() !== null) {
//                    if ($entity->getStartDate() > $entity->getEndDate()) {
//                        $this->setMessage($constraint->message);
//                        return false;
//                    }
//                }
//                return true;
//            }
        } else {
            $message = $constraint->emptyStartDate;
            return false;
        }
        
        if($error){
            $this->context->buildViolation($message)
                //->setParameter('%string%', $value)
                ->addViolation();
        }
    }
}

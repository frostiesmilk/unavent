<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

// src/Flowber/EventBundle/Validator/Constraints/DateRange.php
namespace Flowber\EventBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * Description of DateRange
 *
 * @author Equina
 * 
 * @Annotation
 */
class DateRange extends Constraint{
    public $message = "daterange.violation.crossing";
    public $emptyStartDate = "daterange.violation.startDate";
    public $emptyEndDate = "daterange.violation.endDate";
    
    public function getTargets() {
        return self::CLASS_CONSTRAINT;
    }

    public function validatedBy() {
        return 'daterange_validator';
    }
}

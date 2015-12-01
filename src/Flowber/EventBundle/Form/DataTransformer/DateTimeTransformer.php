<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Flowber\EventBundle\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

/**
 * Description of DateTimeTransformer
 *
 * @author Equina
 */
class DateTimeTransformer implements DataTransformerInterface{
    /**
     * Transforms an object (DateTime) to a string.
     *
     * @param  DateTime|null $datetime
     * @return string
     */
    public function transform($datetime)
    {
        if (null === $datetime) {
            return '';
        }
 
        return $datetime->format('d/m/Y H:i');
    }
 
    /**
     * Transforms a string to an object (DateTime).
     *
     * @param  string $mydatetime
     * @return DateTime|null
     */
    public function reverseTransform($mydatetime)
    {
        // datetime optional
        if (!$mydatetime) {
            return;
        }
 
        return date_create_from_format('d/m/Y H:i', $mydatetime, new \DateTimeZone('Europe/Paris'));
    }
}

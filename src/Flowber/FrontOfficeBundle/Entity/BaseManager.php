<?php

namespace Flowber\FrontOfficeBundle\Entity;

abstract class BaseManager
{
    protected function persistAndFlush($entity)
    {
        $this->em->persist($entity);
        $this->em->flush();
    }
}
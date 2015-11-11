<?php

namespace Flowber\UserBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class FlowberUserBundle extends Bundle
{
    public function getParent()
    {
        return 'FOSUserBundle';
    }  
}

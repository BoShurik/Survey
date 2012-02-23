<?php

namespace BoShurik\UserBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class BoShurikUserBundle extends Bundle
{
    public function getParent()
    {
        return 'FOSUserBundle';
    }
}

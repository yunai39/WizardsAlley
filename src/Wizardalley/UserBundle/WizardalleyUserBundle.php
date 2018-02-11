<?php

namespace Wizardalley\UserBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * Class WizardalleyUserBundle
 *
 * @package Wizardalley\UserBundle
 */
class WizardalleyUserBundle extends Bundle
{
    /**
     * @return string
     */
    public function getParent()
    {
        return 'FOSUserBundle';
    }
}

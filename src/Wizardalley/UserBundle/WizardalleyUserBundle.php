<?php

namespace Wizardalley\UserBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class WizardalleyUserBundle extends Bundle
{

  public function getParent()

  {

    return 'FOSUserBundle';

  }
}

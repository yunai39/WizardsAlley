<?php

namespace Wizardalley\AdminBundle\Table;

use Symfony\Component\HttpFoundation\Request;
use Wizardalley\CoreBundle\Entity\WizardUser;

/**
 * Class UserTable
 * @package Wizardalley\AdminBundle\Table
 */
class UserTable extends AbstractTable
{
    public function getTableName() {
        return 'WizardalleyCoreBundle:WizardUser';
    }

    public function generateTable()
    {
        $this
            ->addColumn('id', 'Id')
            ->addColumn('username', 'Username')
            ->addColumn('firstname', 'Firstname')
            ->addColumn('lastname', 'Lastname')
            ->addColumn('email', 'Email')
        ;
    }

    public function renderEdit(WizardUser $publication) {

    }

    public function renderDelete(WizardUser $publication) {

    }

    public function getName()
    {
        return 'user';
    }
}
<?php

namespace Wizardalley\AdminBundle\Table;

use Wizardalley\CoreBundle\Entity\Publication;

class PublicationTable extends AbstractTable
{
    public function getTableName() {
        return 'WizardalleyCoreBundle:Publication';
    }
    public function generateTable()
    {
        $this
            ->addColumn('id', 'Id')
            ->addColumn('title', 'Titre')
        ;
    }

    public function renderEdit(Publication $publication) {

    }

    public function getName()
    {
        return 'publication';
    }
}
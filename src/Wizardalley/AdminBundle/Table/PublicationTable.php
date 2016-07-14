<?php

namespace Wizardalley\AdminBundle\Table;

use Symfony\Component\HttpFoundation\Request;
use Wizardalley\CoreBundle\Entity\Publication;

class PublicationTable extends AbstractTable
{
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

    /**
     * @param Request $request
     * @return array|\Wizardalley\CoreBundle\Entity\Publication[]
     */
    public function getResult(Request $request)
    {
        $repo = $this->em->getRepository('WizardalleyCoreBundle:Publication');

        return $repo->findAll();
    }
}
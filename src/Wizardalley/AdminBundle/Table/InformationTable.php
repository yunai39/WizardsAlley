<?php

namespace Wizardalley\AdminBundle\Table;

use Wizardalley\CoreBundle\Entity\InformationBillet;

/**
 * Class InformationTable
 * @package Wizardalley\AdminBundle\Table
 */
class InformationTable extends AbstractTable
{
    /**
     * @return string
     */
    public function getTableName() {
        return 'WizardalleyCoreBundle:InformationBillet';
    }
    public function generateTable()
    {
        $this
            ->addColumn('id', 'Id')
            ->addColumn('name', 'Nom')
            ->addAction('edit', [
                'type' => TableAction::ACTION_LINK,
                'render' => 'renderEdit'
            ])
            ->addAction('delete', [
                'type' => TableAction::ACTION_LINK,
                'render' => 'renderDelete'
            ])
        ;
    }

    /**
     * @param TableAction $action
     * @param Page $page
     * @return array
     */
    public function renderEdit(TableAction $action, InformationBillet $informatioNBillet) {
        return [
            'icon' => 'icon-pencil',
            'href' => $this->router->generate(
                'admin_infoBillet_edit',
                ['id' => $informatioNBillet->getId()]
            )
        ];
    }

    /**
     * @param TableAction $action
     * @param Page $page
     * @return array
     */
    public function renderDelete(TableAction $action, InformationBillet $informatioNBillet) {
        return [
            'icon' => 'icon-trash',
            'href' => $this->router->generate(
                'admin_infoBillet_delete',
                ['id' => $informatioNBillet->getId()]
            )
        ];
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'information';
    }
}
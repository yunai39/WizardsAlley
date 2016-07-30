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
    public function getTableName()
    {
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
            ->addModalAction('delete', [
                'type' => TableAction::ACTION_MODAL_CONFIRM,
                'template' => 'template-render-modal-delete',
                'render' => 'renderDeleteLink',
                'title' => 'Suppression du billet d\'information'
            ]);
    }

    /**
     * @param TableAction $action
     * @param InformationBillet $page
     * @return array
     */
    public function renderEdit(TableAction $action, InformationBillet $informatioNBillet)
    {
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
     * @param InformationBillet $info
     * @return array
     */
    public function renderDeleteLink(TableAction $action, InformationBillet $info)
    {
        return [
            'data' => $action->getData(),
            'action' => $this->router->generate('admin_infoBillet_delete', ['id' => $info->getId()]),
            'template' => $action->getTemplate(),
            'icon' => 'icon-trash',
            'title' => $this->translator->trans("wizard.table." . $this->getName() . ".action." . $action->getName())
        ];
    }

    /**
     * @return string
     */
    public function getTemplate(){
        return 'WizardalleyAdminBundle:InformationBillet:list.html.twig';
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'information';
    }
}
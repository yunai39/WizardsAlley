<?php

namespace Wizardalley\AdminBundle\Table;

use Symfony\Component\HttpFoundation\Request;
use Wizardalley\CoreBundle\Entity\Page;
use Wizardalley\CoreBundle\Entity\Publication;

/**
 * Class PageTable
 * @package Wizardalley\AdminBundle\Table
 */
class PageTable extends AbstractTable
{
    /**
     * @return string
     */
    public function getTableName() {
        return 'Wizardalley\CoreBundle\Entity\Page';
    }

    public function generateTable()
    {
        $this
            ->addColumn('id', 'Id')
            ->addColumn('name', 'Name')
            ->addAction('edit', [
                'type' => TableAction::ACTION_LINK,
                'render' => 'renderEdit'
            ])
            ->addModalAction('delete', [
                'type' => TableAction::ACTION_MODAL_CONFIRM,
                'template' => 'template-render-modal-delete',
                'render' => 'renderDeleteLink',
                'title' => 'Suppression de la page'
            ])
        ;
    }

    /**
     * @param TableAction $action
     * @param Page $page
     * @return array
     */
    public function renderEdit(TableAction $action, Page $page) {
        return [
            'icon' => 'icon-pencil',
            'href' => $this->router->generate(
                'admin_page_edit',
                ['id' => $page->getId()]
            )
        ];
    }

    /**
     * @param TableAction $action
     * @param Page $page
     * @return array
     */
    public function renderDeleteLink(TableAction $action, Page $page) {
        return [
            'data' => $action->getData(),
            'action' => $this->router->generate('admin_page_delete', ['id' => $page->getId()]),
            'template' => $action->getTemplate(),
            'icon' => 'icon-trash',
            'title' => $this->translator->trans("wizard.table.action.".$action->getName())
        ];
    }

    /**
     * @return string
     */
    public function getTemplate(){
        return 'WizardalleyAdminBundle:Page:list.html.twig';
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'page';
    }
}
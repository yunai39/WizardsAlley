<?php

namespace Wizardalley\AdminBundle\Table;

use Wizardalley\CoreBundle\Entity\PageCategory;
use Wizardalley\CoreBundle\Entity\WizardUser;

/**
 * Class CategoryTable
 * @package Wizardalley\AdminBundle\Table
 */
class CategoryTable extends AbstractTable
{
    /**
     * @return string
     */
    public function getTableName()
    {
        return 'WizardalleyCoreBundle:PageCategory';
    }

    /**
     *
     */
    public function generateTable()
    {
        $this
            ->addColumn('id', 'Id')
            ->addColumn('name', 'Name', ['search' => true])
            ->addAction('edit', [
                'type' => TableAction::ACTION_LINK,
                'render' => 'renderEdit'
            ])
            ->addModalAction('delete', [
                'type' => TableAction::ACTION_MODAL_CONFIRM,
                'template' => 'template-render-modal-delete',
                'render' => 'renderDeleteLink',
                'title' => 'Suppression de la category'
            ])
        ;
    }


    /**
     * @param TableAction $action
     * @param PageCategory  $user
     * @return array
     */
    public function renderEdit(TableAction $action, PageCategory $user)
    {
        return [
            'icon' => 'icon-pencil',
            'href' => $this->router->generate(
                'admin_pagecategory_edit',
                ['id' => $user->getId()]
            )
        ];
    }


    /**
     * @param TableAction $action
     * @param PageCategory $category
     *
     * @return array
     */
    public function renderDeleteLink(TableAction $action, PageCategory $category)
    {
        return [
            'data' => $action->getData(),
            'action' => $this->router->generate('admin_pagecategory_delete', ['id' => $category->getId()]),
            'template' => $action->getTemplate(),
            'icon' => 'icon-trash',
            'title' => $this->translator->trans("wizard.table.information.action." . $action->getName())
        ];
    }

    /**
     * @return string
     */
    public function getTemplate(){
        return 'WizardalleyAdminBundle:PageCategory:list.html.twig';
    }


    public function getName()
    {
        return 'category';
    }
}
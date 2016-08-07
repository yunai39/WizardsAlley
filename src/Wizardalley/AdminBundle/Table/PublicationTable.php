<?php

namespace Wizardalley\AdminBundle\Table;

use Wizardalley\CoreBundle\Entity\Publication;

/**
 * Class PublicationTable
 * @package Wizardalley\AdminBundle\Table
 */
class PublicationTable extends AbstractTable
{
    /**
     * @return string
     */
    public function getTableName() {
        return 'WizardalleyCoreBundle:Publication';
    }

    /**
     * @inheritdoc
     */
    public function generateTable()
    {
        $this
            ->addColumn('id', 'Id')
            ->addColumn('title', 'Titre')
            ->addAction('user', [
                'type' => TableAction::ACTION_LINK,
                'render' => 'renderUser',
                'template' => 'template-render-user-link'
            ])
            ->addModalAction('delete', [
                'type' => TableAction::ACTION_MODAL_CONFIRM,
                'template' => 'template-render-modal-delete',
                'render' => 'renderDeleteLink',
                'title' => 'Suppression de la publication'
            ])
        ;
    }



    /**
     * @param TableAction $action
     * @param Publication $publication
     * @return array
     */
    public function renderUser(TableAction $action, Publication $publication) {
        return [
            'username' => $publication->getUser()->getUsername(),
            'href' => $this->router->generate(
                'admin_user_edit',
                ['id' => $publication->getUser()->getId()]
            )
        ];
    }

    /**
     * @return string
     */
    public function getTemplate(){
        return 'WizardalleyAdminBundle:Publication:list.html.twig';
    }

    /**
     * @param TableAction $action
     * @param Publication $publication
     * @return array
     */
    public function renderDeleteLink(TableAction $action, Publication $publication) {
        return [
            'data' => $action->getData(),
            'action' => $this->router->generate('admin_publication_delete', ['id' => $publication->getId()]),
            'template' => $action->getTemplate(),
            'icon' => 'icon-trash',
            'title' => $this->translator->trans("wizard.table.information.action.".$action->getName())
        ];
    }

    public function getName()
    {
        return 'publication';
    }
}
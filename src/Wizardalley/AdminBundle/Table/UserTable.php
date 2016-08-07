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
            ->addColumn('roles', 'Roles', [
                'render' => "renderRoles"
            ])
            ->addColumn('email', 'Email')
            ->addModalAction('lock', [
                'type' => TableAction::ACTION_MODAL_CONFIRM,
                'template' => 'template-render-modal-lock',
                'render' => 'renderLockLink',
                'title' => 'Bloquer l\'utilisateur'
            ])
            ->addModalAction('unlock', [
                'type' => TableAction::ACTION_MODAL_CONFIRM,
                'template' => 'template-render-modal-unlock',
                'render' => 'renderUnlockLink',
                'title' => 'Débloquer l\'utilisateur'
            ])
            ->addAction('edit', [
                'type' => TableAction::ACTION_LINK,
                'render' => 'renderEdit'
            ])
        ;
    }


    /**
     * @param TableAction $action
     * @param WizardUser  $user
     * @return array
     */
    public function renderEdit(TableAction $action, WizardUser $user)
    {
        return [
            'icon' => 'icon-pencil',
            'href' => $this->router->generate(
                'admin_user_edit',
                ['id' => $user->getId()]
            )
        ];
    }

    /**
     * @param TableColumn $column
     * @param WizardUser  $user
     * @return array
     */
    public function renderRoles(TableColumn $column, $user){
        return [
            'data' => $user->getRoles()
        ];

    }

    public function renderLockLink(TableAction $action, WizardUser $user) {
        if($user->isLocked()) {
            return false;
        }
        return [
            'data' => $action->getData(),
            'action' => $this->router->generate('admin_user_lock', ['id' => $user->getId()]),
            'template' => $action->getTemplate(),
            'icon' => 'icon-lock',
            'title' => $this->translator->trans("wizard.table.action.".$action->getName())
        ];
    }

    public function renderUnlockLink(TableAction $action, WizardUser $user) {
        if(!$user->isLocked()) {
            return false;
        }
        return [
            'data' => $action->getData(),
            'action' => $this->router->generate('admin_user_unlock', ['id' => $user->getId()]),
            'template' => $action->getTemplate(),
            'icon' => 'icon-unlock',
            'title' => $this->translator->trans("wizard.table.action.".$action->getName())
        ];
    }

    /**
     * @return string
     */
    public function getTemplate(){
        return 'WizardalleyAdminBundle:User:list.html.twig';
    }


    public function getName()
    {
        return 'user';
    }
}
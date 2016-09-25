<?php

namespace Wizardalley\AdminBundle\Table;

use Doctrine\ORM\QueryBuilder;
use Wizardalley\CoreBundle\Entity\Publication;
use Wizardalley\CoreBundle\Entity\PublicationFavorite;

/**
 * Class PublicationTable
 * @package Wizardalley\AdminBundle\Table
 */
class PublicationTable extends AbstractTable
{
    /**
     * @return string
     */
    public function getTableName()
    {
        return 'WizardalleyCoreBundle:Publication';
    }

    /**
     * @inheritdoc
     */
    public function generateTable()
    {
        $this
            ->addColumn('id', 'Id')
            ->addColumn('title', 'Titre', ['search' => true])
            ->addColumn('user', 'user', [
                'type' => TableAction::ACTION_LINK,
                'render' => 'renderUser',
                'template-name' => 'template-render-user-link'
            ])
            ->addModalAction('toogleFavorite', [
                'type' => TableAction::ACTION_MODAL_CONFIRM,
                'template' => 'template-render-modal-toogle-favorite',
                'render' => 'renderFavoriteLink',
                'title' => 'Passer la publication en favoris'
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
     * @param QueryBuilder $query
     * @param string       $search
     *
     * @return QueryBuilder
     */
    public function searchQuery(QueryBuilder $query, $search)
    {
        $query->orWhere('r.smallContent like :smallContent');
        $query->setParameter('smallContent', '%' . $search . '%');
        $query->orWhere('r.content like :content');
        $query->setParameter('content', '%' . $search . '%');
        return $query;
    }

    /**
     * @param TableColumn $column
     * @param Publication $publication
     *
     * @return array
     */
    public function renderUser(TableColumn $column, Publication $publication)
    {
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
    public function getTemplate()
    {
        return 'WizardalleyAdminBundle:Publication:list.html.twig';
    }

    /**
     * @param TableAction $action
     * @param Publication $publication
     *
     * @return array
     */
    public function renderDeleteLink(TableAction $action, Publication $publication)
    {
        return [
            'data' => $action->getData(),
            'action' => $this->router->generate('admin_publication_delete', ['id' => $publication->getId()]),
            'template' => $action->getTemplate(),
            'icon' => 'icon-trash',
            'title' => $this->translator->trans("wizard.table.information.action." . $action->getName())
        ];
    }

    /**
     * @param TableAction $action
     * @param Publication $publication
     *
     * @return array
     */
    public function renderFavoriteLink(TableAction $action, Publication $publication)
    {
        $icon = 'icon-star-empty';
        if ($publication->getFavorite() instanceof PublicationFavorite) {
            $icon = 'icon-star';
        }
        return [
            'data' => $action->getData(),
            'action' => $this->router->generate('admin_publication_delete', ['id' => $publication->getId()]),
            'template' => $action->getTemplate(),
            'icon' => $icon,
            'title' => $this->translator->trans("wizard.table.information.action." . $action->getName())
        ];
    }

    public function getName()
    {
        return 'publication';
    }
}

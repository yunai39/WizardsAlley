<?php

namespace Wizardalley\AdminBundle\Table;

use Doctrine\ORM\QueryBuilder;
use Wizardalley\CoreBundle\Entity\InformationBillet;
use Wizardalley\CoreBundle\Entity\MapObject;

/**
 * Class MapTable
 * @package Wizardalley\AdminBundle\Table
 */
class MapTable extends AbstractTable
{
    /**
     * @return string
     */
    public function getTableName()
    {
        return 'WizardalleyCoreBundle:MapObject';
    }

    public function generateTable()
    {
        $this
            ->addColumn('id', 'Id')
            ->addColumn('title', 'Titre', ['search' => true])
            ->addAction('edit', [
                'type' => TableAction::ACTION_LINK,
                'render' => 'renderEdit'
            ])
            ->addModalAction('delete', [
                'type' => TableAction::ACTION_MODAL_CONFIRM,
                'template' => 'template-render-modal-delete',
                'render' => 'renderDeleteLink',
                'title' => 'Suppression du billet d\'information'
            ])
        ;
    }


    /**
     * @param QueryBuilder $query
     * @param string $search
     * @return QueryBuilder
     */
    public function searchQuery(QueryBuilder $query, $search)
    {
        $query->orWhere('r.title like :title');
        $query->setParameter('title', '%'.$search.'%');
        return $query;
    }

    /**
     * @param TableAction $action
     * @param MapObject $map
     * @return array
     */
    public function renderEdit(TableAction $action, MapObject $map)
    {
        return [
            'icon' => 'icon-pencil',
            'href' => $this->router->generate(
                'admin_map_edit',
                ['id' => $map->getId()]
            )
        ];
    }


    /**
     * @param TableAction $action
     * @param MapObject $map
     * @return array
     */
    public function renderDeleteLink(TableAction $action, MapObject $map)
    {
        return [
            'data' => $action->getData(),
            'action' => $this->router->generate('admin_map_delete', ['id' => $map->getId()]),
            'template' => $action->getTemplate(),
            'icon' => 'icon-trash',
            'title' => $this->translator->trans("wizard.table." . $this->getName() . ".action." . $action->getName())
        ];
    }

    /**
     * @return string
     */
    public function getTemplate()
    {
        return 'WizardalleyAdminBundle:MapObject:list.html.twig';
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'map';
    }
}
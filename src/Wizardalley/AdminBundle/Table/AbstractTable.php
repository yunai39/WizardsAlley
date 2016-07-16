<?php

namespace Wizardalley\AdminBundle\Table;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Query;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGenerator;

/**
 * Class AbstractTable
 * @package Wizardalley\AdminBundle\Table
 */
abstract class AbstractTable
{
    /** @var TableColumn[] */
    protected $columns = [];

    /** @var TableAction[] */
    protected $actions = [];

    /** @var EntityManager */
    protected $em;

    /** @var Router */
    protected $router;

    /**
     * AbstractTable constructor.
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em, Router $router){
        $this->em = $em;
        $this->router = $router;

        $this->generateTable();
    }
    abstract public function generateTable();

    /**
     * @return string
     */
    protected function getIdFunction() {
        return 'getId';
    }

    /**
     * @param $name
     * @param $options
     * @return AbstractTable
     */
    protected function addColumn($name, $label, $options = []){
        $this->columns[$name] = new TableColumn($name,$label, $options);
        return $this;
    }

    /**
     * @param $name
     * @param $options
     */
    protected function addAction($name, $options){
        $this->actions[$name] = new TableAction($name, $options['type'], $options['render']);
        return $this;
    }

    /**
     * @param Request $request
     * @return Query
     */
    public function getQueryResult(Request $request) {
        $repo = $this->em->getRepository($this->getTableName());
        $limit = $request->query->get('iDisplayLength');
        $page = $request->query->get('sEcho');
        $offset = ($page-1) * $limit;
        return $repo->createQueryBuilder('r')
            ->setMaxResults($limit)
            ->setFirstResult($offset)
            ->getQuery();

    }

    /**
     * @return int
     */
    public function getTotal(){
        $repo = $this->em->getRepository($this->getTableName());
        return count($repo->findAll());
    }

    /**
     * @return array
     */
    public function getConfig(){
        $config = [];
        foreach( $this->columns as $column) {
            $config['column'][$column->getName()] = [
                'type' => 'info',
                'label' => $column->getLabel()
            ];
        }
        foreach( $this->actions as $column) {
            $config['action'][$column->getName()] = [
                'type' => 'action',
                'label' => $column->getName()
            ];
        }
        $config['datatable'] = [
            "bProcessing" => true,
            "bServerSide" => true,
            "paging" => true,
            "sAjaxSource" => $this->router->generate('admin_list_json', ['name' => $this->getName()]),
        ];
        foreach( $this->columns as $column) {
            $config['datatable']['columns'][] = ['data' => $column->getName()];
        }
        foreach( $this->actions as $action) {
            $config['datatable']['columns'][] = ['data' => $action->getName()];
        }

        return $config;
    }

    /**
     * @param Request $request
     * @return array
     */
    public function getArrayResult(Request $request){
        $datas = $this->getQueryResult($request)->getResult();
        $array = [];
        /** @var Object $data */
        foreach($datas as $data) {
            $row = [];
            // Pour chaque column
            /** @var TableColumn $column */
            foreach($this->columns as $column) {
                $columnData = [];

                $renderFunctionName = $column->getRenderFunctionName();
                $columnData['data'] = $column->getData($data);
                $columnData['template'] = $column->getTemplateName();
                $columnData['render'] = $this->$renderFunctionName($column,$data);
                $row[$column->getName()] = $columnData;
            }
            /** @var TableAction $action */
            foreach($this->actions as $action) {
                $renderFunction = $action->getActionRender();
                $columnAction = [
                    'data' => $action->getName(),
                    'action' => $action->getActionType(),
                    'template' => TableAction::ACTION_TEMPLATE,
                    'render' => $this->$renderFunction($action, $data)
                ];
                $row[$action->getName()] = $columnAction;
            }
            $array[] = $row;
        }

        return $array;
    }

    /**
     * @param TableColumn $column
     * @param $data
     * @return array
     */
    protected function columnRenderDefault(TableColumn $column, $data){
        return [
            'data' => $column->getData($data)
        ];
    }

    /**
     * @return string
     */
    abstract protected function getTableName();

    /**
     * @return string
     */
    public function getName(){}

    /**
     * @return string
     */
    public function getTemplate(){
        return 'WizardalleyAdminBundle:Table:defaultList.html.twig';
    }
}
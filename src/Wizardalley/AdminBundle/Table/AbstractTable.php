<?php

namespace Wizardalley\AdminBundle\Table;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGenerator;
use Symfony\Component\Translation\Translator;

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

    /** @var Translator */
    protected $translator;

    /**
     * AbstractTable constructor.
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em, Router $router, Translator $translator){
        $this->em = $em;
        $this->router = $router;
        $this->translator = $translator;

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
    protected function addColumn($name, $label, $options = []) {
        $this->columns[$name] = new TableColumn($name,$label, $options);
        return $this;
    }

    /**
     * @param $name
     * @param $options
     * @return AbstractTable
     */
    protected function addAction($name, $options) {

        $this->actions[$name] = new TableAction(
            $name,
            $options['type'],
            $options['render'],
            isset($options['template']) ? $options['template']: TableAction::ACTION_TEMPLATE
        );
        return $this;
    }

    /**
     * @param $name
     * @param $options
     * @return $this
     */
    protected function addModalAction($name, $options) {
        $action = new TableAction($name, $options['type'], $options['render']);
        $action->setTemplate($options['template']);
        if(isset($options['data'])) {
            $action->setData($options['data']);
        }
        $this->actions[$name] = $action;
        return $this;
    }


    /**
     * @param Request $request
     * @return Query
     */
    public function getQueryResult(Request $request) {
        $repo = $this->em->getRepository($this->getTableName());
        $limit = $request->query->get('iDisplayLength');
        $offset = $request->query->get('iDisplayStart');
        /** @var QueryBuilder $query */
        $query = $repo->createQueryBuilder('r')
            ->setMaxResults($limit)
            ->setFirstResult($offset);
        // Si on a un champs de recherche
        if ($request->query->has('sSearch') && !empty($request->query->get('sSearch'))) {
            $search = $request->query->get('sSearch');
            // Pour chaque columns si on
            /** @var TableColumn $column */
            foreach( $this->columns as $column) {
                if ($column->getSearch()) {
                    $query->orWhere('r.'.$column->getName() .'  LIKE :'.$column->getName());
                    $query->setParameter($column->getName(), '%'.$search.'%');
                }

            }
            $query = $this->searchQuery($query, $search);

        }

        return $query->getQuery();

    }

    /**
     * Fonction a surcharger pour modifier la recherche sur des champs qui ne sont pas presents dans les columns
     * @param QueryBuilder $query
     * @param string $search
     * @return QueryBuilder
     */
    public function searchQuery(QueryBuilder $query, $search) {
        return $query;
    }

    /**
     * @return int
     */
    public function getTotal(){
        $repo = $this->em->getRepository($this->getTableName());
        return count($repo->findAll());
    }




    /**
     * @return int
     */
    public function getTotalFiltered(Request $request){
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
            "oStdClasses" => [
                "sFilter" => 'form-control',
            ],
            "sAjaxSource" => $this->router->generate('admin_list_json', ['name' => $this->getName()]),
        ];
        foreach( $this->columns as $column) {
            $config['datatable']['columns'][] = ['data' => $column->getName()];
        }
        foreach( $this->actions as $action) {
            $config['datatable']['columns'][] = ['data' => $action->getName()];
        }
        $config['yadcf'] = $this->getYadcfConfig();

        return $config;
    }

    public function getYadcfConfig(){
        $config = [];
        $i = 0;
        foreach ($this->columns as $column) {
            if ($column->getFilter()) {
                if ($column->getFilter() == TableColumn::FILTER_SELECT_MULTIPLE_TYPE) {
                    $config[] = [
                        'column_number' => $i,
                        'filter_type' => 'multi_select',
                        'style_class' => 'form-control',
                    ];
                } elseif ($column->getFilter() == TableColumn::FILTER_TEXT_TYPE) {
                    $config[] = [
                        'column_number' => $i,
                        'filter_type' => 'text',
                        'style_class' => 'form-control',
                    ];
                }
            }
            $i++;
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
                if($action->getActionType() == TableAction::ACTION_MODAL_CONFIRM) {
                    $template = TableAction::ACTION_MODAL_TEMPLATE;
                } else {
                    if($action->getTemplate()) {
                        $template = $action->getTemplate();
                    } else {
                        $template = TableAction::ACTION_TEMPLATE;
                    }
                }
                $columnAction = [
                    'data' => $action->getName(),
                    'action' => $action->getActionType(),
                    'template' => $template,
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
     * @param TableAction $action
     * @param $data
     * @return array
     */
    protected function actionRenderModal(TableAction $action, $data){
        $actionRender = $action->getActionRender();
        return [
            'data' => $action->getData(),
            'action' => $this->$actionRender($action, $data),
            'template' => $action->getTemplate(),
            'title' => $this->translator->trans("wizard.admin.table.action.".$action->getName())
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
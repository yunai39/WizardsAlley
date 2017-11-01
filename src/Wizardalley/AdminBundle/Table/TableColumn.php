<?php

namespace Wizardalley\AdminBundle\Table;

/**
 * Class TableColumn
 *
 * @package Wizardalley\AdminBundle\Table
 */
class TableColumn
{
    const FILTER_TEXT_TYPE            = 'filter-text';

    const FILTER_SELECT_MULTIPLE_TYPE = 'filter-select-multiple';

    /** @var string */
    protected $templateJsColumn = "template-render-column";

    /** @var string */
    protected $name;

    /** @var string */
    protected $label;

    /** @var bool|string */
    protected $search = false;

    /** @var bool|string */
    protected $filter = false;

    /** @var string */
    protected $renderFunction = 'columnRenderDefault';

    /**
     * TableColumn constructor.
     *
     * @param       $name
     * @param array $options
     */
    public function __construct($name, $label, $options = [])
    {
        $this->label = $label;
        $this->name  = $name;
        if (isset($options[ 'template-name' ])) {
            $this->templateJsColumn = $options[ 'template-name' ];
        }
        if (isset($options[ 'render' ])) {
            $this->renderFunction = $options[ 'render' ];
        }
        if (isset($options[ 'search' ])) {
            $this->search = $options[ 'search' ];
        }
        if (isset($options[ 'filter' ])) {
            $this->filter = $options[ 'filter' ];
        }
    }

    /**
     * @param Object $object
     *
     * @return string|int
     */
    public function getData($object)
    {
        $functionName = 'get' . ucfirst($this->name);

        return $object->$functionName();
    }

    /**
     * @return string
     */
    public function getTemplateName()
    {
        return $this->templateJsColumn;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return bool
     */
    public function getSearch()
    {
        return $this->search;
    }

    /**
     * @return bool|string
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * @return bool|string
     */
    public function getFilter()
    {
        return $this->filter;
    }

    /**
     * @return string
     */
    public function getRenderFunctionName()
    {
        return $this->renderFunction;
    }
}
<?php

namespace Wizardalley\AdminBundle\Table;


class TableColumn
{
    /** @var string */
    protected $templateJsColumn = "template-render-column";

    /** @var string */
    protected $name;

    /** @var string */
    protected $label;

    /** @var string */
    protected $renderFunction = 'columnRenderDefault';

    /**
     * TableColumn constructor.
     * @param $name
     * @param array $options
     */
    public function __construct($name, $label, $options = [])
    {
        $this->label = $label;
        $this->name = $name;
        if(isset($options['template-name'])) {
            $this->templateJsColumn = $options['template-name'];
        }
        if(isset($options['render'])) {
            $this->renderFunction = $options['render'];
        }
    }

    /**
     * @param Object $object
     * @return string|int
     */
    public function getData($object) {
        $functionName = 'get' . ucfirst($this->name);
        return $object->$functionName();
    }

    /**
     * @return string
     */
    public function getTemplateName() {
        return $this->templateJsColumn;
    }

    /**
     * @return string
     */
    public function getName() {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getLabel() {
        return $this->label;
    }

    /**
     * @return string
     */
    public function getRenderFunctionName(){
        return $this->renderFunction;
    }
}
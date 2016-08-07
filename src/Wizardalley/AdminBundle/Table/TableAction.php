<?php

namespace Wizardalley\AdminBundle\Table;

/**
 * Class TableAction
 * @package Wizardalley\AdminBundle\Table
 */
class TableAction
{
    const ACTION_MODAL_CONFIRM = 'table.action.modal_confirm';
    const ACTION_LINK = 'table.action.link';
    const ACTION_TEMPLATE = 'template-render-action';
    const ACTION_MODAL_TEMPLATE = 'template-render-button-modal';
    protected $actionType = self::ACTION_LINK;
    protected $actionRender = null;
    protected $name;
    protected $data;
    protected $template;

    /**
     * TableAction constructor.
     * @param $actionType
     * @param $actionRender
     * @param $template
     */
    public function __construct($name, $actionType, $actionRender, $template = self::ACTION_TEMPLATE)
    {
        $this->name = $name;
        $this->actionType = $actionType;
        $this->actionRender = $actionRender;
        $this->template = $template;
    }

    /**
     * @return string
     */
    public function getActionType()
    {
        return $this->actionType;
    }

    /**
     * @return null
     */
    public function getActionRender()
    {
        return $this->actionRender;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param mixed $data
     * @return TableAction
     */
    public function setData($data)
    {
        $this->data = $data;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getTemplate()
    {
        return $this->template;
    }

    /**
     * @param mixed $template
     * @return TableAction
     */
    public function setTemplate($template)
    {
        $this->template = $template;
        return $this;
    }
}
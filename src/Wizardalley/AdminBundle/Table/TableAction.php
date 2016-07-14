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
    protected $actionType = self::ACTION_LINK;
    protected $actionRender = null;
    protected $name;

    /**
     * TableAction constructor.
     * @param $actionType
     * @param $actionRender
     */
    public function __construct($name, $actionType, $actionRender)
    {
        $this->name = $name;
        $this->actionType = $actionType;
        $this->actionRender = $actionRender;
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
}
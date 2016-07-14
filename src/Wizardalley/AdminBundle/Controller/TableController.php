<?php

namespace Wizardalley\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Wizardalley\AdminBundle\Table\AbstractTable;
use Wizardalley\AdminBundle\Table\TableHelper;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * Class TableController
 * @package Wizardalley\AdminBundle\Controller
 * @Route("/admin")
 */
class TableController extends Controller
{

    /**
     * Lists all Page entities.
     * @Route("/list/{tableName}", name="admin_list_page")
     * @Method("GET")
     * @param $name
     * @return string
     */
    public function listAction($tableName){
        $table = $this->container->get('wizardalley.admin.table.'.$tableName);
        return $this->render(
            $table->getTemplate(),
            [
                'config' => $table->getConfig(),
                'name' => $tableName,
            ]
        );
    }

    /**
     * @Route("/jsonTable/{name}", name="admin_list_json")
     * @Method("GET")
     * @param Request $request
     * @param $name
     * @return JsonResponse
     */
    public function getJsonResultAction(Request $request, $name){

        /** @var AbstractTable $table */
        $table = $this->container->get('wizardalley.admin.table.'.$name);
        return new JsonResponse(
            [
                "draw" => 1,
                "recordsTotal"=> $table->getTotal(),
                "recordsFiltered"=> $table->getTotal(),
                'data' => $table->getArrayResult($request)
            ]
        );
    }

}

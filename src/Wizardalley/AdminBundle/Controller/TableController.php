<?php

namespace Wizardalley\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Wizardalley\AdminBundle\Table\AbstractTable;
use Sensio\Bundle\FrameworkExtraBundle\Configuration;

/**
 * Class TableController
 *
 * @package Wizardalley\AdminBundle\Controller
 * @Configuration\Route("/admin")
 */
class TableController extends Controller
{

    /**
     * Lists all Page entities.
     * @Configuration\Route("/list/{tableName}", name="admin_list_page")
     * @Configuration\Method("GET")
     *
     * @param string $tableName
     *
     * @return string
     */
    public function listAction($tableName)
    {
        $table = $this->container->get('wizardalley.admin.table.' . $tableName);

        return $this->render(
            $table->getTemplate(),
            [
                'config' => $table->getConfig(),
                'name'   => $tableName,
            ]
        );
    }

    /**
     * @Configuration\Route("/jsonTable/{name}", name="admin_list_json")
     * @Configuration\Method("GET")
     * @param Request $request
     * @param         $name
     *
     * @return JsonResponse
     */
    public function getJsonResultAction(Request $request, $name)
    {

        /** @var AbstractTable $table */
        $table = $this->container->get('wizardalley.admin.table.' . $name);

        return new JsonResponse(
            [
                "draw"            => $request->query->get('sEcho'),
                "recordsTotal"    => $table->getTotal(),
                "recordsFiltered" => $table->getTotal(),
                'data'            => $table->getArrayResult($request)
            ]
        );
    }
}

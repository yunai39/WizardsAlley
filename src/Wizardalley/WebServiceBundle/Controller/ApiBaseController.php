<?php

namespace Wizardalley\WebServiceBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class ApiBaseController
 *
 * @package Wizardalley\WebServiceBundle\Controller
 */
class ApiBaseController extends FOSRestController
{

    /**
     * @param $data
     *
     * @return Response
     */
    protected function successResponse($data)
    {
        $response = new Response('{"status":"success", "data": ' . $data . '}');
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    /**
     * @param $message
     *
     * @return Response
     */
    protected function errorResponse($message)
    {
        $response = new JsonResponse(
            [
                'status'  => 'error',
                'message' => $message
            ]
        );

        return $response;
    }

}
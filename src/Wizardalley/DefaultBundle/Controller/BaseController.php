<?php

namespace Wizardalley\DefaultBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Class BaseController
 * @package Wizardalley\DefaultBundle\Controller
 */
class BaseController extends Controller
{
    const BASE_LIMIT = 2;
    /**
     * @param int   $status
     * @param array $data
     * @param int   $returnCode
     * @param array $extra
     *
     * @return JsonResponse
     */
    protected function sendJsonResponse($status, $data, $returnCode = 200, $extra = null)
    {
        return new JsonResponse([
            'status' => $status,
            'data' => $data,
            'extra' => $extra
        ], $returnCode);
    }
}

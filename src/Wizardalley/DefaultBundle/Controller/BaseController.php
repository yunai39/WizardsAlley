<?php

namespace Wizardalley\DefaultBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;

class BaseController extends Controller
{
    protected function sendJsonResponse($status, $data, $returnCode = 200, $extra = null){
        return new JsonResponse([
            'status' => $status,
            'data' => $data,
            'extra' => $extra
        ], $returnCode);
    }
}

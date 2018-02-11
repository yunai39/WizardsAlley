<?php

namespace Wizardalley\WebServiceBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Class BaseController
 *
 * @package Wizardalley\WebServiceBundle\Controller
 */
class BaseController extends Controller
{
    /**
     * @param $content
     *
     * @return JsonResponse
     */
    protected function buildSuccessResponse($content)
    {
        return new JsonResponse(
            [
                'result'  => 'success',
                'content' => $content
            ]
        );
    }

    /**
     * @param     $error
     * @param     $message
     * @param int $error_status
     *
     * @return JsonResponse
     */
    protected function buildErrorResponse($error, $message, $error_status = 500)
    {
        return new JsonResponse(
            [
                'result'  => 'error',
                'error'   => $error,
                'message' => $message
            ],
            $error_status
        );
    }
}
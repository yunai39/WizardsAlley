<?php

namespace Wizardalley\WebServiceBundle\Controller;

use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\View;
use FOS\RestBundle\Controller\FOSRestController;
use Wizardalley\CoreBundle\Entity\Publication;

/**
 * Class PublicationController
 *
 * @package WebServiceBundle\Controller
 */
class PublicationController extends FOSRestController
{
    /**
     * @Get(
     *     path = "/publication/{id}",
     *     name = "api_publication_show",
     *     requirements = {"id"="\d+"}
     * )
     * @View
     */
    public function showAction(Publication $publication)
    {
        return $publication;
    }
}
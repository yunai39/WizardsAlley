<?php

namespace Wizardalley\WebServiceBundle\Controller;

use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\View;
use FOS\RestBundle\Controller\FOSRestController;
use JMS\Serializer\SerializationContext;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Wizardalley\CoreBundle\Entity\Page;
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
        $data = [];

        // Afficher seulement si la publication a ete publier
        if ($publication->getHasBeenPublished()) {
            $data = $this
                ->get('jms_serializer')
                ->serialize($publication, 'json', SerializationContext::create()->setGroups(['publication_detail']))
            ;
        }

        return $this->successResponse($data);
    }

    /**
     * @Get(
     *     path = "/page/publicationList/{pageId}/{latestId}",
     *     name = "api_page_publication_list_show",
     *     requirements = {"pageId"="\d+","latestId"="\d+"}
     * )
     * @View
     */
    public function pagePublicationListAction($pageId, $latestId = null)
    {
        // Recuperer la page
        $page = $this
            ->getDoctrine()
            ->getManager()
            ->getRepository('WizardalleyCoreBundle:Page')
            ->find($pageId)
        ;

        if (!$page instanceof Page) {
            return $this->errorResponse('');
        }

        $publicationRepository =  $this
            ->getDoctrine()
            ->getManager()
            ->getRepository('WizardalleyCoreBundle:Publication');

        $publications = $publicationRepository->findPublicationList($page, 5, $latestId);

        $data = $this
            ->get('jms_serializer')
            ->serialize($publications, 'json', SerializationContext::create()->setGroups(['publication_list']))
        ;

        return $this->successResponse($data);
    }

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
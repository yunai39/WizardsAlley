<?php

namespace Wizardalley\WebServiceBundle\Controller;

use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\View;
use JMS\Serializer\SerializationContext;
use Wizardalley\CoreBundle\Entity\Page;
use Wizardalley\CoreBundle\Entity\Publication;

/**
 * Class PublicationController
 *
 * @package WebServiceBundle\Controller
 */
class PublicationController extends ApiBaseController
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
        // Afficher seulement si la publication a ete publier
        if ($publication->getHasBeenPublished()) {
            $data = $this
                ->get('jms_serializer')
                ->serialize(
                    $publication,
                    'json',
                    SerializationContext::create()->setGroups(['publication_detail', 'user_list'])
                )
            ;

            return $this->successResponse($data);
        } else {
            return $this->errorResponse('');
        }
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

        $publicationRepository = $this
            ->getDoctrine()
            ->getManager()
            ->getRepository('WizardalleyCoreBundle:Publication')
        ;

        $publications = $publicationRepository->findPublicationList($page, 5, $latestId);

        $data = $this
            ->get('jms_serializer')
            ->serialize(
                $publications,
                'json',
                SerializationContext::create()->setGroups(['publication_list', 'user_list'])
            )
        ;

        return $this->successResponse($data);
    }
}
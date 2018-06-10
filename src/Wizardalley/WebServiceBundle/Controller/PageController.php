<?php

namespace Wizardalley\WebServiceBundle\Controller;

use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\View;
use JMS\Serializer\SerializationContext;
use Wizardalley\CoreBundle\Entity\Page;
use Wizardalley\CoreBundle\Entity\PageCategory;

/**
 * Class PageController
 *
 * @package WebServiceBundle\Controller
 */
class PageController extends ApiBaseController
{
    /**
     * @Get(
     *     path = "/page/{id}",
     *     name = "api_page_show",
     *     requirements = {"id"="\d+"}
     * )
     * @View
     */
    public function showAction(Page $page)
    {
        $data = [];

        // Afficher seulement si la publication a ete publier
        $data = $this
            ->get('jms_serializer')
            ->serialize(
                $page,
                'json',
                SerializationContext::create()->setGroups(['page_detail', 'user_list', 'category_list'])
            )
        ;

        return $this->successResponse($data);
    }

    /**
     * @Get(
     *     path = "/category/{id}",
     *     name = "api_category_show",
     *     requirements = {"id"="\d+"}
     * )
     * @View
     */
    public function categoryShowAction(PageCategory $pageCategory)
    {
        $data = $this
            ->get('jms_serializer')
            ->serialize(
                $pageCategory,
                'json',
                SerializationContext::create()->setGroups(['category_detail', 'user_list'])
            )
        ;

        return $this->successResponse($data);
    }

    /**
     * @Get(
     *     path = "/category/pageList/{categoryId}/{latestId}",
     *     name = "api_category_page_list_show",
     *     requirements = {"categoryId"="\d+","latestId"="\d+"}
     * )
     * @View
     */
    public function categoryPageListAction($categoryId, $latestId = null)
    {
        // Recuperer la page
        $category = $this
            ->getDoctrine()
            ->getManager()
            ->getRepository('WizardalleyCoreBundle:PageCategory')
            ->find($categoryId)
        ;

        if (!$category instanceof PageCategory) {
            return $this->errorResponse('');
        }

        $pageRepository = $this
            ->getDoctrine()
            ->getManager()
            ->getRepository('WizardalleyCoreBundle:Page')
        ;

        $publications = $pageRepository->findPageList($category, 5, $latestId);

        $data = $this
            ->get('jms_serializer')
            ->serialize(
                $publications,
                'json',
                SerializationContext::create()->setGroups(['page_list', 'user_list'])
            )
        ;

        return $this->successResponse($data);
    }
}
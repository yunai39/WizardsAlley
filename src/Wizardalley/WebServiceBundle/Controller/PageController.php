<?php

namespace Wizardalley\WebServiceBundle\Controller;

use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\View;
use FOS\RestBundle\Controller\Annotations\QueryParam;
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
     * @ApiDoc(
     *  resource=true,
     *  description="Show page properties",
     *  section="page"
     * )
     * @QueryParam(name="id", requirements="\d+", description="Page id")
     * @Get(
     *     path = "/public/page/{id}",
     *     name = "api_page_show",
     *     requirements = {"id"="\d+"}
     * )
     * @View
     */
    public function showAction(Page $page)
    {
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
     * @ApiDoc(
     *  resource=true,
     *  description="Show category properties",
     *  section="category"
     * )
     * @QueryParam(name="id", requirements="\d+", description="Category id")
     * @Get(
     *     path = "/public/category/{id}",
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
     * @ApiDoc(
     *  resource=true,
     *  description="List categories",
     *  section="category"
     * )
     * @Get(
     *     path = "/public/categories",
     *     name = "api_category_list_show"
     * )
     * @View
     */
    public function listCategoryAction()
    {
        $categories = $this->getDoctrine()->getRepository('WizardalleyCoreBundle:PageCategory')->findAll();
        $data = $this
            ->get('jms_serializer')
            ->serialize(
                $categories,
                'json',
                SerializationContext::create()->setGroups(['category_list'])
            )
        ;

        return $this->successResponse($data);
    }

    /**
     * @ApiDoc(
     *  resource=true,
     *  description="List of pages for a category",
     *  section="page"
     * )
     * @QueryParam(name="categoryId", requirements="\d+", description="Category id")
     * @QueryParam(name="latestId", requirements="\d+", default="null", description="Last page id displayed, this way you will get the next page")
     * @Get(
     *     path = "/public/category/pageList/{categoryId}/{latestId}",
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
<?php

namespace Wizardalley\WebServiceBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\JsonResponse;
use Wizardalley\CoreBundle\Entity\WizardUserRepository;

class DefaultController extends Controller
{
    /**
     * @Route("/test", name="api_test", options={"expose"=true})
     */
    public function testAction()
    {
        return new JsonResponse([ "contenu" => "Ceci est un test"]);
    }

    /**
     * @Route("/getPublication/{page}", name="wizard_api_get_publication_view", options={"expose"=true})
     */
    public function displayPublicationPageAction($page){
        $limit = 2;
        $offset = ($page - 1)* $limit;
        /** @var WizardUserRepository $repo */
        $repo = $this->getDoctrine()->getRepository('WizardalleyCoreBundle:WizardUser');
        $publications = $repo->findPublicationUser($this->getUser(),$offset, $limit);
        return new JsonResponse([
                'result' => 'success',
                'content' => ['publications' => $publications]
            ]
        );
    }
}

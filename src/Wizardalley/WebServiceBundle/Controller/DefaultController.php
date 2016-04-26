<?php

namespace Wizardalley\WebServiceBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Templating\Helper\AssetsHelper;
use Symfony\Bundle\TwigBundle\Extension\AssetsExtension;
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
     * @Route("/getPublication/{publication_id}", name="wizard_api_get_publication_view", options={"expose"=true})
     */
    public function displayPublicationPageAction($publication_id){
        $limit = 2;
        /** @var WizardUserRepository $repo */
        $repo = $this->getDoctrine()->getRepository('WizardalleyCoreBundle:WizardUser');
        $publications = $repo->findPublicationUser($this->getUser(),$publication_id, $limit);
        /** @var AssetsHelper $assetExtension */
        $assetExtension = $this->get('templating.helper.assets');
        $last_id = 1;
        foreach($publications as $key => $publication) {
            if($publication['type'] == 'page_publication'){
                $publications[$key]['img_profile'] = $assetExtension->getUrl(
                    'uploads/page/' . $publication['writer_id'] . '/' . $publication['path_profile']
                );
                $publications[$key]['path_publication'] = $this->generateUrl(
                    'publication_show',
                    [
                        'id' => $publication['publication_id']
                    ]
                );
                $publication[$key]['writer_link'] = $this->generateUrl(
                    'page_show',
                    [
                        'id_page' => $publication['writer_id']
                    ]
                );
            } else {
                $publications[$key]['img_profile'] = $assetExtension->getUrl(
                    'uploads/profile/' . $publication['writer_id'] . '/' . $publication['path_profile']
                );
                $publication[$key]['writer_link'] = $this->generateUrl(
                    'wizardalley_user_wall',
                    [
                        'id' => $publication['writer_id']
                    ]
                );
            }
            $last_id = $publication['publication_id'];
        }
        return new JsonResponse([
                'result' => 'success',
                'last_id' => $last_id,
                'content' => ['publications' => $publications]
            ]
        );
    }
}

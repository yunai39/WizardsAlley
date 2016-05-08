<?php

namespace Wizardalley\WebServiceBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Templating\Helper\AssetsHelper;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Wizardalley\CoreBundle\Entity\SmallPublication;
use Wizardalley\CoreBundle\Entity\WizardUserRepository;
use Wizardalley\PublicationBundle\Form\SmallPublicationType;

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
     * @Route("/getCsrfToken", name="wizard_api_get_csrf_token", options={"expose"=true})
     */
    public function getCsrfTokenAction(Request $request)
    {
        $csrf = $this->get('form.csrf_provider'); //Symfony\Component\Form\Extension\Csrf\CsrfProvider\SessionCsrfProvider by default
        $token = $csrf->generateCsrfToken(''); //Intention should be empty string, if you did not define it in parameters
        return new JsonResponse([
                'result' => 'success',
                'content' => ['token' => $token]
            ]
        );
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
    /**
     * Creates a new SmallPublication entity.
     * @Route("/addSmallPublication", name="wizard_api_add_small_publication", options={"expose"=true})
     */
    public function createSmallPublicationAction(Request $request)
    {
        $entity = new SmallPublication();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $user = $this->getUser();
            $entity->setUser($user);
            $entity->setDatePublication(new \DateTime('now'));
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return new JsonResponse([
                'result' => 'success',
                'message' => 'wizard.smallPublication.add.success'
            ]);
        }
        return new JsonResponse([
                'result' => 'error',
                'message' => 'wizard.smallPublication.add.error',
                'error'     => $form->getErrors()
            ],
            500
        );
    }


    /**
     * Creates a form to create a SmallPublication entity.
     *
     * @param SmallPublication $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(SmallPublication $entity)
    {
        $form = $this->createForm(new SmallPublicationType(), $entity, array(
            'action' => $this->generateUrl('user_small_publication_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

}

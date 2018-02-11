<?php

namespace Wizardalley\WebServiceBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Templating\Helper\AssetsHelper;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Wizardalley\CoreBundle\Entity\SmallPublication;
use Wizardalley\CoreBundle\Entity\WizardUser;
use Wizardalley\CoreBundle\Entity\WizardUserRepository;
use Wizardalley\PublicationBundle\Form\SmallPublicationType;

class DefaultController extends BaseController
{
    /**
     * @Route("/test", name="api_test", options={"expose"=true})
     */
    public function testAction()
    {
        return new JsonResponse(["contenu" => "Ceci est un test"]);
    }

    /**
     * @Route("/getCsrfToken/{form_name}", name="wizard_api_get_csrf_token", options={"expose"=true})
     */
    public function getCsrfTokenAction(Request $request, $form_name)
    {
        $csrf  =
            $this->get(
                'security.csrf.token_manager'
            ); //Symfony\Component\Form\Extension\Csrf\CsrfProvider\SessionCsrfProvider by default
        $token = $csrf->refreshToken($form_name);

        //Intention should be empty string, if you did not define it in parameters
        return $this->buildSuccessResponse(['token' => $token->getValue()]);
    }

    /**
     * @Route("/getPublication/{publication_id}", name="wizard_api_get_publication_view", options={"expose"=true})
     */
    public function displayPublicationPageAction($publication_id)
    {
        /** @var WizardUserRepository $repo */
        $repo         = $this->getDoctrine()->getRepository('WizardalleyCoreBundle:WizardUser');
        $publications = $repo->findPublicationUser($this->getUser(), $publication_id);
        /** @var AssetsHelper $assetExtension */
        $assetExtension = $this->get('templating.helper.assets');
        $last_id        = 1;
        foreach ($publications as $key => $publication) {
            if ($publication[ 'type' ] == 'page_publication') {
                $publications[ $key ][ 'img_profile' ]      = $assetExtension->getUrl(
                    'uploads/page/' . $publication[ 'writer_id' ] . '/' . $publication[ 'path_profile' ]
                );
                $publications[ $key ][ 'path_publication' ] =
                    $this->generateUrl('publication_show', ['id' => $publication[ 'publication_id' ]]);
                $publication[ $key ][ 'writer_link' ]       =
                    $this->generateUrl('page_show', ['id_page' => $publication[ 'writer_id' ]]);
            } else {
                $publications[ $key ][ 'img_profile' ] =
                    $assetExtension->getUrl(
                        'uploads/profile/' . $publication[ 'writer_id' ] . '/' . $publication[ 'path_profile' ]
                    );
                $publication[ $key ][ 'writer_link' ]  =
                    $this->generateUrl('wizardalley_user_wall', ['id' => $publication[ 'writer_id' ]]);
            }
            $last_id = $publication[ 'publication_id' ];
        }

        return new JsonResponse(
            [
                'result'  => 'success',
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
        $form   = $this->createCreateForm($entity);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $user = $this->getUser();
            $entity->setUser($user);
            $entity->setCreatedAt(new \DateTime('now'));
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return new JsonResponse(
                [
                    'result'  => 'success',
                    'message' => 'wizard.smallPublication.add.success'
                ]
            );
        }
        $errors = [];
        /** @var \Symfony\Component\Form\FormError $error */
        foreach ($form->getErrors(true) as $error) {
            $errors[ $error->getOrigin()->getName() ] = $error->getMessage();
        }

        return $this->buildErrorResponse($errors, 'wizard.smallPublication.add.error');
    }

    /**
     * Creates a new SmallPublication entity.
     * @Route("/getUserInfo", name="wizard_api_get_user_info", options={"expose"=true})
     */
    public function getUserInfo()
    {
        /** @var WizardUser $user */
        $user = $this->getUser();

        return $this->buildSuccessResponse(
            [
                'id'          => $user->getId(),
                'username'    => $user->getUsername(),
                'firstname'   => $user->getFirstname(),
                'email'       => $user->getEmail(),
                'lastname'    => $user->getLastname(),
                'pathCover'   => $user->getWebPathCouverture(),
                'pathProfile' => $user->getWebPathProfile(),
            ]
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
        $form = $this->createForm(
            new SmallPublicationType(), $entity, [
                'action' => $this->generateUrl('user_small_publication_create'),
                'method' => 'POST',
            ]
        );

        $form->add('submit', 'submit', ['label' => 'wizard.utility.form.create']);

        return $form;
    }
}

<?php

namespace Wizardalley\UserBundle\Controller;

use Liip\ImagineBundle\Imagine\Cache\CacheManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Wizardalley\CoreBundle\Entity\WizardUser;

/**
 * Controller managing the user profile
 *
 * @author Christophe Coevoet <stof@notk.org>
 */
class ProfilePictureController extends Controller
{
    /**
     * @return Response
     */
    public function editProfilePictureAction()
    {
        $form = $this->createFormBuilder($this->getUser())->add('fileProfile')->getForm();
        if ($this->getRequest()->isMethod('POST')) {
            $form->handleRequest($this->getRequest());
            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                /** @var WizardUser $user */
                $user = $this->getUser();
                $user->uploadProfile();
                $em->persist($this->getUser());
                $em->flush();

                /** @var $cacheManager CacheManager */
                $cacheManager = $this->get('liip_imagine.cache.manager');
                $cacheManager->remove($user->getWebPathProfile());
            }
        }

        return $this->render(
            'WizardalleyUserBundle:Profile:editPicture.html.twig',
            [
                'form' => $form->createView(),
                'user' => $this->getUser()
            ]
        );
    }

    /**
     * @return Response
     */
    public function editCouverturePictureAction()
    {
        $form = $this->createFormBuilder($this->getUser())->add('fileCouverture')->getForm();

        if ($this->getRequest()->isMethod('POST')) {
            $form->handleRequest($this->getRequest());
            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                /** @var WizardUser $user */
                $user = $this->getUser();
                $user->uploadCouverture();
                $em->persist($user);
                $em->flush();
                /** @var $cacheManager CacheManager */
                $cacheManager = $this->get('liip_imagine.cache.manager');
                $cacheManager->remove($user->getWebPathCouverture());
            }
        }

        return $this->render(
            'WizardalleyUserBundle:Profile:editPictureCouverture.html.twig',
            [
                'form' => $form->createView(),
                'user' => $this->getUser()
            ]
        );
    }
}

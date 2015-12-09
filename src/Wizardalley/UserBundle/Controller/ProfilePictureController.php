<?php

namespace Wizardalley\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Controller managing the user profile
 *
 * @author Christophe Coevoet <stof@notk.org>
 */
class ProfilePictureController extends Controller {

    /**
     * 
     * @return type
     */
    public function editProfilePictureAction() {
        $form = $this->createFormBuilder($this->getUser())
                ->add('fileProfile'
                )
                ->getForm()
        ;

        if ($this->getRequest()->isMethod('POST')) {
            $form->handleRequest($this->getRequest());
            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $this->getUser()->uploadProfile();
                $em->persist($this->getUser());
                $em->flush();
            }
        }

        return $this->render('WizardalleyUserBundle:Profile:editPicture.html.twig', array(
                    'form' => $form->createView()
        ));
    }

    /**
     * 
     * @return type
     */
    public function editCouverturePictureAction() {
        $form = $this->createFormBuilder($this->getUser())
                ->add('fileCouverture'
                )
                ->getForm()
        ;

        if ($this->getRequest()->isMethod('POST')) {
            $form->handleRequest($this->getRequest());
            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $this->getUser()->uploadCouverture();
                $em->persist($this->getUser());
                $em->flush();
            }
        }

        return $this->render('WizardalleyUserBundle:Profile:editPictureCouverture.html.twig', array(
                    'form' => $form->createView()
        ));
    }

}

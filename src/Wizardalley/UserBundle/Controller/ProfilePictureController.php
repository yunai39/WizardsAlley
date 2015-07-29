<?php

/*
 * This file is part of the FOSUserBundle package.
 *
 * (c) FriendsOfSymfony <http://friendsofsymfony.github.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Wizardalley\UserBundle\Controller;

use Symfony\Component\DependencyInjection\ContainerAware;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use FOS\UserBundle\Controller\ProfileController as Base;

/**
 * Controller managing the user profile
 *
 * @author Christophe Coevoet <stof@notk.org>
 */
class ProfilePictureController extends Controller
{
    public function editProfilePictureAction(Request $request){
        $form = $this->createFormBuilder($this->getUser())
        ->add('file')
        ->getForm()
    ;

    if ($this->getRequest()->isMethod('POST')) {
        $form->handleRequest($this->getRequest());
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $this->getUser()->upload();
            $em->persist($this->getUser());
            $em->flush();

        }
    }

    return $this->render('WizardalleyUserBundle:Profile:editPicture.html.twig', array(
        'form' => $form->createView()
    ));
    }
}

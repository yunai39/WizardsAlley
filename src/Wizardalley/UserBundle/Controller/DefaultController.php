<?php
namespace Wizardalley\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
  public function userWallAction(Request $request, $id)
  {
    //
    $repo = $this->getDoctrine()->getRepository('WizardalleyUserBundle:WizardUser');
    $user = $repo->find($id);
    if(!$user){
        return new \Symfony\Component\Translation\Exception\NotFoundResourceException();
    }
    return $this->render('WizardalleyUserBundle:Default:userWall.html.twig', array(
      'user' => $user,
    ));
  }
}
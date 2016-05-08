<?php

namespace Wizardalley\FrontBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Routing\Annotation\Route;
/**
 * Class DefaultController
 * @package Wizardalley\FrontBundle\Controller
 */
class DefaultController extends Controller
{
    /**
     * @Route("/wizardalley")
     * @Template()
     * @param string $name
     * @return array
     */
    public function indexAction()
    {
        return [];
    }
}

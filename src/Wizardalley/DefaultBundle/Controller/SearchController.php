<?php

namespace Wizardalley\DefaultBundle\Controller;

use Wizardalley\DefaultBundle\Form\SearchType;

class SearchController extends BaseController
{
    
    public function searchDisplayAction()
    {
        
        $form = $this->createForm(new SearchType(), null, array(
            'method' => 'POST',
        ));
        $form->add('submit', 'submit', array('label' => 'Create'));
       return $this->render('WizardalleyDefaultBundle:Default:search.html.twig', [ 'form' => $form->createView() ]);
    }
}

<?php

namespace Wizardalley\AdminBundle\Controller;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;
use Wizardalley\CoreBundle\Entity\Page;
use Wizardalley\CoreBundle\Entity\PublicationFavorite;
use Wizardalley\PublicationBundle\Form\PageType;

/**
 * Publication controller.
 *
 * @Route("/admin/publication")
 */
class PublicationController extends Controller
{
    /**
     * Deletes a Publication entity.
     *
     * @Route("/{id}", name="admin_publication_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em     = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('WizardalleyCoreBundle:Publication')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Publication entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('admin_list_page', ['tableName' => 'publication']));
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function renderFormDeleteTemplateAction()
    {
        $form = $this->createFormBuilder()
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm();
        return $this->render('WizardalleyAdminBundle:Table:renderForm.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function renderFormToggleFavoriteTemplateAction()
    {
        $form = $this->createFormBuilder()
            ->setMethod('PUT')
            ->add('submit', 'submit', array('label' => 'wizard.admin.publication.favorite.toogle'))
            ->getForm();
        return $this->render('WizardalleyAdminBundle:Table:renderForm.html.twig', ['form' => $form->createView()]);
    }


    /**
     * Toogle a Publication entity favorite.
     *
     * @param Request $request
     * @param int     $id
     * @return Response
     * @Route("/favoriteToggle/{id}", name="admin_publication_favorite_toggle")
     */
    public function toggleFavoritePublicationAction(Request $request, $id)
    {

        $em     = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('WizardalleyCoreBundle:PublicationFavorite')
            ->findOneBy(['publication' => $id]);

        // Si on a pas d'entite creer le favori
        if ($entity instanceof PublicationFavorite) {
            $em->remove($entity);
        } else {
            $publication = $em->getRepository('WizardalleyCoreBundle:Publication')->find($id);
            if (!$publication) {
                throw $this->createNotFoundException('Unable to find Publication entity.');
            }
            $publicationFavorite = new PublicationFavorite();
            $publicationFavorite->setDateFavorite(new \DateTime());
            $publicationFavorite->setPublication($em->getReference('WizardalleyCoreBundle:Publication', $id));
            $em->persist($publicationFavorite);
        }
        $em->flush();

        return new RedirectResponse($request->headers->get('referer'));
    }

    /**
     * Creates a form to delete a Page entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_publication_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm();
    }
}

<?php

namespace BoShurik\SurveyBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use BoShurik\SurveyBundle\Entity\Survey;
use BoShurik\SurveyBundle\Form\SurveyType;

/**
 * Survey controller.
 *
 */
class SurveyController extends Controller
{
    /**
     * Lists all Survey entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entities = $em->getRepository('BoShurikSurveyBundle:Survey')->findAll();

        return $this->render('BoShurikSurveyBundle:Survey:index.html.twig', array(
            'entities' => $entities
        ));
    }

    /**
     * Finds and displays a Survey entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('BoShurikSurveyBundle:Survey')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Survey entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('BoShurikSurveyBundle:Survey:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),

        ));
    }

    /**
     * Displays a form to create a new Survey entity.
     *
     */
    public function newAction()
    {
        $entity = new Survey();
        $form   = $this->createForm(new SurveyType(), $entity);

        return $this->render('BoShurikSurveyBundle:Survey:edit.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
            'delete_form' => false
        ));
    }

    /**
     * Creates a new Survey entity.
     *
     */
    public function createAction()
    {
        $entity  = new Survey();
        $request = $this->getRequest();
        $form    = $this->createForm(new SurveyType(), $entity);
        $form->bindRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('survey_show', array('id' => $entity->getId())));
            
        }

        return $this->render('BoShurikSurveyBundle:Survey:edit.html.twig', array(
            'entity'      => $entity,
            'form'        => $form->createView(),
            'delete_form' => false
        ));
    }

    /**
     * Displays a form to edit an existing Survey entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('BoShurikSurveyBundle:Survey')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Survey entity.');
        }

        $editForm = $this->createForm(new SurveyType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('BoShurikSurveyBundle:Survey:edit.html.twig', array(
            'entity'      => $entity,
            'form'        => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Edits an existing Survey entity.
     *
     */
    public function updateAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('BoShurikSurveyBundle:Survey')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Survey entity.');
        }

        $editForm   = $this->createForm(new SurveyType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        $request = $this->getRequest();

        $editForm->bindRequest($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('survey_edit', array('id' => $id)));
        }

        return $this->render('BoShurikSurveyBundle:Survey:edit.html.twig', array(
            'entity'      => $entity,
            'form'        => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Survey entity.
     *
     */
    public function deleteAction($id)
    {
        $form = $this->createDeleteForm($id);
        $request = $this->getRequest();

        $form->bindRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            $entity = $em->getRepository('BoShurikSurveyBundle:Survey')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Survey entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('survey'));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
}

<?php

namespace BoShurik\SurveyBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use BoShurik\SurveyBundle\Entity\Survey;
use BoShurik\SurveyBundle\Form\SurveyType;

use BoShurik\SurveyBundle\Entity\Answer;
use BoShurik\UserBundle\Entity\User;
use Symfony\Component\HttpFoundation\Request;

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
        $em = $this->getDoctrine()->getManager();

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
        $em = $this->getDoctrine()->getManager();

        //TODO: Оптимизировать этот запросик, чтоб все сразу вытаскивалось
        $entity = $em->getRepository('BoShurikSurveyBundle:Survey')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Survey entity.');
        }

        $builder = $this->createFormBuilder();

        foreach ($entity->getQuestions() as $question) {
            $choices = array();
            foreach ($question->getChoices() as $choice) {
                $choices[$choice->getId()] = $choice->getName();
            }
            $builder->add('question_' . $question->getId(), 'choice', array(
                'label' => $question->getName(),
                'expanded' => $question->getExpanded(),
                'multiple' => $question->getMultiple(),
                'choices' => $choices
            ));
        }

        $form = $builder->getForm();

        return $this->render('BoShurikSurveyBundle:Survey:show.html.twig', array(
            'entity' => $entity,
            'form' => $form->createView()
        ));
    }

    public function answerAction(Request $request, $id)
    {
        $user = $this->get('security.context')->getToken()->getUser();
        if (!$user instanceof User) {
            throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException();
        }

        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('BoShurikSurveyBundle:Survey')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Survey entity.');
        }

        // TODO: Move this code to form type
        $builder = $this->createFormBuilder();

        $choiceEntities = array();
        foreach ($entity->getQuestions() as $question) {
            $choices = array();
            foreach ($question->getChoices() as $choice) {
                $choices[$choice->getId()] = $choice->getName();
                $choiceEntities[$choice->getId()] = $choice;
            }
            $builder->add('question_' . $question->getId(), 'choice', array(
                'label' => $question->getName(),
                'expanded' => $question->getExpanded(),
                'multiple' => $question->getMultiple(),
                'choices' => $choices
            ));
        }

        $form = $builder->getForm();
        $form->handleRequest($request);
        if ($form->isValid()) {
            $answer = new Answer($user, $entity);

            $data = $form->getData();
            // TODO: Move this code to model
            foreach ($entity->getQuestions() as $question) {
                if (isset($data['question_' . $question->getId()])) {
                    if (is_array($data['question_' . $question->getId()]) && $question->getMultiple()) {
                        foreach ($data['question_' . $question->getId()] as $value) {
                            if (isset($choiceEntities[$value])) {
                                $answer->addChoice($choiceEntities[$value]);
                            } else {
                                throw new \Exception('Wrong data!');
                            }
                        }
                    } else if (!is_array($data['question_' . $question->getId()]) && !$question->getMultiple()) {
                        if (isset($choiceEntities[$data['question_' . $question->getId()]])) {
                            $answer->addChoice($choiceEntities[$data['question_' . $question->getId()]]);
                        } else {
                            throw new \Exception('Wrong data!');
                        }
                    } else {
                        throw new \Exception('Wrong data!');
                    }
                }
            }

            $em->persist($answer);
            $em->flush();

            return $this->redirect($this->generateUrl('survey_show', array('id' => $entity->getId())));
        }

        return $this->render('BoShurikSurveyBundle:Survey:show.html.twig', array(
            'entity' => $entity,
            'form' => $form->createView()
        ));
    }

    public function resultAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('BoShurikSurveyBundle:Survey')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Survey entity.');
        }

        //TODO: Вывод результатов
        $answers = $em->getRepository('BoShurikSurveyBundle:Answer')->findBy(array(
            'survey' => $entity->getId()
        ));

        return $this->render('BoShurikSurveyBundle:Survey:result.html.twig', array(
            'entity' => $entity,
            'answers' => $answers
        ));
    }

    /**
     * Displays a form to create a new Survey entity.
     *
     */
    public function newAction(Request $request)
    {
        $entity = new Survey();
        $form = $this->createForm(new SurveyType(), $entity, array(
            'action' => $this->generateUrl('survey_create')
        ));
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('survey_show', array('id' => $entity->getId())));
        }

        return $this->render('BoShurikSurveyBundle:Survey:edit.html.twig', array(
            'entity' => $entity,
            'form' => $form->createView(),
            'delete_form' => false
        ));
    }

    /**
     * Displays a form to edit an existing Survey entity.
     *
     */
    public function editAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('BoShurikSurveyBundle:Survey')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Survey entity.');
        }

        $editForm = $this->createForm(new SurveyType(), $entity, array(
            'action' => $this->generateUrl('survey_update', array('id' => $id))
        ));
        $deleteForm = $this->createDeleteForm($id);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('survey_edit', array('id' => $id)));
        }

        return $this->render('BoShurikSurveyBundle:Survey:edit.html.twig', array(
            'entity' => $entity,
            'form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Survey entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);

        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
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
        return $this->createFormBuilder(array('id' => $id), array(
                'action' => $this->generateUrl('survey_delete', array('id' => $id)),
                'method' => 'POST',
            ))
            ->add('id', 'hidden')
            ->add('delete', 'submit')
            ->getForm();
    }
}

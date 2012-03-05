<?php

namespace BoShurik\SurveyBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use BoShurik\SurveyBundle\Entity\Survey;
use BoShurik\SurveyBundle\Form\SurveyType;

use BoShurik\SurveyBundle\Entity\Answer;
use BoShurik\UserBundle\Entity\User;

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

        //TODO: Оптимизировать этот запросик, чтоб все сразу вытаскивалось
        $entity = $em->getRepository('BoShurikSurveyBundle:Survey')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Survey entity.');
        }

        $builder = $this->createFormBuilder();

        foreach ($entity->getQuestions() as $question)
        {
            $choices = array();
            foreach ($question->getChoices() as $choice)
            {
                $choices[$choice->getId()] = $choice->getName();
            }
            $builder->add('question_'. $question->getId(), 'choice', array(
                'label' => $question->getName(),
                'expanded' => $question->getExpanded(),
                'multiple' => $question->getMultiple(),
                'choices' => $choices
            ));
        }

        $form = $builder->getForm();

        return $this->render('BoShurikSurveyBundle:Survey:show.html.twig', array(
            'entity'      => $entity,
            'form'        => $form->createView()
        ));
    }

    public function answerAction($id)
    {
        $user = $this->get('security.context')->getToken()->getUser();
        if (!$user instanceof User)
        {
            throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException();
        }

        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('BoShurikSurveyBundle:Survey')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Survey entity.');
        }

        $builder = $this->createFormBuilder();

        $choiceEntities = array();
        foreach ($entity->getQuestions() as $question)
        {
            $choices = array();
            foreach ($question->getChoices() as $choice)
            {
                $choices[$choice->getId()] = $choice->getName();
                $choiceEntities[$choice->getId()] = $choice;
            }
            $builder->add('question_'. $question->getId(), 'choice', array(
                'label' => $question->getName(),
                'expanded' => $question->getExpanded(),
                'multiple' => $question->getMultiple(),
                'choices' => $choices
            ));
        }

        $form = $builder->getForm();

        $request = $this->getRequest();
        $form->bindRequest($request);
        if ($form->isValid()) {
            $answer = new Answer($user, $entity);

            $data = $form->getData();
            foreach ($entity->getQuestions() as $question)
            {
                if (isset($data['question_'. $question->getId()]))
                {
                    if (is_array($data['question_'. $question->getId()]) && $question->getMultiple())
                    {
                        foreach ($data['question_'. $question->getId()] as $value)
                        {
                            if (isset($choiceEntities[$value]))
                            {
                                $answer->addChoice($choiceEntities[$value]);
                            }
                            else
                            {
                                throw new \Exception('Wrong data!');
                            }
                        }
                    }
                    else if (!is_array($data['question_'. $question->getId()]) && !$question->getMultiple())
                    {
                        if (isset($choiceEntities[$data['question_'. $question->getId()]]))
                        {
                            $answer->addChoice($choiceEntities[$data['question_'. $question->getId()]]);
                        }
                        else
                        {
                            throw new \Exception('Wrong data!');
                        }
                    }
                    else
                    {
                        throw new \Exception('Wrong data!');
                    }
                }
            }

            $em->persist($answer);
            $em->flush();

            return $this->redirect($this->generateUrl('survey_show', array('id' => $entity->getId())));
        }

        return $this->render('BoShurikSurveyBundle:Survey:show.html.twig', array(
            'entity'      => $entity,
            'form'        => $form->createView()
        ));
    }

    public function resultAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('BoShurikSurveyBundle:Survey')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Survey entity.');
        }

        //TODO: Вывод результатов
        $answers = $em->getRepository('BoShurikSurveyBundle:Answer')->findBy(array(
            'survey' => $entity->getId()
        ));

        return $this->render('BoShurikSurveyBundle:Survey:result.html.twig', array(
            'entity'      => $entity,
            'answers'     => $answers
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
            foreach ($entity->getQuestions() as $question)
            {
                $question->setSurvey($entity);
                foreach ($question->getChoices() as $choice)
                {
                    $choice->setQuestion($question);
                }
            }
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

        $oldQuestions = array();
        $oldChoices = array();
        foreach ($entity->getQuestions() as $question)
        {
            $oldQuestions[$question->getId()] = $question;
            foreach ($question->getChoices() as $choice)
            {
                $oldChoices[$choice->getId()] = $choice;
            }
        }

        $editForm   = $this->createForm(new SurveyType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        $request = $this->getRequest();

        $editForm->bindRequest($request);

        if ($editForm->isValid()) {
            foreach ($entity->getQuestions() as $question)
            {
                if (!$question->getId())
                {
                    $question->setSurvey($entity);
                }
                else
                {
                    unset($oldQuestions[$question->getId()]);
                }

                foreach ($question->getChoices() as $choice)
                {
                    if (!$question->getId())
                    {
                        $choice->setQuestion($question);
                    }
                    else
                    {
                        unset($oldChoices[$choice->getId()]);
                    }
                }
            }

            foreach ($oldQuestions as $question)
            {
                $em->remove($question);
            }

            foreach ($oldChoices as $choice)
            {
                $em->remove($choice);
            }

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

<?php

namespace Brother\ContactUsBundle\Controller;

use Brother\CommonBundle\AppDebug;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Brother\ContactUsBundle\Entity\Entry;
use Brother\ContactUsBundle\Form\EntryType;

/**
 * Entry controller.
 *
 */
class EntryController extends Controller
{

    /**
     * Lists all Entry entities.
     *
     */
    public function indexAction(Request $request)
    {
        $entity = new Entry();
        $form = $this->createCreateForm($entity);
        if ($request->isMethod('post')) {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($entity);
                $em->flush();
                $this->get('session')->getFlashBag()->add(
                    'notice',
                    'Ваше сообщение успешно получено. Вы можете отправить ещё одно.'
                );
                return $this->redirect($this->generateUrl('contact_us', array('id' => $entity->getId())));
            }
        }

        return $this->render('BrotherContactUsBundle:Entry:index.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a Entry entity.
     *
     * @param Entry $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Entry $entity)
    {
        $form = $this->createForm(new EntryType(), $entity, array(
            'action' => $this->generateUrl('contact_us'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Отправить'));

        return $form;
    }
}
